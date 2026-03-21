<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Favorite;
use App\Models\Inquiry;
use App\Models\ProfileView;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AgentSearchController extends Controller
{
    // 都道府県リスト（共通定数）
    private const PREFECTURES = [
        '北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県',
        '茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県',
        '新潟県','富山県','石川県','福井県','山梨県','長野県',
        '岐阜県','静岡県','愛知県','三重県',
        '滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県',
        '鳥取県','島根県','岡山県','広島県','山口県',
        '徳島県','香川県','愛媛県','高知県',
        '福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県',
    ];

    private const TYPE_INFO = [
        'logic_seeker'   => ['label' => '論理・データ重視タイプ', 'emoji' => '📊', 'color' => '#004e92'],
        'empathy_seeker' => ['label' => 'バランス重視タイプ',     'emoji' => '🤝', 'color' => '#2e7d32'],
        'support_seeker' => ['label' => '感情・寄り添い重視タイプ','emoji' => '💛', 'color' => '#e65100'],
    ];

    public function index(Request $request): View
    {
        $area     = $request->input('area', '');
        $tag      = $request->input('tag', '');
        $diagType = $request->input('type', '');

        // ログイン中ユーザーの診断情報
        /** @var \App\Models\User|null $user */
        $user           = Auth::guard('user')->user();
        $userScore      = $user?->diagnosis_score;
        $userInterests  = $user && !empty($user->interests)
            ? array_values(array_filter(array_map('trim', explode(',', $user->interests))))
            : [];

        // クエリ構築
        $query = Agent::query()
            ->where('life_flg', 0)
            ->where('verification_status', 2)
            ->whereNotNull('title')->where('title', '!=', '')
            ->whereNotNull('story')->where('story', '!=', '')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        if (!empty($area)) {
            $query->where('area', $area);
        }
        if (!empty($tag)) {
            $query->where(function ($q) use ($tag) {
                $q->where('tags',       'like', "%{$tag}%")
                  ->orWhere('title',    'like', "%{$tag}%")
                  ->orWhere('story',    'like', "%{$tag}%")
                  ->orWhere('area_detail', 'like', "%{$tag}%");
            });
        }

        // ORDER BY: 関心事合致 → スコア相性 → id降順
        if (!empty($userInterests)) {
            $likeConditions = collect($userInterests)
                ->map(fn($kw) => "tags LIKE " . \DB::getPdo()->quote("%{$kw}%"))
                ->implode(' OR ');
            $query->orderByRaw("CASE WHEN ({$likeConditions}) THEN 0 ELSE 1 END ASC");
        }
        if ($userScore !== null) {
            $query->orderByRaw('ABS(COALESCE(diagnosis_score, 50) - ?) ASC', [$userScore]);
        }
        $query->orderByDesc('id');

        $agents      = $query->get();
        $matchedType = self::TYPE_INFO[$diagType] ?? null;

        return view('user.search', [
            'agents'        => $agents,
            'area'          => $area,
            'tag'           => $tag,
            'diagType'      => $diagType,
            'matchedType'   => $matchedType,
            'prefectures'   => self::PREFECTURES,
            'userScore'     => $userScore,
            'userInterests' => $userInterests,
        ]);
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $agent = Agent::where('life_flg', 0)
            ->where('verification_status', 2)
            ->find($id);

        if (!$agent) {
            return redirect()->route('search');
        }

        // 閲覧履歴を記録（自分自身のプロフィールは除外）
        $viewerIsOwner = Auth::guard('agent')->check()
            && Auth::guard('agent')->id() === $agent->id;

        if (!$viewerIsOwner) {
            /** @var \App\Models\User|null $loginUser */
            $loginUser = Auth::guard('user')->user();
            ProfileView::create([
                'agent_id'  => $agent->id,
                'user_id'   => $loginUser?->id,
                'viewer_ip' => $request->ip(),
                'viewed_at' => now(),
            ]);
        }

        // クチコミ取得
        $reviews = $agent->reviews()->with('user')->latest('updated_at')->get();
        $reviewCount = $reviews->count();
        $avgRating   = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : 0;

        // お気に入り状態（ログイン中Userのみ）
        $favStatus    = 0;
        $userReviewed = false;
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::guard('user')->user();

        // 既存の相談があるかチェック（旧PHP profile.php の $has_thread 相当）
        $existingInquiry = null;

        if ($authUser) {
            $fav = Favorite::where('user_id', $authUser->id)
                ->where('agent_id', $agent->id)
                ->first();
            $favStatus = $fav ? $fav->status : 0;

            $userReviewed = $agent->reviews()
                ->where('user_id', $authUser->id)
                ->exists();

            $existingInquiry = Inquiry::where('user_id', $authUser->id)
                ->where('agent_id', $agent->id)
                ->latest()
                ->first();
        }

        $reviewPosted = $request->query('review') === '1';
        $tags         = array_filter(array_map('trim', explode(',', $agent->tags ?? '')));
        $areaDisplay  = $agent->area ?: '未設定';
        if (!empty($agent->area_detail)) {
            $areaDisplay .= '　' . mb_substr($agent->area_detail, 0, 25);
        }

        return view('user.profile', compact(
            'agent', 'reviews', 'reviewCount', 'avgRating',
            'favStatus', 'userReviewed', 'reviewPosted',
            'tags', 'areaDisplay', 'authUser', 'existingInquiry'
        ));
    }
}
