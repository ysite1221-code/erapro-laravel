<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    // ----------------------------------------------------------------
    // User パスワードリセット
    // ----------------------------------------------------------------

    public function showForgotForm(): View
    {
        return view('auth.forgot_password', ['guard' => 'user']);
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'パスワードリセットメールを送信しました。メールをご確認ください。')
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, string $token): View
    {
        return view('auth.reset_password', [
            'token' => $token,
            'email' => $request->query('email', ''),
            'guard' => 'user',
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'パスワードをリセットしました。新しいパスワードでログインしてください。')
            : back()->withErrors(['email' => __($status)]);
    }

    // ----------------------------------------------------------------
    // Agent パスワードリセット
    // ----------------------------------------------------------------

    public function showAgentForgotForm(): View
    {
        return view('auth.forgot_password', ['guard' => 'agent']);
    }

    public function sendAgentResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::broker('agents')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'パスワードリセットメールを送信しました。')
            : back()->withErrors(['email' => __($status)]);
    }

    public function showAgentResetForm(Request $request, string $token): View
    {
        return view('auth.reset_password', [
            'token' => $token,
            'email' => $request->query('email', ''),
            'guard' => 'agent',
        ]);
    }

    public function resetAgentPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $status = Password::broker('agents')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($agent, $password) {
                $agent->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('agent.login')->with('status', 'パスワードをリセットしました。')
            : back()->withErrors(['email' => __($status)]);
    }
}
