<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        Report::create([
            'user_id'  => Auth::guard('user')->id(),
            'agent_id' => $agent->id,
            'reason'   => $request->reason,
            'detail'   => $request->detail,
        ]);

        return redirect()->route('agent.profile', $agent->id)
            ->with('status', '通報を受け付けました。運営が内容を確認いたします。');
    }
}
