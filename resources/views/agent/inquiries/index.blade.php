@extends('layouts.agent')

@section('title', '問い合わせ管理 - ERAPRO Agent')

@push('styles')
<style>
    .inq-wrap { max-width:900px; margin:0 auto; padding:32px 28px 80px; }
    .page-title { font-size:1.4rem; font-weight:900; color:#111; margin:0 0 6px; letter-spacing:-0.02em; }
    .page-sub   { font-size:0.85rem; color:#999; margin:0 0 28px; }

    .filter-row { display:flex; gap:8px; margin-bottom:24px; flex-wrap:wrap; }
    .filter-btn {
        padding:7px 18px; border-radius:20px; font-size:0.82rem; font-weight:600;
        border:1.5px solid #e0e0e0; background:#fff; color:#888; cursor:pointer;
        transition:all 0.18s; text-decoration:none;
    }
    .filter-btn.active, .filter-btn:hover { border-color:#004e92; background:#004e92; color:#fff; }
    .filter-btn.all.active { background:#111; border-color:#111; }

    .inq-list { display:flex; flex-direction:column; gap:12px; }
    .inq-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0;
        padding:20px 24px; display:flex; align-items:center; gap:20px;
        text-decoration:none; color:inherit; transition:box-shadow 0.2s, border-color 0.2s;
    }
    .inq-card:hover { box-shadow:0 4px 16px rgba(0,0,0,0.08); border-color:#d0d8ee; }

    .inq-user-avatar {
        width:46px; height:46px; border-radius:50%; background:#e8f0fe;
        display:flex; align-items:center; justify-content:center;
        font-size:1.2rem; flex-shrink:0;
    }
    .inq-card-body { flex:1; min-width:0; }
    .inq-user-name { font-size:0.97rem; font-weight:700; color:#111; margin-bottom:3px; }
    .inq-purpose   { font-size:0.85rem; color:#555; margin-bottom:4px; }
    .inq-meta      { font-size:0.75rem; color:#bbb; }

    .inq-status { flex-shrink:0; }
    .status-badge {
        display:inline-block; padding:5px 14px; border-radius:20px;
        font-size:0.78rem; font-weight:700; white-space:nowrap;
    }
    .status-1 { background:#fff3e0; color:#e65100; }
    .status-2 { background:#e3f2fd; color:#1565c0; }
    .status-3 { background:#f3e5f5; color:#6a1b9a; }
    .status-4 { background:#e8f5e9; color:#2e7d32; }
    .status-5 { background:#fafafa; color:#bbb; }

    .empty-state { text-align:center; padding:80px 20px; color:#bbb; }
    .empty-state .empty-icon { font-size:3rem; margin-bottom:16px; display:block; }

    .alert-success {
        background:#e8f5e9; border:1px solid #c8e6c9; color:#2e7d32;
        border-radius:6px; padding:12px 16px; font-size:0.88rem; margin-bottom:20px;
    }
</style>
@endpush

@section('content')
<div class="dashboard">
    @php $agent = Auth::guard('agent')->user(); @endphp
    <x-agent-sidebar :agent="$agent" active="inquiries" />

    <main class="main-content">
        <div class="inq-wrap" style="padding-left:0;padding-right:0;">

            <h2 class="page-title">問い合わせ管理</h2>
            <p class="page-sub">ユーザーから受け取った相談リクエストを管理します</p>

            @if (session('status'))
            <div class="alert-success">✅ {{ session('status') }}</div>
            @endif

            @php
                $statusCounts = $inquiries->groupBy('status')->map->count();
                $currentFilter = request('status', '');
                $filtered = $currentFilter !== ''
                    ? $inquiries->where('status', (int)$currentFilter)
                    : $inquiries;
            @endphp

            <div class="filter-row">
                <a href="{{ route('agent.inquiries.index') }}"
                   class="filter-btn all {{ $currentFilter === '' ? 'active' : '' }}">
                    すべて ({{ $inquiries->count() }})
                </a>
                @foreach ($statusLabels as $val => $label)
                <a href="{{ route('agent.inquiries.index', ['status' => $val]) }}"
                   class="filter-btn {{ $currentFilter == $val ? 'active' : '' }}">
                    {{ $label }} ({{ $statusCounts->get($val, 0) }})
                </a>
                @endforeach
            </div>

            @if ($filtered->isNotEmpty())
            <div class="inq-list">
                @foreach ($filtered as $inq)
                <a href="{{ route('agent.inquiries.show', $inq->id) }}" class="inq-card">
                    <div class="inq-user-avatar">👤</div>
                    <div class="inq-card-body">
                        <div class="inq-user-name">{{ $inq->user->name ?? '退会済みユーザー' }}</div>
                        <div class="inq-purpose">📋 {{ $inq->purpose }}</div>
                        <div class="inq-meta">
                            {{ $inq->updated_at->format('Y年n月j日') }}
                            @if ($inq->trigger) ・ きっかけ: {{ $inq->trigger }} @endif
                        </div>
                    </div>
                    <div class="inq-status">
                        <span class="status-badge status-{{ $inq->status }}">
                            {{ $statusLabels[$inq->status] ?? '不明' }}
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <span class="empty-icon">📭</span>
                <p>{{ $currentFilter !== '' ? 'このステータスの問い合わせはありません。' : 'まだ問い合わせがありません。' }}</p>
            </div>
            @endif

        </div>
    </main>
</div>
@endsection
