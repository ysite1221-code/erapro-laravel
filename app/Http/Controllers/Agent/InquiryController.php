<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InquiryController extends Controller
{
    private const STATUS_LABELS = [
        1 => 'リクエスト送信済',
        2 => '日程調整中',
        3 => '面談完了・提案中',
        4 => '完了',
        5 => 'キャンセル',
    ];

    public function index(): View
    {
        $agentId = Auth::guard('agent')->id();

        $inquiries = Inquiry::where('agent_id', $agentId)
            ->with('user')
            ->latest()
            ->get();

        return view('agent.inquiries.index', [
            'inquiries'    => $inquiries,
            'statusLabels' => self::STATUS_LABELS,
        ]);
    }

    public function show(Inquiry $inquiry): View
    {
        abort_if($inquiry->agent_id !== Auth::guard('agent')->id(), 403);

        $inquiry->load('user');

        return view('agent.inquiries.show', [
            'inquiry'      => $inquiry,
            'statusLabels' => self::STATUS_LABELS,
        ]);
    }

    public function updateStatus(Request $request, Inquiry $inquiry): RedirectResponse
    {
        abort_if($inquiry->agent_id !== Auth::guard('agent')->id(), 403);

        $request->validate([
            'status' => ['required', 'integer', 'in:1,2,3,4,5'],
        ]);

        $inquiry->update(['status' => $request->integer('status')]);

        return redirect()
            ->route('agent.inquiries.show', $inquiry)
            ->with('status', 'ステータスを更新しました。');
    }
}
