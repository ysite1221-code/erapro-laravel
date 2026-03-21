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

    <footer style="width:100%;text-align:center;padding:24px 0;font-size:13px;color:#666;background:transparent;border-top:1px solid #eaeaea;margin-top:auto;">
        &copy; {{ date('Y') }} ERAPRO.&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ route('terms') }}" style="color:#666;text-decoration:none;">利用規約</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ route('privacy') }}" style="color:#666;text-decoration:none;">プライバシーポリシー</a>
    </footer>

    @stack('scripts')
</body>
</html>
