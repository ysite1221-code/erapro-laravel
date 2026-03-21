<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    // ----------------------------------------------------------------
    // User
    // ----------------------------------------------------------------

    public function showUserRegisterForm(): View
    {
        return view('auth.user_register');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $token = Str::random(64);

        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'email_token' => $token,
            'password'    => Hash::make($validated['password']),
        ]);

        $this->sendUserVerificationMail($user, $token);

        return redirect()->route('user.verify.notice');
    }

    /**
     * メールの認証リンクを処理する（独自トークン方式）
     * SendGrid等のクリックトラッキングで署名URLが壊れる問題を回避
     */
    public function verifyUserEmail(string $token): RedirectResponse
    {
        $user = User::where('email_token', $token)->first();

        if (! $user) {
            abort(403, '認証リンクが無効または期限切れです。再度メール認証を行ってください。');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // 使用済みトークンを無効化
        $user->update(['email_token' => null]);

        Auth::guard('user')->login($user);
        request()->session()->regenerate();

        return redirect()->route('user.dashboard')
            ->with('status', 'メールアドレスの認証が完了しました！');
    }

    public function resendUserVerification(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email', 'exists:users,email']]);

        $user = User::where('email', $request->email)->first();

        if ($user && ! $user->hasVerifiedEmail()) {
            $token = Str::random(64);
            $user->update(['email_token' => $token]);
            $this->sendUserVerificationMail($user, $token);
        }

        return back()->with('status', '認証メールを再送しました。');
    }

    // ----------------------------------------------------------------
    // Agent
    // ----------------------------------------------------------------

    public function showAgentRegisterForm(): View
    {
        return view('auth.agent_register');
    }

    public function storeAgent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', 'unique:agents,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $token = Str::random(64);

        $agent = Agent::create([
            'name'                => $validated['name'],
            'email'               => $validated['email'],
            'email_token'         => $token,
            'password'            => Hash::make($validated['password']),
            'verification_status' => 0,
        ]);

        $this->sendAgentVerificationMail($agent, $token);

        return redirect()->route('agent.verify.notice');
    }

    public function verifyAgentEmail(string $token): RedirectResponse
    {
        $agent = Agent::where('email_token', $token)->first();

        if (! $agent) {
            abort(403, '認証リンクが無効または期限切れです。再度メール認証を行ってください。');
        }

        if (! $agent->hasVerifiedEmail()) {
            $agent->markEmailAsVerified();
        }

        $agent->update(['email_token' => null]);

        Auth::guard('agent')->login($agent);
        request()->session()->regenerate();

        return redirect()->route('agent.dashboard')
            ->with('status', 'メールアドレスの認証が完了しました！次にプロフィールとKYCの設定をお願いします。');
    }

    public function resendAgentVerification(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email', 'exists:agents,email']]);

        $agent = Agent::where('email', $request->email)->first();

        if ($agent && ! $agent->hasVerifiedEmail()) {
            $token = Str::random(64);
            $agent->update(['email_token' => $token]);
            $this->sendAgentVerificationMail($agent, $token);
        }

        return back()->with('status', '認証メールを再送しました。');
    }

    // ----------------------------------------------------------------
    // Helper: メール送信
    // ----------------------------------------------------------------

    private function sendUserVerificationMail(User $user, string $token): void
    {
        $url = route('user.email.verify', ['token' => $token]);

        Mail::raw(
            "ERAPRO へようこそ、{$user->name} さん！\n\n"
            . "以下のリンクをクリックしてメールアドレスを認証してください。\n\n"
            . "{$url}\n\n"
            . "※ 心当たりのない場合は、このメールを無視してください。",
            fn($m) => $m->to($user->email)->subject('【ERAPRO】メールアドレスの認証をお願いします')
        );
    }

    private function sendAgentVerificationMail(Agent $agent, string $token): void
    {
        $url = route('agent.email.verify', ['token' => $token]);

        Mail::raw(
            "ERAPRO 募集人登録を申請いただきありがとうございます、{$agent->name} さん！\n\n"
            . "以下のリンクをクリックしてメールアドレスを認証してください。\n\n"
            . "{$url}\n\n"
            . "認証後、KYC（本人確認）書類の提出をお願いします。",
            fn($m) => $m->to($agent->email)->subject('【ERAPRO募集人】メールアドレスの認証をお願いします')
        );
    }
}
