<header>
    <div class="header-inner">
        @if (request()->routeIs('agent.lp'))
            {{-- Agent LP 専用ヘッダー --}}
            <a href="{{ route('agent.lp') }}" class="logo">
                <img src="{{ asset('img/logo_white.png') }}" alt="ERAPRO for Agent"
                     style="height:28px;max-width:140px;object-fit:contain;">
            </a>
            <nav class="header-nav" style="display:flex;align-items:center;gap:20px;">
                <a href="{{ url('/') }}"
                   style="color:rgba(255,255,255,0.72);text-decoration:none;font-size:0.855rem;letter-spacing:0.02em;transition:color 0.2s;"
                   onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.72)'">一般の方はこちら</a>
                <a href="{{ route('agent.login') }}"
                   style="display:inline-block;padding:8px 22px;background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.45);border-radius:5px;font-size:0.855rem;font-weight:600;text-decoration:none;letter-spacing:0.03em;transition:all 0.2s;"
                   onmouseover="this.style.background='rgba(255,255,255,0.12)';this.style.borderColor='rgba(255,255,255,0.8)'" onmouseout="this.style.background='transparent';this.style.borderColor='rgba(255,255,255,0.45)'">ログイン</a>
            </nav>
        @else
            {{-- 通常ヘッダー --}}
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
        @endif
    </div>
</header>
