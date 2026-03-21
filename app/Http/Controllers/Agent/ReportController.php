<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Mail\ReportAdminNotification;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function create(int $userId): View
    {
        $user = User::findOrFail($userId);
        return view('agent.report_form', compact('user'));
    }

    public function store(Request $request, int $userId): RedirectResponse
    {
        $user = User::findOrFail($userId);

        $request->validate([
            'reason' => ['required', 'string', 'max:100'],
            'detail' => ['nullable', 'string', 'max:1000'],
        ]);

        $agent = Auth::guard('agent')->user();

        Report::create([
            'user_id'       => $user->id,
            'agent_id'      => $agent->id,
            'reason'        => $request->reason,
            'detail'        => $request->detail,
            'reporter_type' => 'agent',
        ]);

        // 旧PHP report_act.php: 運営宛メール通知
        $adminEmail = config('mail.from.address');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new ReportAdminNotification(
                    reporterType:    'agent',
                    reporterName:    $agent->name,
                    reporterEmail:   $agent->email,
                    targetType:      'user',
                    targetName:      $user->name,
                    targetEmail:     $user->email,
                    reason:          $request->reason,
                    adminReportsUrl: route('admin.reports.index'),
                ));
            } catch (\Throwable $e) {
                \Log::warning('ReportAdminNotification mail failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('agent.inquiries.index')
            ->with('status', '通報を受け付けました。運営が内容を確認いたします。');
    }
}
