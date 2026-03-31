<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent登録 | ERAPRO</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #f0fdf4; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,.08); padding: 48px 40px; width: 100%; max-width: 440px; }
        .card h1 { font-size: 1.4rem; font-weight: 700; color: #1a1a2e; margin-bottom: 6px; text-align: center; }
        .card .sub { font-size: .875rem; color: #6b7280; text-align: center; margin-bottom: 6px; }
        .notice { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 10px 14px; font-size: .8rem; color: #166534; margin-bottom: 22px; line-height: 1.6; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: .84rem; font-weight: 600; color: #374151; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 11px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: .9rem; outline: none; transition: border-color .2s; }
        .form-group input:focus { border-color: #16a34a; }
        .form-group .hint { font-size: .75rem; color: #9ca3af; margin-top: 4px; }
        .error { color: #ef4444; font-size: .78rem; margin-top: 3px; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; color: #dc2626; font-size: .875rem; padding: 12px 16px; margin-bottom: 18px; }
        .btn { width: 100%; padding: 13px; background: #16a34a; color: #fff; font-size: .95rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: background .2s; margin-top: 4px; }
        .btn:hover { background: #15803d; }
        .links { margin-top: 20px; text-align: center; font-size: .875rem; color: #6b7280; }
        .links a { color: #16a34a; text-decoration: none; font-weight: 500; }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">
    <h1>ERAPRO</h1>
    <p class="sub">Agent登録</p>

    <div class="notice">
        ✅ 登録後にメールアドレスの認証が必要です。<br>
        その後、KYC（本人確認）の審査通過でプロフィールが公開されます。
    </div>

    @if ($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('agent.register.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">氏名</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="山田 花子">
            @error('name')<span class="error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="agent@example.com">
            @error('email')<span class="error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required placeholder="8文字以上">
            <p class="hint">8文字以上で設定してください</p>
            @error('password')<span class="error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">パスワード（確認）</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn">登録してメール認証へ</button>
    </form>

    <div class="links">
        すでにアカウントをお持ちの方は <a href="{{ route('agent.login') }}">ログイン</a>
    </div>
</div>
</body>
</html>
