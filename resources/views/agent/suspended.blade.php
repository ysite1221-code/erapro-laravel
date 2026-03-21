<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント停止のお知らせ - ERAPRO</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #f4f7f6;
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; padding: 24px;
        }
        .wrap { max-width: 540px; width: 100%; }
        .logo { text-align: center; padding: 0 0 24px;
            font-size: 1.2rem; font-weight: 800; color: #004e92; letter-spacing: 0.04em; }
        .card {
            background: #fff; border-radius: 12px;
            padding: 48px 44px; box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            text-align: center;
        }
        .icon {
            width: 72px; height: 72px; background: #fef2f2; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin: 0 auto 24px;
        }
        h2 { font-size: 1.3rem; color: #1a1a1a; margin-bottom: 16px; }
        .reason-box {
            background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px;
            padding: 16px 20px; margin: 20px 0; text-align: left;
        }
        .reason-label { font-size: 0.75rem; font-weight: 700; color: #c2410c;
            margin-bottom: 6px; letter-spacing: 0.04em; }
        .reason-text { font-size: 0.92rem; color: #374151; line-height: 1.7; }
        p { font-size: 0.9rem; color: #555; line-height: 1.8; margin-bottom: 8px; }
        .contact { font-size: 0.82rem; color: #9ca3af; margin-top: 16px; }
        .btn-logout {
            display: inline-block; margin-top: 28px; padding: 12px 36px;
            background: #6b7280; color: #fff; border-radius: 30px;
            font-size: 0.9rem; font-weight: bold; text-decoration: none;
            transition: background 0.3s;
        }
        .btn-logout:hover { background: #374151; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="logo">ERAPRO</div>
    <div class="card">
        <div class="icon">🚫</div>
        <h2>アカウントが停止されています</h2>
        <p>{{ $agent->name ?? '' }} さんのアカウントは、<br>現在運営により停止されています。</p>

        @if ($agent && $agent->suspension_reason)
        <div class="reason-box">
            <div class="reason-label">停止理由</div>
            <div class="reason-text">{{ $agent->suspension_reason }}</div>
        </div>
        @endif

        <p>停止解除についてご不明な点は、<br>運営事務局までお問い合わせください。</p>
        <p class="contact">お問い合わせ先：support@erapro.jp</p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">ログアウトする</button>
        </form>
    </div>
</div>
</body>
</html>
