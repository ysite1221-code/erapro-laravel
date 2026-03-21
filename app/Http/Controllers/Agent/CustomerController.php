<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $agent   = Auth::guard('agent')->user();
        $agentId = $agent->id;

        // ── favorites 経由のユーザー ──
        $favRows = Favorite::where('agent_id', $agentId)
            ->whereHas('user', fn($q) => $q->where('life_flg', 0))
            ->with('user')
            ->get()
            ->keyBy('user_id');

        // ── inquiries 経由のユーザー（旧 messages 代替） ──
        $inqRows = Inquiry::where('agent_id', $agentId)
            ->whereHas('user', fn($q) => $q->where('life_flg', 0))
            ->with('user')
            ->selectRaw('user_id, MAX(created_at) as last_inq_at, COUNT(*) as inq_count')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        // ── PHP 側でマージ・デデュープ（旧 customer_list.php と同ロジック） ──
        $customers = [];

        foreach ($favRows as $userId => $fav) {
            $customers[$userId] = [
                'user'       => $fav->user,
                'fav_status' => $fav->status,
                'has_inq'    => false,
                'inq_count'  => 0,
                'contact_at' => $fav->updated_at,
            ];
        }

        foreach ($inqRows as $userId => $inq) {
            if (isset($customers[$userId])) {
                $customers[$userId]['has_inq']   = true;
                $customers[$userId]['inq_count']  = $inq->inq_count;
                // 最新の接触日時を採用
                if ($inq->last_inq_at > $customers[$userId]['contact_at']) {
                    $customers[$userId]['contact_at'] = $inq->last_inq_at;
                }
            } else {
                $customers[$userId] = [
                    'user'       => $inq->user,
                    'fav_status' => 0,
                    'has_inq'    => true,
                    'inq_count'  => $inq->inq_count,
                    'contact_at' => $inq->last_inq_at,
                ];
            }
        }

        // 最終接触日時 降順ソート
        usort($customers, fn($a, $b) => $b['contact_at'] <=> $a['contact_at']);

        return view('agent.customers.index', compact('customers', 'agent'));
    }
}
