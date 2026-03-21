<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function withdraw(Request $request): RedirectResponse
    {
        /** @var \App\Models\Agent $agent */
        $agent = Auth::guard('agent')->user();
        $agent->update(['life_flg' => 1]);

        Auth::guard('agent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'ご利用ありがとうございました。退会処理が完了しました。');
    }

    public function index(): View
    {
        /** @var \App\Models\Agent $agent */
        $agent = Auth::guard('agent')->user();

        // KPI: 今月の閲覧数
        $monthlyViews = $agent->profileViews()
            ->where('viewed_at', '>=', Carbon::now()->startOfMonth())
            ->count();

        // KPI: 本日の閲覧数
        $todayViews = $agent->profileViews()
            ->whereDate('viewed_at', Carbon::today())
            ->count();

        // KPI: お気に入り登録数（status=1）
        $favCount = $agent->favorites()->where('status', 1)->count();

        // KPI: My Agent 登録数（status=2）
        $myAgentCount = $agent->favorites()->where('status', 2)->count();

        // KPI: クチコミ平均・件数
        $reviewStats = $agent->reviews()
            ->selectRaw('ROUND(AVG(rating), 1) as avg_rating, COUNT(*) as review_count')
            ->first();

        // 直近クチコミ5件（userリレーション付き）
        $recentReviews = $agent->reviews()
            ->with('user')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        // 最近の閲覧履歴10件
        $recentViews = $agent->profileViews()
            ->latest('viewed_at')
            ->limit(10)
            ->get();

        // プロフィール完成度
        $completionItems = [
            'profile_img' => 'プロフィール写真',
            'title'       => 'キャッチコピー',
            'area'        => '活動エリア',
            'tags'        => 'タグ',
            'story'       => 'My Story',
            'philosophy'  => 'Philosophy',
        ];
        $filled = collect($completionItems)->keys()
            ->filter(fn($field) => !empty($agent->$field))
            ->count();
        $completionPct = (int) round($filled / count($completionItems) * 100);
        $isPublic = !empty($agent->title) && !empty($agent->story);

        return view('agent.dashboard', compact(
            'agent', 'monthlyViews', 'todayViews', 'favCount', 'myAgentCount',
            'reviewStats', 'recentReviews', 'recentViews',
            'completionItems', 'completionPct', 'isPublic'
        ));
    }
}
