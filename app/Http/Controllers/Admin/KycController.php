<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\KycResultNotification;
use App\Models\Agent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class KycController extends Controller
{
    private const STATUS_LABELS = [
        0 => '未提出',
        1 => '審査待ち',
        2 => '承認済み',
        9 => '否認',
    ];

    public function show(Agent $agent): View
    {
        return view('admin.kyc.show', [
            'agent'        => $agent,
            'statusLabels' => self::STATUS_LABELS,
        ]);
    }

    public function update(Request $request, Agent $agent): RedirectResponse
    {
        $request->validate([
            'verification_status' => ['required', 'in:2,9'],
        ]);

        $newStatus = (int) $request->input('verification_status');
        $agent->update(['verification_status' => $newStatus]);

        $label = self::STATUS_LABELS[$newStatus];

        // エージェントへメール通知
        if ($agent->email) {
            try {
                Mail::to($agent->email)->send(new KycResultNotification(
                    agentName:    $agent->name,
                    status:       $newStatus,
                    dashboardUrl: route('agent.dashboard'),
                ));
            } catch (\Throwable $e) {
                \Log::warning('KycResultNotification mail failed: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('admin.kyc.show', $agent)
            ->with('status', "{$agent->name} さんのKYCを「{$label}」に更新しました。");
    }
}
