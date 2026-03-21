<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function toggleInterest(Request $request): JsonResponse
    {
        $request->validate(['interest' => ['required', 'string', 'max:50']]);

        /** @var \App\Models\User $user */
        $user     = Auth::guard('user')->user();
        $keyword  = trim($request->input('interest'));
        $current  = array_values(array_filter(array_map('trim', explode(',', $user->interests ?? ''))));

        $key = array_search($keyword, $current, true);
        if ($key !== false) {
            array_splice($current, $key, 1);
            $result = 'removed';
        } else {
            $current[] = $keyword;
            $result = 'added';
        }

        $user->update(['interests' => implode(',', $current)]);

        return response()->json(['result' => $result, 'interests' => $current]);
    }

    public function withdraw(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('user')->user();
        $user->update(['life_flg' => 1]);

        Auth::guard('user')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'ご利用ありがとうございました。退会処理が完了しました。');
    }

    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('user')->user();

        // お気に入り（status=1）
        $favorites = $user->favorites()
            ->with('agent')
            ->where('status', 1)
            ->whereHas('agent', fn($q) => $q->where('life_flg', 0))
            ->latest('updated_at')
            ->get();

        // My Agent（status=2）
        $myAgents = $user->favorites()
            ->with('agent')
            ->where('status', 2)
            ->whereHas('agent', fn($q) => $q->where('life_flg', 0))
            ->latest('updated_at')
            ->get();

        // 未読は inquiries の未対応ステータスで代用（messages廃止のため件数=0で固定）
        $unreadCount = 0;

        // 関心事パース
        $userInterests = !empty($user->interests)
            ? array_values(array_filter(array_map('trim', explode(',', $user->interests))))
            : [];

        // おすすめAgent（関心事タグ一致 × 診断スコア相性順）
        $recommended = collect();
        if (!empty($userInterests)) {
            $query = Agent::where('life_flg', 0)
                ->where('verification_status', 2)
                ->where(function ($q) use ($userInterests) {
                    foreach ($userInterests as $kw) {
                        $q->orWhere('tags', 'like', "%{$kw}%");
                    }
                });
            if ($user->diagnosis_score !== null) {
                $score = $user->diagnosis_score;
                $query->orderByRaw('ABS(COALESCE(diagnosis_score, 50) - ?) ASC', [$score]);
            }
            $recommended = $query->limit(5)->get();
        }

        // 近隣Agent（エリア一致 × 診断スコア相性順）
        $nearbyAgents = collect();
        if (!empty($user->area)) {
            $query = Agent::where('life_flg', 0)
                ->where('verification_status', 2)
                ->where('area', $user->area);
            if ($user->diagnosis_score !== null) {
                $score = $user->diagnosis_score;
                $query->orderByRaw('ABS(COALESCE(diagnosis_score, 50) - ?) ASC', [$score]);
            }
            $nearbyAgents = $query->limit(6)->get();
        }

        $typeLabels = [
            'logic_seeker'   => ['論理・データ重視タイプ', '📊'],
            'empathy_seeker' => ['バランス重視タイプ',     '🤝'],
            'support_seeker' => ['感情・寄り添い重視タイプ', '💛'],
        ];

        $allInterests = ['結婚', '妊娠・出産', '住宅購入', '教育資金', '資産運用', '老後の備え', '自動車購入'];

        return view('user.dashboard', compact(
            'user', 'favorites', 'myAgents', 'unreadCount',
            'userInterests', 'recommended', 'nearbyAgents',
            'typeLabels', 'allInterests'
        ));
    }
}
