<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAgentIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\Agent|null $agent */
        $agent = Auth::guard('agent')->user();

        if ($agent && $agent->life_flg == 1 && $agent->suspension_reason !== null) {
            // 管理者による停止 → 停止通知ページへ（ログアウトしない）
            if (! $request->routeIs('agent.suspended')) {
                return redirect()->route('agent.suspended');
            }
        }

        return $next($request);
    }
}
