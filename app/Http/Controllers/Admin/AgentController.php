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

    public function toggleStatus(Agent $agent): RedirectResponse
    {
        $agent->update(['life_flg' => $agent->life_flg ? 0 : 1]);

        $msg = $agent->life_flg
            ? "{$agent->name} のアカウントを停止しました。"
            : "{$agent->name} のアカウントを有効化しました。";

        return redirect()
            ->route('admin.agents.index')
            ->with('status', $msg);
    }
}
