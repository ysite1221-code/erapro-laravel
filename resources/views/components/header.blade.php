<header>
    <div class="header-inner">
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ asset('img/logo_blue.png') }}" alt="ERAPRO">
        </a>
        <nav class="header-nav">
            @if (Auth::guard('user')->check())
                <a href="{{ route('user.dashboard') }}" class="btn-mypage">マイページ</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-login" style="border:none;cursor:pointer;">ログアウト</button>
                </form>
            @elseif (Auth::guard('agent')->check())
                <a href="{{ route('agent.dashboard') }}" class="btn-mypage">マイページ</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-login" style="border:none;cursor:pointer;">ログアウト</button>
                </form>
            @elseif (Auth::guard('admin')->check())
                <a href="{{ route('admin.dashboard') }}" class="btn-mypage">管理画面</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-login" style="border:none;cursor:pointer;">ログアウト</button>
                </form>
            @else
                <a href="{{ route('agent.lp') }}" class="header-nav-link">募集人の方はこちら</a>
                <a href="{{ route('login') }}" class="btn-login">ログイン</a>
            @endif
        </nav>
    </div>
</header>
