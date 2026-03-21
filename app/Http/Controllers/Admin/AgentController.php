<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgentController extends Controller
{
    private const VERIFICATION_LABELS = [
        0 => '未提出',
        1 => '審査待ち',
        2 => '承認済み',
        9 => '否認',
    ];

    public function index(Request $request): View
    {
        $q = $request->input('q', '');

        $query = Agent::query()->withCount(['reviews', 'favorites', 'inquiries']);

        if (!empty($q)) {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $agents = $query->latest()->paginate(30)->withQueryString();

        return view('admin.agents.index', [
            'agents'             => $agents,
            'q'                  => $q,
            'verificationLabels' => self::VERIFICATION_LABELS,
        ]);
    }

    public function toggleStatus(Request $request, Agent $agent): RedirectResponse
    {
        $isSuspending = ! $agent->life_flg; // 現在有効 → これから停止

        if ($isSuspending) {
            $reason = $request->input('suspension_reason', '');
            $agent->update([
                'life_flg'          => 1,
                'suspension_reason' => $reason ?: null,
            ]);
            $msg = "{$agent->name} のアカウントを停止しました。";
        } else {
            $agent->update([
                'life_flg'          => 0,
                'suspension_reason' => null,
            ]);
            $msg = "{$agent->name} のアカウントを有効化しました。";
        }

        return redirect()->back()->with('status', $msg);
    }
}
