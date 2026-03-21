<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>本登録完了 - ERAPRO</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #f4f7f6;
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; padding: 24px;
        }
        .verify-wrap { max-width: 520px; width: 100%; }
        .verify-box {
            background: #fff;
            border-radius: 12px;
            padding: 56px 48px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            text-align: center;
        }
        .verify-icon {
            width: 72px; height: 72px;
            background: #e8f5e9; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin: 0 auto 24px;
        }
        .verify-box h2 {
            font-size: 1.4rem; color: #1a1a1a; margin-bottom: 16px;
        }
        .verify-box p {
            font-size: 0.92rem; color: #555; line-height: 1.8; margin-bottom: 8px;
        }
        .btn-login {
            display: inline-block;
            margin-top: 32px;
            padding: 14px 44px;
            background: #004e92; color: #fff;
            border-radius: 30px;
            font-size: 0.95rem; font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
            box-shadow: 0 4px 10px rgba(0,78,146,0.2);
        }
        .btn-login:hover { background: #003366; color: #fff; }
        .logo-area {
            text-align: center; padding: 20px 0 28px;
            font-size: 1.2rem; font-weight: 800; color: #004e92;
            letter-spacing: 0.04em;
        }
    </style>
</head>
<body>

<div class="verify-wrap">
    <div class="logo-area">ERAPRO</div>

    <div class="verify-box">
        <div class="verify-icon">✅</div>
        <h2>本登録が完了しました</h2>
        {{-- 旧PHP verify.php:$verified_name 相当 --}}
        <p><strong>{{ $userName }} さん</strong>、ようこそERAPROへ！</p>
        <p>メールアドレスの認証が完了しました。<br>下のボタンからログインしてご利用ください。</p>
        <a href="{{ route('login') }}" class="btn-login">ログイン画面へ</a>
    </div>
</div>

</body>
</html>
