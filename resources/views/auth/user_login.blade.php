<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン | ERAPRO</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #f4f6f8;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
        }

        .login-card h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
            text-align: center;
        }

        .login-card .subtitle {
            font-size: .875rem;
            color: #6b7280;
            text-align: center;
            margin-bottom: 32px;
        }

        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: .875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: .95rem;
            transition: border-color .2s;
            outline: none;
        }

        .form-group input:focus { border-color: #4f46e5; }

        .form-group input.is-invalid { border-color: #ef4444; }

        .error-message {
            color: #ef4444;
            font-size: .8rem;
            margin-top: 4px;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            color: #dc2626;
            font-size: .875rem;
            padding: 12px 16px;
            margin-bottom: 20px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            font-size: .875rem;
            color: #4b5563;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: #4f46e5;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background .2s;
        }

        .btn-login:hover { background: #4338ca; }

        .links {
            margin-top: 24px;
            text-align: center;
            font-size: .875rem;
            color: #6b7280;
        }

        .links a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover { text-decoration: underline; }

        .divider {
            margin: 0 8px;
            color: #d1d5db;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h1>ERAPRO</h1>
    <p class="subtitle">一般ユーザーログイン</p>

    {{-- エラーメッセージ --}}
    @if ($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="example@mail.com"
                autocomplete="email"
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                required
            >
            @error('email')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="••••••••"
                autocomplete="current-password"
                required
            >
        </div>

        <label class="remember-row">
            <input type="checkbox" name="remember" value="1">
            ログイン状態を保持する
        </label>

        <button type="submit" class="btn-login">ログイン</button>
    </form>

    <div class="links">
        <a href="{{ route('password.request') }}">パスワードを忘れた方</a>
        <span class="divider">|</span>
        <a href="{{ route('user.register') }}">新規会員登録</a>
    </div>

    <div class="links" style="margin-top:12px;">
        募集人の方は <a href="{{ route('agent.login') }}">こちら</a>
    </div>
</div>

</body>
</html>
