<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    private const STATUS_LABELS = [
        0 => '未対応',
        1 => '対応中',
        2 => '対応済み',
        9 => '却下',
    ];

    public function index(Request $request): View
    {
        $query = Report::with(['user', 'agent'])->latest();

        if ($request->filled('reporter_type')) {
            $query->where('reporter_type', $request->input('reporter_type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $reports = $query->paginate(20)->withQueryString();

        return view('admin.reports.index', [
            'reports'      => $reports,
            'statusLabels' => self::STATUS_LABELS,
        ]);
    }

    public function show(Report $report): View
    {
        $report->load(['user', 'agent']);

        return view('admin.reports.show', [
            'report'       => $report,
            'statusLabels' => self::STATUS_LABELS,
        ]);
    }

    public function update(Request $request, Report $report): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'integer', 'in:0,1,2,9'],
        ]);

        $newStatus = $request->integer('status');

        // ステータスが変わった場合、ユーザー側を未読にリセット（通報者=userの場合のみ）
        $isReadByUser = ($report->reporter_type !== 'user' || $newStatus === $report->status)
            ? $report->is_read_by_user
            : false;

        $report->update([
            'status'           => $newStatus,
            'is_read_by_user'  => $isReadByUser,
        ]);

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('status', '対応ステータスを更新しました。');
    }

    public function banAgent(Request $request, Report $report): RedirectResponse
    {
        $agent = Agent::findOrFail($report->agent_id);

        if ($agent->life_flg) {
            // 現在停止中 → 有効化
            $agent->update(['life_flg' => 0, 'suspension_reason' => null]);
            $msg = "{$agent->name} のアカウントを有効化しました。";
        } else {
            // 現在有効 → 停止
            $reason = $request->input('suspension_reason', '');
            $agent->update([
                'life_flg'          => 1,
                'suspension_reason' => $reason ?: null,
            ]);
            $msg = "{$agent->name} のアカウントを停止しました。";
        }

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('status', $msg);
    }
}
