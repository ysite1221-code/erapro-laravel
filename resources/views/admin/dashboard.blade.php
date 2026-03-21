@extends('layouts.admin')

@section('title', 'ダッシュボード - ERAPRO Admin')
@section('page-title', 'ダッシュボード')

@push('styles')
<style>
    .kpi-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px; margin-bottom: 32px;
    }
    .kpi-card {
        background: #fff; border-radius: 10px; padding: 20px 22px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06); border: 1px solid #e8eaf0;
    }
    .kpi-label { font-size: 0.75rem; font-weight: 700; color: #9ca3af; letter-spacing: 0.06em; margin-bottom: 8px; }
    .kpi-value { font-size: 2rem; font-weight: 900; color: #1a1f36; line-height: 1; margin-bottom: 4px; }
    .kpi-sub   { font-size: 0.75rem; color: #9ca3af; }

    .section-title {
        font-size: 1rem; font-weight: 900; color: #1a1f36;
        margin: 0 0 16px; display: flex; align-items: center; gap: 8px;
    }
    .badge {
        display: inline-block; padding: 2px 10px; border-radius: 12px;
        font-size: 0.72rem; font-weight: 700;
    }
    .badge-warn   { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .badge-info   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-green  { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .badge-gray   { background: #f9fafb; color: #6b7280; border: 1px solid #e5e7eb; }

    /* KYC pending テーブル */
    .data-card {
        background: #fff; border-radius: 10px; border: 1px solid #e8eaf0;
        overflow: hidden; margin-bottom: 28px;
    }
    .data-card-header {
        padding: 16px 20px; border-bottom: 1px solid #f3f4f6;
        display: flex; align-items: center; justify-content: space-between;
    }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th {
        background: #f9fafb; text-align: left; font-size: 0.72rem; font-weight: 700;
        color: #6b7280; padding: 10px 16px; letter-spacing: 0.06em; border-bottom: 1px solid #e8eaf0;
    }
    .data-table td {
        padding: 13px 16px; font-size: 0.855rem; border-bottom: 1px solid #f3f4f6;
        color: #374151; vertical-align: middle;
    }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: #f9fafb; }
    .data-table a { color: #004e92; text-decoration: none; font-weight: 600; }
    .data-table a:hover { text-decoration: underline; }

    .btn-sm {
        display: inline-block; padding: 5px 14px; border-radius: 5px;
        font-size: 0.78rem; font-weight: 700; cursor: pointer; text-decoration: none;
        transition: all 0.18s; border: none;
    }
    .btn-primary { background: #1a1f36; color: #fff; }
    .btn-primary:hover { background: #374151; color: #fff; }
    .btn-outline { background: #fff; color: #1a1f36; border: 1px solid #d1d5db; }
    .btn-outline:hover { background: #f9fafb; }

    .empty-row td { text-align: center; color: #9ca3af; padding: 32px; font-size: 0.875rem; }

    /* 直近問い合わせ */
    .inquiry-row .status {
        display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.73rem; font-weight: 600;
    }
    .s-1 { background: #fff7ed; color: #c2410c; }
    .s-2 { background: #eff6ff; color: #1d4ed8; }
    .s-3 { background: #faf5ff; color: #7c3aed; }
    .s-4 { background: #f0fdf4; color: #166534; }
    .s-5 { background: #f9fafb; color: #9ca3af; }
</style>
@endpush

@section('content')

@if (session('status'))
<div class="alert-success">✅ {{ session('status') }}</div>
@endif

{{-- KPIグリッド --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-label">AGENTS（有効）</div>
        <div class="kpi-value">{{ $agentTotal }}</div>
        <div class="kpi-sub">承認済み {{ $agentApproved }} 名</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">USERS（有効）</div>
        <div class="kpi-value">{{ $userTotal }}</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">KYC 審査待ち</div>
        <div class="kpi-value" style="{{ $kycPending->count() > 0 ? 'color:#c2410c;' : '' }}">
            {{ $kycPending->count() }}
        </div>
        <div class="kpi-sub">件</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">INQUIRIES（累計）</div>
        <div class="kpi-value">{{ $inquiryTotal }}</div>
    </div>
</div>

{{-- KYC 審査待ち --}}
<div class="data-card" id="kyc">
    <div class="data-card-header">
        <span class="section-title">
            🔐 KYC 審査待ち
            @if ($kycPending->count() > 0)
            <span class="badge badge-warn">{{ $kycPending->count() }} 件</span>
            @endif
        </span>
        <a href="{{ route('admin.agents.index', ['q' => '']) }}" class="btn-sm btn-outline">全エージェントを見る</a>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>氏名</th>
                <th>メール</th>
                <th>提出URL</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kycPending as $agent)
            <tr>
                <td style="color:#9ca3af;">#{{ $agent->id }}</td>
                <td><strong>{{ $agent->name }}</strong></td>
                <td>{{ $agent->email }}</td>
                <td>
                    @if ($agent->affiliation_url)
                    <a href="{{ $agent->affiliation_url }}" target="_blank" rel="noopener"
                       style="font-size:0.78rem;max-width:200px;display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;vertical-align:bottom;">
                        {{ $agent->affiliation_url }}
                    </a>
                    @else
                    <span style="color:#9ca3af;font-size:0.78rem;">未提出</span>
                    @endif
                </td>
                <td style="color:#9ca3af;font-size:0.78rem;">{{ $agent->created_at->format('Y/m/d') }}</td>
                <td>
                    <a href="{{ route('admin.kyc.show', $agent) }}" class="btn-sm btn-primary">審査する</a>
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="6">審査待ちのエージェントはいません ✅</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- 直近の問い合わせ --}}
<div class="data-card">
    <div class="data-card-header">
        <span class="section-title">📋 直近の問い合わせ</span>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>ユーザー</th>
                <th>エージェント</th>
                <th>目的</th>
                <th>ステータス</th>
                <th>日時</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentInquiries as $inq)
            @php
                $statusClasses = [1=>'s-1',2=>'s-2',3=>'s-3',4=>'s-4',5=>'s-5'];
                $statusLabels  = [1=>'送信済',2=>'調整中',3=>'提案中',4=>'完了',5=>'キャンセル'];
            @endphp
            <tr class="inquiry-row">
                <td>{{ $inq->user->name ?? '-' }}</td>
                <td>{{ $inq->agent->name ?? '-' }}</td>
                <td>{{ $inq->purpose }}</td>
                <td>
                    <span class="status {{ $statusClasses[$inq->status] ?? '' }}">
                        {{ $statusLabels[$inq->status] ?? '-' }}
                    </span>
                </td>
                <td style="color:#9ca3af;font-size:0.78rem;">{{ $inq->created_at->format('m/d H:i') }}</td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="5">問い合わせはまだありません</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
