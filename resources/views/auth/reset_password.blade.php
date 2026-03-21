<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新しいパスワードの設定 | ERAPRO</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #f4f6f8; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,.08); padding: 48px 40px; width: 100%; max-width: 420px; }
        h1 { font-size: 1.3rem; font-weight: 700; color: #1a1a2e; margin-bottom: 8px; text-align: center; }
        .sub { font-size: .875rem; color: #6b7280; text-align: center; margin-bottom: 28px; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; border-radius: 8px; padding: 12px 16px; font-size: .875rem; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: .84rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-group input { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: .95rem; outline: none; transition: border-color .2s; }
        .form-group input:focus { border-color: #4f46e5; }
        .hint { font-size: .75rem; color: #9ca3af; margin-top: 4px; }
        .error { color: #ef4444; font-size: .78rem; margin-top: 4px; }
        .btn { width: 100%; padding: 13px; background: #4f46e5; color: #fff; font-size: .95rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: background .2s; }
        .btn:hover { background: #4338ca; }
    </style>
</head>
<body>
<div class="card">
    <h1>🔑 新しいパスワードを設定</h1>
    <p class="sub">8文字以上の新しいパスワードを入力してください。</p>

    @if ($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    @php
        $action = $guard === 'agent' ? route('agent.password.update') : route('password.update');
    @endphp

    <form method="POST" action="{{ $action }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required>
            @error('email')<span class="error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label for="password">新しいパスワード</label>
            <input type="password" id="password" name="password" required placeholder="8文字以上">
            <p class="hint">8文字以上で設定してください</p>
            @error('password')<span class="error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">新しいパスワード（確認）</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn">パスワードを変更する</button>
    </form>
</div>
</body>
</html>
