@extends('layouts.app')

@section('title', '相談の送信履歴 - ERAPRO')

@push('styles')
<style>
    body { background:#f5f5f5; }
    .inq-wrap { max-width:760px; margin:0 auto; padding:40px 28px 100px; }
    .page-title { font-size:1.5rem; font-weight:900; color:#111; margin:0 0 6px; letter-spacing:-0.02em; }
    .page-sub   { font-size:0.88rem; color:#999; margin:0 0 32px; }

    .inq-list { display:flex; flex-direction:column; gap:14px; }
    .inq-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0;
        padding:20px 24px; display:flex; gap:20px; align-items:center;
        text-decoration:none; color:inherit; transition:box-shadow 0.2s, border-color 0.2s;
    }
    .inq-card:hover { box-shadow:0 4px 16px rgba(0,0,0,0.08); border-color:#d0d8ee; }

    .inq-agent-img {
        width:52px; height:52px; border-radius:50%; object-fit:cover;
        background:#eee; flex-shrink:0;
    }
    .inq-body { flex:1; min-width:0; }
    .inq-agent-name { font-size:0.97rem; font-weight:700; color:#111; margin-bottom:3px; }
    .inq-purpose    { font-size:0.85rem; color:#555; margin-bottom:4px; }
    .inq-meta       { font-size:0.75rem; color:#bbb; }

    .status-badge {
        display:inline-block; padding:5px 14px; border-radius:20px;
        font-size:0.78rem; font-weight:700; white-space:nowrap; flex-shrink:0;
    }
    .status-1 { background:#fff3e0; color:#e65100; }
    .status-2 { background:#e3f2fd; color:#1565c0; }
    .status-3 { background:#f3e5f5; color:#6a1b9a; }
    .status-4 { background:#e8f5e9; color:#2e7d32; }
    .status-5 { background:#fafafa; color:#bbb; border:1px solid #e0e0e0; }

    .empty-state { text-align:center; padding:80px 20px; color:#bbb; }
    .empty-state .empty-icon { font-size:3rem; display:block; margin-bottom:16px; }
    .empty-state p { font-size:0.95rem; margin-bottom:20px; }
    .empty-state a { display:inline-block; padding:12px 28px; background:#004e92; color:#fff; border-radius:8px; font-weight:bold; }

    .back-link { display:inline-block; margin-bottom:20px; font-size:0.85rem; color:#999; }
    .back-link:hover { color:#004e92; }

    .new-badge {
        display:inline-block; padding:3px 10px; background:#ef4444; color:#fff;
        border-radius:20px; font-size:0.7rem; font-weight:800;
        margin-left:8px; vertical-align:middle; letter-spacing:0.04em;
        animation: pulse-badge 1.4s infinite;
    }
    @keyframes pulse-badge {
        0%, 100% { opacity:1; }
        50% { opacity:0.6; }
    }
</style>
@endpush

@section('content')
<div class="inq-wrap">

    <a href="{{ route('user.dashboard') }}" class="back-link">← マイページに戻る</a>
    <h1 class="page-title">📋 相談の送信履歴・進捗</h1>
    <p class="page-sub">あなたが送信した相談リクエストの一覧と、エージェントからの対応状況を確認できます。</p>

    @if ($inquiries->isNotEmpty())
    <div class="inq-list">
        @foreach ($inquiries as $inq)
        @php
            $img = $inq->agent->profile_img
                ? asset('storage/' . $inq->agent->profile_img)
                : 'https://picsum.photos/seed/agent' . $inq->agent_id . '/100/100';
        @endphp
        @php $hasNewMsg = $inq->latestMessage?->sender_type === 'agent'; @endphp
        <a href="{{ route('user.inquiries.show', $inq->id) }}"
           class="inq-card" style="{{ $hasNewMsg ? 'border-color:#fca5a5;' : '' }}">
            <img src="{{ $img }}" class="inq-agent-img" alt="{{ $inq->agent->name ?? '' }}">
            <div class="inq-body">
                <div class="inq-agent-name">
                    {{ $inq->agent->name ?? '削除済みエージェント' }}
                    @if ($hasNewMsg)
                        <span class="new-badge">NEW</span>
                    @endif
                </div>
                <div class="inq-purpose">📋 {{ $inq->purpose }}</div>
                <div class="inq-meta">
                    送信日: {{ $inq->created_at->format('Y年n月j日') }}
                    @if ($hasNewMsg)
                        ・ 💬 {{ Str::limit($inq->latestMessage->message, 30) }}
                    @endif
                </div>
            </div>
            <span class="status-badge status-{{ $inq->status }}">
                {{ $statusLabels[$inq->status] ?? '不明' }}
            </span>
        </a>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <span class="empty-icon">📭</span>
        <p>まだ相談リクエストを送ったことがありません。<br>気になるプロに相談してみましょう！</p>
        <a href="{{ route('search') }}">保険のプロを探す</a>
    </div>
    @endif

</div>
@endsection
