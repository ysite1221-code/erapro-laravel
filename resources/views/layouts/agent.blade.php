<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERAPRO Agent')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>

<header class="agent-header">
    <a href="{{ route('agent.dashboard') }}" class="agent-header-logo">
        <img src="{{ asset('img/logo_white.png') }}" alt="ERAPRO Agent"
             onerror="this.style.display='none'; this.nextSibling.style.display='inline'">
        <span style="display:none; font-weight:800; color:#fff; font-size:1rem; letter-spacing:0.05em;">ERAPRO</span>
    </a>
    <div class="agent-header-right">
        @auth('agent')
            <span class="agent-header-user">{{ Auth::guard('agent')->user()->name }}</span>
        @endauth
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="agent-header-logout" style="background:none;border:none;cursor:pointer;padding:0;">
                ログアウト
            </button>
        </form>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer style="text-align:center;padding:20px 16px;font-size:0.78rem;color:#9ca3af;border-top:1px solid #e5e7eb;margin-top:32px;">
    <a href="{{ route('terms') }}" style="color:#6b7280;text-decoration:none;margin:0 10px;">利用規約</a>
    <a href="{{ route('privacy') }}" style="color:#6b7280;text-decoration:none;margin:0 10px;">プライバシーポリシー</a>
</footer>

@stack('scripts')
</body>
</html>
