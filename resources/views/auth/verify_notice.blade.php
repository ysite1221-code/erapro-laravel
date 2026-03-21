<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証 | ERAPRO</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #f4f6f8; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,.08); padding: 48px 40px; width: 100%; max-width: 440px; text-align: center; }
        .icon { font-size: 3rem; margin-bottom: 16px; display: block; }
        h1 { font-size: 1.3rem; font-weight: 700; color: #1a1a2e; margin-bottom: 10px; }
        p { font-size: .9rem; color: #6b7280; line-height: 1.8; margin-bottom: 6px; }
        .email-hint { font-size: .8rem; color: #9ca3af; margin: 16px 0 24px; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 6px; padding: 10px 14px; font-size: .84rem; margin-bottom: 20px; }
        .resend-form { margin-top: 28px; }
        .resend-form label { display: block; font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: 6px; text-align: left; }
        .resend-form input { width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: .9rem; outline: none; margin-bottom: 10px; }
        .resend-form input:focus { border-color: #4f46e5; }
        .btn { width: 100%; padding: 12px; background: #4f46e5; color: #fff; font-size: .9rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: background .2s; }
        .btn:hover { background: #4338ca; }
        .back-link { display: inline-block; margin-top: 20px; font-size: .84rem; color: #9ca3af; text-decoration: none; }
        .back-link:hover { color: #4f46e5; }
    </style>
</head>
<body>
<div class="card">
    <span class="icon">📧</span>
    <h1>メールをご確認ください</h1>
    <p>ご登録のメールアドレスに認証リンクを送信しました。</p>
    <p class="email-hint">メールが届かない場合は迷惑メールフォルダをご確認いただくか、<br>以下から再送してください。</p>

    @if (session('status'))
    <div class="alert-success">✅ {{ session('status') }}</div>
    @endif

    <div class="resend-form">
        <form method="POST" action="{{ $resendRoute }}">
            @csrf
            <label for="email">メールアドレスを入力して再送</label>
            <input type="email" id="email" name="email" placeholder="example@mail.com" required>
            <button type="submit" class="btn">認証メールを再送する</button>
        </form>
    </div>

    <a href="{{ $loginRoute }}" class="back-link">← ログインページに戻る</a>
</div>
</body>
</html>
