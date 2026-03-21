<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>管理者アカウント発行のお知らせ</title>
<style>
  body { margin:0; padding:0; background:#f0f2f5; font-family:'Helvetica Neue',Arial,sans-serif; }
  .wrapper { max-width:560px; margin:32px auto; background:#fff;
             border-radius:10px; overflow:hidden;
             box-shadow:0 4px 20px rgba(0,0,0,0.08); }
  .header { background:#1a1f36; padding:28px 36px; }
  .header-logo { font-size:1.3rem; font-weight:900; color:#fff; letter-spacing:0.06em; }
  .header-logo small { display:block; font-size:0.7rem; color:rgba(255,255,255,0.45);
                        margin-top:3px; letter-spacing:0.1em; }
  .body { padding:36px 36px 28px; }
  h2 { font-size:1.1rem; color:#1a1f36; margin:0 0 18px; }
  p  { font-size:0.9rem; color:#374151; line-height:1.8; margin:0 0 14px; }
  .cred-box { background:#f8faff; border:1.5px solid #c7d2fe;
              border-radius:8px; padding:20px 24px; margin:22px 0; }
  .cred-row { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
  .cred-row:last-child { margin-bottom:0; }
  .cred-label { font-size:0.75rem; font-weight:700; color:#6366f1;
                min-width:110px; letter-spacing:0.04em; }
  .cred-value { font-size:0.92rem; color:#1a1f36; font-family:monospace;
                background:#fff; border:1px solid #e5e7eb;
                padding:4px 12px; border-radius:5px; word-break:break-all; }
  .btn-wrap  { text-align:center; margin:28px 0 20px; }
  .btn-login { display:inline-block; padding:13px 44px;
               background:#1a1f36; color:#fff; border-radius:30px;
               font-size:0.92rem; font-weight:700; text-decoration:none;
               letter-spacing:0.04em; }
  .notice { background:#fffbeb; border:1px solid #fde68a; border-radius:6px;
            padding:12px 16px; font-size:0.82rem; color:#92400e; line-height:1.7; }
  .footer { border-top:1px solid #f0f0f0; padding:20px 36px;
            font-size:0.78rem; color:#9ca3af; text-align:center; line-height:1.8; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <div class="header-logo">
      ERAPRO
      <small>ADMIN PANEL</small>
    </div>
  </div>

  <div class="body">
    <h2>管理者アカウントが発行されました</h2>

    <p>
      {{ $adminName }} 様<br>
      ERAPROの管理者アカウントが発行されました。<br>
      以下の情報でログインしてください。
    </p>

    <div class="cred-box">
      <div class="cred-row">
        <span class="cred-label">ログインID</span>
        <span class="cred-value">{{ $adminEmail }}</span>
      </div>
      <div class="cred-row">
        <span class="cred-label">初期パスワード</span>
        <span class="cred-value">{{ $plainPassword }}</span>
      </div>
    </div>

    <div class="btn-wrap">
      <a href="{{ $loginUrl }}" class="btn-login">管理画面にログインする</a>
    </div>

    <div class="notice">
      ⚠️ セキュリティのため、初回ログイン後は速やかにパスワードを変更してください。<br>
      このメールに心当たりがない場合は、運営事務局までご連絡ください。
    </div>
  </div>

  <div class="footer">
    {{ config('app.name') }} 運営事務局<br>
    このメールは自動送信されています。返信はご遠慮ください。
  </div>
</div>
</body>
</html>
