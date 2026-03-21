<header>
    <div class="header-inner">
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ asset('img/logo_blue.png') }}" alt="ERAPRO">
        </a>
        <nav class="header-nav">
            @if (Auth::guard('user')->check())
                <a href="#" class="btn-mypage">マイページ</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-login" style="border:none;cursor:pointer;">ログアウト</button>
                </form>
            @else
                <a href="#" class="header-nav-link">募集人の方はこちら</a>
                <a href="{{ route('login') }}" class="btn-login">ログイン</a>
            @endif
        </nav>
    </div>
</header>
