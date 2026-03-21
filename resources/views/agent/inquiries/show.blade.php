@extends('layouts.agent')

@section('title', '問い合わせ詳細 - ERAPRO Agent')

@push('styles')
<style>
    .inq-wrap { max-width:780px; margin:0 auto; padding:32px 28px 80px; }
    .back-link { display:inline-block; font-size:0.85rem; color:#999; margin-bottom:20px; }
    .back-link:hover { color:#004e92; }
    .page-title { font-size:1.3rem; font-weight:900; color:#111; margin:0 0 24px; }

    .alert-success {
        background:#e8f5e9; border:1px solid #c8e6c9; color:#2e7d32;
        border-radius:6px; padding:12px 16px; font-size:0.88rem; margin-bottom:20px;
    }

    .user-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0;
        padding:20px 24px; display:flex; align-items:center; gap:16px; margin-bottom:24px;
    }
    .user-avatar {
        width:52px; height:52px; border-radius:50%; background:#e8f0fe;
        display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0;
    }
    .user-name { font-size:1rem; font-weight:700; color:#111; margin-bottom:3px; }
    .user-meta { font-size:0.8rem; color:#999; }

    .detail-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0;
        padding:28px 32px; margin-bottom:24px;
    }
    .detail-row { display:flex; gap:16px; padding:14px 0; border-bottom:1px solid #f5f5f5; }
    .detail-row:last-child { border-bottom:none; }
    .detail-label { width:160px; flex-shrink:0; font-size:0.82rem; font-weight:700; color:#888; padding-top:2px; }
    .detail-value { flex:1; font-size:0.9rem; color:#333; line-height:1.7; }

    .status-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0; padding:24px 28px;
    }
    .status-card h3 { font-size:0.97rem; font-weight:700; color:#111; margin:0 0 16px; }
    .status-select {
        width:100%; padding:11px 14px; border:1.5px solid #e0e0e0; border-radius:6px;
        font-size:0.9rem; font-family:inherit; color:#333; background:#fafafa;
        transition:border-color 0.2s; margin-bottom:14px; cursor:pointer;
    }
    .status-select:focus { outline:none; border-color:#004e92; background:#fff; }
    .btn-update {
        padding:11px 28px; background:#004e92; color:#fff; border:none;
        border-radius:6px; font-size:0.9rem; font-weight:700; cursor:pointer;
        transition:background 0.2s; letter-spacing:0.03em;
    }
    .btn-update:hover { background:#003a70; }

    .status-badge {
        display:inline-block; padding:5px 14px; border-radius:20px;
        font-size:0.82rem; font-weight:700;
    }
    .status-1 { background:#fff3e0; color:#e65100; }
    .status-2 { background:#e3f2fd; color:#1565c0; }
    .status-3 { background:#f3e5f5; color:#6a1b9a; }
    .status-4 { background:#e8f5e9; color:#2e7d32; }
    .status-5 { background:#fafafa; color:#bbb; border:1px solid #e0e0e0; }
</style>
@endpush

@section('content')
<div class="dashboard">
    <aside class="sidebar">
        @php $agent = Auth::guard('agent')->user(); @endphp
        <img src="{{ $agent->profile_img ? asset('storage/' . $agent->profile_img) : 'https://placehold.co/150x150/e0e0e0/888?text=No+Img' }}"
             class="sidebar-avatar" alt="プロフィール">
        <ul>
            <li><a href="{{ route('agent.dashboard') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">dashboard</span>ダッシュボード
            </a></li>
            <li><a href="{{ route('agent.profile.edit') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">person</span>プロフィール編集
            </a></li>
            <li><a href="{{ route('agent.inquiries.index') }}" class="sidebar-link active">
                <span class="material-icons-outlined sidebar-icon">chat</span>問い合わせ管理
            </a></li>
            <li><a href="{{ route('agent.customers.index') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">people</span>顧客リスト
            </a></li>
        </ul>
        <a href="{{ route('agent.profile', $agent->id) }}" target="_blank" class="sidebar-public-btn">自分の公開ページを見る</a>
    </aside>

    <main class="main-content">
        <div class="inq-wrap" style="padding-left:0;padding-right:0;">

            <a href="{{ route('agent.inquiries.index') }}" class="back-link">← 問い合わせ一覧に戻る</a>
            <h2 class="page-title">問い合わせ詳細</h2>

            @if (session('status'))
            <div class="alert-success">✅ {{ session('status') }}</div>
            @endif

            {{-- ユーザー情報 --}}
            <div class="user-card">
                <div class="user-avatar">👤</div>
                <div>
                    <div class="user-name">{{ $inquiry->user->name ?? '退会済みユーザー' }}</div>
                    <div class="user-meta">
                        送信日時: {{ $inquiry->created_at->format('Y年n月j日 H:i') }}
                        　現在のステータス:
                        <span class="status-badge status-{{ $inquiry->status }}">
                            {{ $statusLabels[$inquiry->status] ?? '不明' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- 相談内容 --}}
            <div class="detail-card">
                <div class="detail-row">
                    <div class="detail-label">相談の目的</div>
                    <div class="detail-value">{{ $inquiry->purpose }}</div>
                </div>
                @if ($inquiry->trigger)
                <div class="detail-row">
                    <div class="detail-label">相談のきっかけ</div>
                    <div class="detail-value">{{ $inquiry->trigger }}</div>
                </div>
                @endif
                @if ($inquiry->preferred_style)
                <div class="detail-row">
                    <div class="detail-label">希望スタイル</div>
                    <div class="detail-value">{{ $inquiry->preferred_style }}</div>
                </div>
                @endif
                @if ($inquiry->note)
                <div class="detail-row">
                    <div class="detail-label">その他・備考</div>
                    <div class="detail-value" style="white-space:pre-wrap;">{{ $inquiry->note }}</div>
                </div>
                @endif
            </div>

            {{-- ステータス変更 --}}
            <div class="status-card">
                <h3>ステータスを更新する</h3>
                <form action="{{ route('agent.inquiries.update_status', $inquiry->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="status-select">
                        @foreach ($statusLabels as $val => $label)
                        <option value="{{ $val }}" {{ $inquiry->status === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-update">ステータスを更新する</button>
                </form>
            </div>

        </div>
    </main>
</div>
@endsection
