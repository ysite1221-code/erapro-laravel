<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        $report->update(['status' => $request->integer('status')]);

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('status', '対応ステータスを更新しました。');
    }
}
