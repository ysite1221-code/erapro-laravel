<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\ProfileView;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ActivityReportController extends Controller
{
    public function index(): View
    {
        $agent   = Auth::guard('agent')->user();
        $agentId = $agent->id;

        // ── KPI: 今月の閲覧数 ──
        $monthlyViews = ProfileView::where('agent_id', $agentId)
            ->whereYear('viewed_at', now()->year)
            ->whereMonth('viewed_at', now()->month)
            ->count();

        // ── KPI: 本日の閲覧数 ──
        $todayViews = ProfileView::where('agent_id', $agentId)
            ->whereDate('viewed_at', today())
            ->count();

        // ── KPI: お気に入り登録数（status=1）──
        $favCount = Favorite::where('agent_id', $agentId)->where('status', 1)->count();

        // ── KPI: My Agent 登録数（status=2）──
        $myAgentCount = Favorite::where('agent_id', $agentId)->where('status', 2)->count();

        // ── 過去30日間の閲覧数（日別）──
        $viewRows = ProfileView::where('agent_id', $agentId)
            ->where('viewed_at', '>=', now()->subDays(29)->startOfDay())
            ->select(DB::raw('DATE(viewed_at) as view_date'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('view_date')
            ->orderBy('view_date')
            ->pluck('cnt', 'view_date')
            ->toArray();

        $chartLabels = [];
        $chartData   = [];
        for ($i = 29; $i >= 0; $i--) {
            $d             = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('m/d');
            $chartData[]   = $viewRows[$d] ?? 0;
        }

        // ── 先月 vs 今月 お気に入り新規登録 ──
        $thisMonthStart  = now()->startOfMonth();
        $lastMonthStart  = now()->subMonth()->startOfMonth();
        $lastMonthEnd    = now()->subMonth()->endOfMonth();

        $thisFav   = Favorite::where('agent_id', $agentId)->where('status', 1)->where('updated_at', '>=', $thisMonthStart)->count();
        $lastFav   = Favorite::where('agent_id', $agentId)->where('status', 1)->whereBetween('updated_at', [$lastMonthStart, $lastMonthEnd])->count();
        $thisMyAgent = Favorite::where('agent_id', $agentId)->where('status', 2)->where('updated_at', '>=', $thisMonthStart)->count();
        $lastMyAgent = Favorite::where('agent_id', $agentId)->where('status', 2)->whereBetween('updated_at', [$lastMonthStart, $lastMonthEnd])->count();

        $favDiff     = $thisFav - $lastFav;
        $myAgentDiff = $thisMyAgent - $lastMyAgent;

        // ── 問い合わせ統計（旧 messages 代替）──
        $totalInquiries  = \App\Models\Inquiry::where('agent_id', $agentId)->count();
        $newInquiries    = \App\Models\Inquiry::where('agent_id', $agentId)->where('status', 1)->count();
        $activeInquiries = \App\Models\Inquiry::where('agent_id', $agentId)->whereIn('status', [2, 3])->count();

        return view('agent.report', compact(
            'agent',
            'monthlyViews', 'todayViews', 'favCount', 'myAgentCount',
            'chartLabels', 'chartData',
            'thisFav', 'lastFav', 'favDiff',
            'thisMyAgent', 'lastMyAgent', 'myAgentDiff',
            'totalInquiries', 'newInquiries', 'activeInquiries'
        ));
    }
}
