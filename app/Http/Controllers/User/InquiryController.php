<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\NewInquiryNotification;
use App\Mail\NewMessageNotification;
use App\Models\Agent;
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
        $userId = Auth::guard('user')->id();

        $inquiries = Inquiry::where('user_id', $userId)
            ->with(['agent', 'latestMessage'])
            ->latest()
            ->get();

        return view('user.inquiries.index', [
            'inquiries'    => $inquiries,
            'statusLabels' => self::STATUS_LABELS,
        ]);
    }

    public function show(Inquiry $inquiry): View
    {
        abort_if($inquiry->user_id !== Auth::guard('user')->id(), 403);

        $inquiry->load(['agent', 'messages']);

        // エージェントからのメッセージを既読化
        $inquiry->messages()
            ->where('sender_type', 'agent')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('user.inquiries.show', [
            'inquiry'      => $inquiry,
            'statusLabels' => self::STATUS_LABELS,
        ]);
    }

    public function create(int $agentId): View|RedirectResponse
    {
        $agent = Agent::where('life_flg', 0)
            ->where('verification_status', 2)
            ->find($agentId);

        if (!$agent) {
            return redirect()->route('search');
        }

        // 既に相談済みかチェック
        $existing = Inquiry::where('user_id', Auth::guard('user')->id())
            ->where('agent_id', $agentId)
            ->first();

        return view('user.inquiry_form', compact('agent', 'existing'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'agent_id'        => ['required', 'integer', 'min:1', 'exists:agents,id'],
            'purpose'         => ['required', 'string', 'max:255'],
            'trigger'         => ['nullable', 'string', 'max:255'],
            'preferred_style' => ['nullable', 'string', 'max:255'],
            'note'            => ['nullable', 'string', 'max:1000'],
        ]);

        $userId = Auth::guard('user')->id();
        $user   = Auth::guard('user')->user();

        $inquiry = Inquiry::updateOrCreate(
            ['user_id' => $userId, 'agent_id' => $validated['agent_id']],
            [
                'purpose'         => $validated['purpose'],
                'trigger'         => $validated['trigger'] ?? null,
                'preferred_style' => $validated['preferred_style'] ?? null,
                'note'            => $validated['note'] ?? null,
                'status'          => 1,
            ]
        );

        // エージェントへメール通知
        $agent = Agent::find($validated['agent_id']);
        if ($agent && $agent->email) {
            try {
                Mail::to($agent->email)->send(new NewInquiryNotification(
                    userName:   $user->name,
                    purpose:    $validated['purpose'],
                    inquiryUrl: route('agent.inquiries.show', $inquiry->id),
                ));
            } catch (\Throwable $e) {
                \Log::warning('NewInquiryNotification mail failed: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('agent.profile', $validated['agent_id'])
            ->with('status', '相談リクエストを送信しました！エージェントからの連絡をお待ちください。');
    }

    public function storeMessage(Request $request, Inquiry $inquiry): JsonResponse|RedirectResponse
    {
        abort_if($inquiry->user_id !== Auth::guard('user')->id(), 403);

        $request->validate(['message' => ['required', 'string', 'max:2000']]);

        $user = Auth::guard('user')->user();

        $msg = InquiryMessage::create([
            'inquiry_id'  => $inquiry->id,
            'sender_type' => 'user',
            'message'     => $request->message,
        ]);

        // エージェントへメール通知
        $inquiry->loadMissing('agent');
        if ($inquiry->agent && $inquiry->agent->email) {
            try {
                Mail::to($inquiry->agent->email)->send(new NewMessageNotification(
                    senderName:      $user->name,
                    messagePreview:  $request->message,
                    inquiryUrl:      route('agent.inquiries.show', $inquiry->id),
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
                'sender_name'  => $user->name,
            ]);
        }

        return redirect()->route('user.inquiries.show', $inquiry)
            ->with('status', 'メッセージを送信しました。');
    }
}
