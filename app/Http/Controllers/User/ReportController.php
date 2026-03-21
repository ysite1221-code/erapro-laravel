<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\ReportAdminNotification;
use App\Models\Agent;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function create(int $agentId): View
    {
        $agent = Agent::findOrFail($agentId);
        return view('user.report_form', compact('agent'));
    }

    public function store(Request $request, int $agentId): RedirectResponse
    {
        $agent = Agent::findOrFail($agentId);

        $request->validate([
            'reason' => ['required', 'string', 'max:100'],
            'detail' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::guard('user')->user();

        Report::create([
            'user_id'  => $user->id,
            'agent_id' => $agent->id,
            'reason'   => $request->reason,
            'detail'   => $request->detail,
        ]);

        // 旧PHP report_act.php: 運営宛メール通知
        $adminEmail = config('mail.from.address');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new ReportAdminNotification(
                    reporterType:    'user',
                    reporterName:    $user->name,
                    reporterEmail:   $user->email,
                    targetType:      'agent',
                    targetName:      $agent->name,
                    targetEmail:     $agent->email,
                    reason:          $request->reason,
                    adminReportsUrl: route('admin.reports.index'),
                ));
            } catch (\Throwable $e) {
                \Log::warning('ReportAdminNotification mail failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('agent.profile', $agent->id)
            ->with('status', '通報を受け付けました。運営が内容を確認いたします。');
    }
}
