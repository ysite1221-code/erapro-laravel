<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
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
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $this->sendUserVerificationMail($user);

        return redirect()->route('user.verify.notice');
    }

    public function verifyUserEmail(Request $request, int $id): RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            abort(403, '認証リンクが無効または期限切れです。');
        }

        $user = User::findOrFail($id);

        if ($request->query('hash') !== sha1($user->email)) {
            abort(403, '認証リンクが正しくありません。');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::guard('user')->login($user);
        $request->session()->regenerate();

        return redirect()->route('user.dashboard')
            ->with('status', 'メールアドレスの認証が完了しました！');
    }

    public function resendUserVerification(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email', 'exists:users,email']]);

        $user = User::where('email', $request->email)->first();

        if ($user && ! $user->hasVerifiedEmail()) {
            $this->sendUserVerificationMail($user);
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

        $agent = Agent::create([
            'name'                => $validated['name'],
            'email'               => $validated['email'],
            'password'            => Hash::make($validated['password']),
            'verification_status' => 0,
        ]);

        $this->sendAgentVerificationMail($agent);

        return redirect()->route('agent.verify.notice');
    }

    public function verifyAgentEmail(Request $request, int $id): RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            abort(403, '認証リンクが無効または期限切れです。');
        }

        $agent = Agent::findOrFail($id);

        if ($request->query('hash') !== sha1($agent->email)) {
            abort(403, '認証リンクが正しくありません。');
        }

        if (! $agent->hasVerifiedEmail()) {
            $agent->markEmailAsVerified();
        }

        Auth::guard('agent')->login($agent);
        $request->session()->regenerate();

        return redirect()->route('agent.dashboard')
            ->with('status', 'メールアドレスの認証が完了しました！次にプロフィールとKYCの設定をお願いします。');
    }

    public function resendAgentVerification(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email', 'exists:agents,email']]);

        $agent = Agent::where('email', $request->email)->first();

        if ($agent && ! $agent->hasVerifiedEmail()) {
            $this->sendAgentVerificationMail($agent);
        }

        return back()->with('status', '認証メールを再送しました。');
    }

    // ----------------------------------------------------------------
    // Helper: メール送信
    // ----------------------------------------------------------------

    private function sendUserVerificationMail(User $user): void
    {
        $url = URL::temporarySignedRoute(
            'user.email.verify',
            now()->addHours(24),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        Mail::raw(
            "ERAPRO へようこそ、{$user->name} さん！\n\n"
            . "以下のリンクをクリックしてメールアドレスを認証してください。\n\n"
            . "{$url}\n\n"
            . "※ このリンクは24時間有効です。\n"
            . "※ 心当たりのない場合は、このメールを無視してください。",
            fn($m) => $m->to($user->email)->subject('【ERAPRO】メールアドレスの認証をお願いします')
        );
    }

    private function sendAgentVerificationMail(Agent $agent): void
    {
        $url = URL::temporarySignedRoute(
            'agent.email.verify',
            now()->addHours(24),
            ['id' => $agent->id, 'hash' => sha1($agent->email)]
        );

        Mail::raw(
            "ERAPRO 募集人登録を申請いただきありがとうございます、{$agent->name} さん！\n\n"
            . "以下のリンクをクリックしてメールアドレスを認証してください。\n\n"
            . "{$url}\n\n"
            . "認証後、KYC（本人確認）書類の提出をお願いします。\n"
            . "※ このリンクは24時間有効です。",
            fn($m) => $m->to($agent->email)->subject('【ERAPRO募集人】メールアドレスの認証をお願いします')
        );
    }
}
