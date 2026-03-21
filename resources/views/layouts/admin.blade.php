<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERAPRO Admin')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Noto Sans JP', sans-serif; background: #f0f2f5; color: #333; display: flex; min-height: 100vh; }

        /* サイドバー */
        .admin-sidebar {
            width: 220px; flex-shrink: 0;
            background: #1a1f36; min-height: 100vh;
            display: flex; flex-direction: column;
            position: sticky; top: 0; height: 100vh;
        }
        .admin-sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .admin-sidebar-logo span {
            font-size: 1.1rem; font-weight: 900; color: #fff; letter-spacing: 0.05em;
        }
        .admin-sidebar-logo small {
            display: block; font-size: 0.68rem; color: rgba(255,255,255,0.4);
            margin-top: 2px; letter-spacing: 0.08em;
        }
        .admin-nav { flex: 1; padding: 12px 0; }
        .admin-nav-section {
            font-size: 0.65rem; color: rgba(255,255,255,0.3);
            padding: 14px 20px 6px; letter-spacing: 0.1em; font-weight: 700;
        }
        .admin-nav a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px; font-size: 0.85rem; color: rgba(255,255,255,0.65);
            text-decoration: none; transition: all 0.18s; border-left: 3px solid transparent;
        }
        .admin-nav a:hover { background: rgba(255,255,255,0.06); color: #fff; }
        .admin-nav a.active { background: rgba(99,179,237,0.12); color: #63b3ed; border-left-color: #63b3ed; }
        .admin-nav .material-icons-outlined { font-size: 1.1rem; }
        .admin-sidebar-footer {
            padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.08);
        }
        .admin-sidebar-footer span { font-size: 0.78rem; color: rgba(255,255,255,0.4); display: block; margin-bottom: 8px; }
        .admin-logout-btn {
            display: block; width: 100%; padding: 8px; background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 4px; font-size: 0.8rem; cursor: pointer; font-family: inherit;
            transition: all 0.18s; text-align: center;
        }
        .admin-logout-btn:hover { background: rgba(239,68,68,0.2); color: #fc8181; border-color: rgba(239,68,68,0.3); }

        /* メインエリア */
        .admin-main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .admin-topbar {
            background: #fff; border-bottom: 1px solid #e8eaf0;
            padding: 14px 28px; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 10;
        }
        .admin-topbar-title { font-size: 1.05rem; font-weight: 700; color: #1a1f36; }
        .admin-topbar-admin { font-size: 0.82rem; color: #888; }
        .admin-content { flex: 1; padding: 28px 32px 60px; }

        /* 共通コンポーネント */
        .alert-success {
            background: #f0fdf4; border: 1px solid #86efac; color: #166534;
            border-radius: 6px; padding: 12px 16px; font-size: 0.875rem; margin-bottom: 20px;
        }
        .alert-error {
            background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b;
            border-radius: 6px; padding: 12px 16px; font-size: 0.875rem; margin-bottom: 20px;
        }
        @stack('styles-inline')
    </style>
    @stack('styles')
</head>
<body>

<aside class="admin-sidebar">
    <div class="admin-sidebar-logo">
        <span>ERAPRO</span>
        <small>ADMIN PANEL</small>
    </div>
    <nav class="admin-nav">
        <div class="admin-nav-section">OVERVIEW</div>
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="material-icons-outlined">dashboard</span>ダッシュボード
        </a>

        <div class="admin-nav-section">管理</div>
        <a href="{{ route('admin.agents.index') }}"
           class="{{ request()->routeIs('admin.agents.*') ? 'active' : '' }}">
            <span class="material-icons-outlined">people</span>エージェント
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="material-icons-outlined">person</span>ユーザー
        </a>

        <div class="admin-nav-section">審査</div>
        <a href="{{ route('admin.dashboard') }}#kyc"
           class="{{ request()->routeIs('admin.kyc.*') ? 'active' : '' }}">
            <span class="material-icons-outlined">verified_user</span>KYC審査
        </a>
        @can('view-sensitive-data')
        <a href="{{ route('admin.reports.index') }}"
           class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <span class="material-icons-outlined">flag</span>通報管理
        </a>
        @endcan

        @can('view-sensitive-data')
        <div class="admin-nav-section">設定</div>
        <a href="{{ route('admin.admins.index') }}"
           class="{{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
            <span class="material-icons-outlined">admin_panel_settings</span>管理者管理
        </a>
        @endcan
    </nav>
    <div class="admin-sidebar-footer">
        @auth('admin')
        <span>{{ Auth::guard('admin')->user()->name }}</span>
        @endauth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="admin-logout-btn">ログアウト</button>
        </form>
    </div>
</aside>

<div class="admin-main" style="display:flex; flex-direction:column; flex:1; min-width:0; min-height:100vh;">
    <div class="admin-topbar">
        <div class="admin-topbar-title">@yield('page-title', 'Admin Panel')</div>
        <div class="admin-topbar-admin">
            @auth('admin')管理者: {{ Auth::guard('admin')->user()->name }}@endauth
        </div>
    </div>
    <div style="flex:1; padding:28px 32px 60px;">
        @yield('content')
    </div>
    <style>
        .admin-footer-custom {
            width: 100%;
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #e5e7eb;
            margin-top: auto;
        }
        .admin-footer-links {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 32px;
            margin-bottom: 12px;
        }
        .admin-footer-links a {
            color: #4b5563;
            text-decoration: none;
            font-size: 14px;
            white-space: nowrap;
        }
        .admin-footer-links a:hover {
            color: #111827;
            text-decoration: underline;
        }
        .admin-footer-copy {
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
        }
    </style>
    <footer class="admin-footer-custom">
        <div class="admin-footer-links">
            <a href="{{ route('terms') }}">利用規約</a>
            <a href="{{ route('privacy') }}">プライバシーポリシー</a>
            <a href="{{ route('agent.lp') }}">保険募集人の掲載登録について</a>
        </div>
        <div class="admin-footer-copy">
            &copy; {{ date('Y') }} ERAPRO.
        </div>
    </footer>
</div>

@stack('scripts')
</body>
</html>
