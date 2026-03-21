<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    // ----------------------------------------------------------------
    // User
    // ----------------------------------------------------------------

    public function showUserLoginForm(): View
    {
        return view('auth.user_login');
    }

    public function userLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $guard = Auth::guard('user');

        if (! $guard->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'メールアドレスまたはパスワードが正しくありません。']);
        }

        $user = $guard->user();

        // 退会済みチェック
        if ($user->life_flg == 1) {
            $guard->logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'このアカウントは退会済みです。']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('user.dashboard'));
    }

    // ----------------------------------------------------------------
    // Agent
    // ----------------------------------------------------------------

    public function showAgentLoginForm(): View
    {
        return view('auth.agent_login');
    }

    public function agentLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $guard = Auth::guard('agent');

        if (! $guard->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'メールアドレスまたはパスワードが正しくありません。']);
        }

        $agent = $guard->user();

        // 停止・退会チェック
        if ($agent->life_flg == 1) {
            $guard->logout();
            $msg = $agent->suspension_reason
                ? "現在アカウントが停止されています。理由：{$agent->suspension_reason}"
                : 'このアカウントは退会済みです。';
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => $msg]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('agent.dashboard'));
    }

    // ----------------------------------------------------------------
    // Admin
    // ----------------------------------------------------------------

    public function showAdminLoginForm(): View
    {
        return view('auth.admin_login');
    }

    public function adminLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $guard = Auth::guard('admin');

        if (! $guard->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'メールアドレスまたはパスワードが正しくありません。']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    // ----------------------------------------------------------------
    // 共通ログアウト
    // ----------------------------------------------------------------

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('user')->logout();
        Auth::guard('agent')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
