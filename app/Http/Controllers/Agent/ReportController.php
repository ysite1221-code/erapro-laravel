<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        Report::create([
            'user_id'       => $user->id,
            'agent_id'      => Auth::guard('agent')->id(),
            'reason'        => $request->reason,
            'detail'        => $request->detail,
            'reporter_type' => 'agent',
        ]);

        return redirect()->route('agent.inquiries.index')
            ->with('status', '通報を受け付けました。運営が内容を確認いたします。');
    }
}
