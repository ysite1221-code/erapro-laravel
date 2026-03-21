<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERAPRO - 人で選ぶ保険')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>

    <x-header />

    <main>
        @yield('content')
    </main>

    <footer style="text-align:center;padding:24px 16px;font-size:0.8rem;color:#9ca3af;border-top:1px solid #e5e7eb;margin-top:40px;">
        <a href="{{ route('terms') }}" style="color:#6b7280;text-decoration:none;margin:0 12px;">利用規約</a>
        <a href="{{ route('privacy') }}" style="color:#6b7280;text-decoration:none;margin:0 12px;">プライバシーポリシー</a>
    </footer>

    @stack('scripts')
</body>
</html>
