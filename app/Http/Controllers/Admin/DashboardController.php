<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $kycPending   = Agent::where('verification_status', 1)->with('inquiries')->latest()->get();
        $agentTotal   = Agent::where('life_flg', 0)->count();
        $agentApproved = Agent::where('verification_status', 2)->where('life_flg', 0)->count();
        $userTotal    = User::where('life_flg', 0)->count();
        $inquiryTotal = Inquiry::count();

        // 直近の問い合わせ（5件）
        $recentInquiries = Inquiry::with(['user', 'agent'])->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'kycPending', 'agentTotal', 'agentApproved',
            'userTotal', 'inquiryTotal', 'recentInquiries'
        ));
    }
}
