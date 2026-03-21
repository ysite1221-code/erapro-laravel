@extends('layouts.admin')

@section('title', 'ユーザー管理 - ERAPRO Admin')
@section('page-title', 'ユーザー管理')

@push('styles')
<style>
    .toolbar {
        display: flex; gap: 12px; align-items: center; margin-bottom: 20px; flex-wrap: wrap;
    }
    .search-form { display: flex; gap: 8px; flex: 1; min-width: 240px; }
    .search-input {
        flex: 1; padding: 9px 14px; border: 1px solid #d1d5db; border-radius: 6px;
        font-size: 0.875rem; font-family: inherit; background: #fff;
        transition: border-color 0.2s;
    }
    .search-input:focus { outline: none; border-color: #6366f1; }
    .btn-search {
        padding: 9px 18px; background: #1a1f36; color: #fff; border: none;
        border-radius: 6px; font-size: 0.855rem; font-weight: 600;
        cursor: pointer; font-family: inherit; transition: background 0.18s;
    }
    .btn-search:hover { background: #374151; }
    .result-count { font-size: 0.82rem; color: #6b7280; margin-left: auto; }

    .data-card { background: #fff; border-radius: 10px; border: 1px solid #e8eaf0; overflow: hidden; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th {
        background: #f9fafb; text-align: left; font-size: 0.72rem; font-weight: 700;
        color: #6b7280; padding: 10px 16px; letter-spacing: 0.06em; border-bottom: 1px solid #e8eaf0;
        white-space: nowrap;
    }
    .data-table td {
        padding: 12px 16px; font-size: 0.855rem; border-bottom: 1px solid #f3f4f6;
        color: #374151; vertical-align: middle;
    }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: #f9fafb; }

    .user-cell { display: flex; align-items: center; gap: 10px; }
    .user-avatar {
        width: 32px; height: 32px; border-radius: 50%; background: #e8f0fe;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; flex-shrink: 0; color: #3b4fc8;
    }
    .user-name  { font-weight: 700; color: #1a1f36; font-size: 0.875rem; }
    .user-email { font-size: 0.76rem; color: #9ca3af; }

    .status-badge {
        display: inline-block; padding: 3px 10px; border-radius: 12px;
        font-size: 0.72rem; font-weight: 700;
    }
    .life-active   { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .life-inactive { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    .btn-sm {
        display: inline-block; padding: 5px 12px; border-radius: 5px;
        font-size: 0.76rem; font-weight: 700; cursor: pointer; border: none;
        font-family: inherit; transition: all 0.18s;
    }
    .btn-stop   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .btn-stop:hover { background: #fee2e2; }
    .btn-resume { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .btn-resume:hover { background: #dcfce7; }

    .diag-chip {
        display: inline-block; padding: 2px 8px; border-radius: 10px;
        font-size: 0.7rem; background: #eff6ff; color: #1d4ed8; font-weight: 600;
    }

    .pagination { display: flex; gap: 6px; margin-top: 16px; align-items: center; justify-content: center; }
    .pagination a, .pagination span {
        padding: 6px 12px; border: 1px solid #e5e7eb; border-radius: 5px;
        font-size: 0.8rem; color: #374151; text-decoration: none; transition: all 0.18s;
    }
    .pagination a:hover { background: #f9fafb; }
    .pagination .active span { background: #1a1f36; color: #fff; border-color: #1a1f36; }
    .pagination .disabled span { color: #d1d5db; }
</style>
@endpush

@section('content')

@if (session('status'))
<div class="alert-success">✅ {{ session('status') }}</div>
@endif

<div class="toolbar">
    <form method="GET" action="{{ route('admin.users.index') }}" class="search-form">
        <input type="text" name="q" class="search-input"
               placeholder="氏名・メールで検索…" value="{{ $q }}">
        <button type="submit" class="btn-search">検索</button>
        @if ($q)
        <a href="{{ route('admin.users.index') }}" style="padding:9px 12px;font-size:0.82rem;color:#6b7280;text-decoration:none;">クリア</a>
        @endif
    </form>
    <span class="result-count">全 {{ $users->total() }} 件</span>
</div>

<div class="data-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ユーザー</th>
                <th>エリア</th>
                <th>診断タイプ</th>
                <th>状態</th>
                <th>お気に入り</th>
                <th>問い合わせ</th>
                <th>レビュー</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            @php
                $diagLabels = [
                    'logic_seeker'   => '📊 論理',
                    'empathy_seeker' => '🤝 バランス',
                    'support_seeker' => '💛 感情',
                ];
            @endphp
            <tr>
                <td style="color:#9ca3af;font-size:0.76rem;">#{{ $user->id }}</td>
                <td>
                    <div class="user-cell">
                        <div class="user-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
                        <div>
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-email">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>{{ $user->area ?: '-' }}</td>
                <td>
                    @if ($user->diagnosis_type)
                    <span class="diag-chip">{{ $diagLabels[$user->diagnosis_type] ?? $user->diagnosis_type }}</span>
                    @else
                    <span style="color:#d1d5db;font-size:0.78rem;">未診断</span>
                    @endif
                </td>
                <td>
                    <span class="status-badge {{ $user->life_flg ? 'life-inactive' : 'life-active' }}">
                        {{ $user->life_flg ? '停止中' : '有効' }}
                    </span>
                </td>
                <td style="text-align:center;">{{ $user->favorites_count }}</td>
                <td style="text-align:center;">{{ $user->inquiries_count }}</td>
                <td style="text-align:center;">{{ $user->reviews_count }}</td>
                <td style="color:#9ca3af;font-size:0.76rem;white-space:nowrap;">{{ $user->created_at->format('Y/m/d') }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.users.toggle_status', $user) }}"
                          onsubmit="return confirm('{{ $user->life_flg ? '有効化' : '停止' }}しますか？');">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-sm {{ $user->life_flg ? 'btn-resume' : 'btn-stop' }}">
                            {{ $user->life_flg ? '有効化' : '停止' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="10" style="text-align:center;color:#9ca3af;padding:32px;">
                {{ $q ? "「{$q}」に一致するユーザーは見つかりませんでした。" : 'ユーザーがいません。' }}
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($users->hasPages())
<div class="pagination">{{ $users->links() }}</div>
@endif

@endsection
