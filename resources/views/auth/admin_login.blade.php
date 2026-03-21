<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン - ERAPRO Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Noto Sans JP', sans-serif;
            background: #1a1f36;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            background: #fff; border-radius: 12px; padding: 40px 40px 36px;
            width: 100%; max-width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-logo {
            text-align: center; margin-bottom: 28px;
        }
        .login-logo span {
            font-size: 1.4rem; font-weight: 900; color: #1a1f36; letter-spacing: 0.06em;
        }
        .login-logo small {
            display: block; font-size: 0.7rem; color: #9ca3af; letter-spacing: 0.1em; margin-top: 2px;
        }
        .alert-error {
            background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b;
            border-radius: 6px; padding: 10px 14px; font-size: 0.84rem; margin-bottom: 20px;
        }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 0.82rem; font-weight: 700; color: #374151; margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 6px;
            font-size: 0.9rem; font-family: inherit; color: #111; background: #fafafa;
            transition: border-color 0.2s;
        }
        .form-control:focus { outline: none; border-color: #1a1f36; background: #fff; }
        .form-error { color: #dc2626; font-size: 0.78rem; margin-top: 4px; display: block; }
        .btn-submit {
            width: 100%; padding: 13px; background: #1a1f36; color: #fff;
            border: none; border-radius: 6px; font-size: 0.95rem; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: background 0.18s; margin-top: 4px;
        }
        .btn-submit:hover { background: #374151; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo">
        <span>ERAPRO</span>
        <small>ADMIN PANEL</small>
    </div>

    @if ($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">メールアドレス</label>
            <input type="email" id="email" name="email" class="form-control"
                   value="{{ old('email') }}" required autofocus>
            @error('email')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="password">パスワード</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn-submit">ログイン</button>
    </form>
</div>
</body>
</html>
