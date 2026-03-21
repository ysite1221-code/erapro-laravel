<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Mail\InquiryStatusChangedNotification;
use App\Mail\NewMessageNotification;
use App\Models\Inquiry;
use App\Models\InquiryMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
            ->with(['user', 'latestMessage'])
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

        $inquiry->load(['user', 'messages']);

        return view('agent.inquiries.show', [
            'inquiry'      => $inquiry,
            'statusLabels' => self::STATUS_LABELS,
        ]);
    }

    public function updateStatus(Request $request, Inquiry $inquiry): RedirectResponse
    {
        abort_if($inquiry->agent_id !== Auth::guard('agent')->id(), 403);

        $request->validate([
            'status'          => ['required', 'integer', 'in:1,2,3,4,5'],
            'completion_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $updateData = ['status' => $request->integer('status')];

        if ($request->integer('status') === 4) {
            $updateData['completion_note'] = $request->input('completion_note');
        }

        $inquiry->update($updateData);

        // ユーザーへステータス変更メール通知
        $inquiry->loadMissing('user');
        if ($inquiry->user && $inquiry->user->email) {
            $agent      = Auth::guard('agent')->user();
            $statusLabel = self::STATUS_LABELS[$request->integer('status')] ?? '';
            try {
                Mail::to($inquiry->user->email)->send(new InquiryStatusChangedNotification(
                    agentName:   $agent->name,
                    statusLabel: $statusLabel,
                    inquiryUrl:  route('user.inquiries.show', $inquiry->id),
                ));
            } catch (\Throwable $e) {
                \Log::warning('InquiryStatusChangedNotification mail failed: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('agent.inquiries.show', $inquiry)
            ->with('status', 'ステータスを更新しました。');
    }

    public function storeMessage(Request $request, Inquiry $inquiry): JsonResponse|RedirectResponse
    {
        abort_if($inquiry->agent_id !== Auth::guard('agent')->id(), 403);

        $request->validate(['message' => ['required', 'string', 'max:2000']]);

        $agent = Auth::guard('agent')->user();

        $msg = InquiryMessage::create([
            'inquiry_id'  => $inquiry->id,
            'sender_type' => 'agent',
            'message'     => $request->message,
        ]);

        // ユーザーへメール通知
        $inquiry->loadMissing('user');
        if ($inquiry->user && $inquiry->user->email) {
            try {
                Mail::to($inquiry->user->email)->send(new NewMessageNotification(
                    senderName:      $agent->name,
                    messagePreview:  $request->message,
                    inquiryUrl:      route('user.inquiries.show', $inquiry->id),
                ));
            } catch (\Throwable $e) {
                \Log::warning('NewMessageNotification mail failed: ' . $e->getMessage());
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'id'           => $msg->id,
                'sender_type'  => $msg->sender_type,
                'message'      => $msg->message,
                'created_at'   => $msg->created_at->format('n/j H:i'),
                'sender_name'  => $agent->name,
            ]);
        }

        return redirect()->route('agent.inquiries.show', $inquiry)
            ->with('status', 'メッセージを送信しました。');
    }
}
