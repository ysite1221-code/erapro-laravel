<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function create(int $agentId): View|RedirectResponse
    {
        $agent = Agent::where('life_flg', 0)
            ->where('verification_status', 2)
            ->find($agentId);

        if (!$agent) {
            return redirect()->route('search');
        }

        $userId = Auth::guard('user')->id();

        // 既存のクチコミを取得（編集用）
        $existing = Review::where('user_id', $userId)
            ->where('agent_id', $agentId)
            ->first();

        return view('user.review_form', compact('agent', 'existing'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'agent_id' => ['required', 'integer', 'min:1', 'exists:agents,id'],
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'comment'  => ['nullable', 'string', 'max:1000'],
        ]);

        $userId = Auth::guard('user')->id();

        Review::updateOrCreate(
            ['user_id' => $userId, 'agent_id' => $validated['agent_id']],
            [
                'rating'  => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return redirect()
            ->route('agent.profile', $validated['agent_id'])
            ->with('status', 'クチコミを投稿しました。ありがとうございます！');
    }
}
