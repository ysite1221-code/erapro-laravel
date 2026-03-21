<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'agent_id' => ['required', 'integer', 'min:1', 'exists:agents,id'],
            'action'   => ['required', 'in:favorite,my_agent'],
        ]);

        $userId   = Auth::guard('user')->id();
        $agentId  = (int) $request->input('agent_id');
        $action   = $request->input('action');
        $newStatus = $action === 'my_agent' ? 2 : 1;

        $existing = Favorite::where('user_id', $userId)
            ->where('agent_id', $agentId)
            ->first();

        if ($existing) {
            if ($existing->status === $newStatus) {
                // 同じ → トグルOFF（削除）
                $existing->delete();
                return response()->json(['result' => 'removed', 'action' => $action]);
            }
            // 別ステータスに変更（お気に入り ↔ My Agent）
            $existing->update(['status' => $newStatus]);
            return response()->json(['result' => 'updated', 'action' => $action, 'status' => $newStatus]);
        }

        // 新規追加
        Favorite::create([
            'user_id'  => $userId,
            'agent_id' => $agentId,
            'status'   => $newStatus,
        ]);
        return response()->json(['result' => 'added', 'action' => $action, 'status' => $newStatus]);
    }
}
