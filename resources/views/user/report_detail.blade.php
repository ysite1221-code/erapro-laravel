@extends('layouts.app')

@section('title', '通報の処理状況 #' . $report->id . ' - ERAPRO')

@push('styles')
<style>
    body { background: #f5f5f5; }
    .wrap { max-width: 680px; margin: 0 auto; padding: 40px 24px 80px; }
    .back-link { display: inline-block; font-size: 0.85rem; color: #999; margin-bottom: 20px; text-decoration: none; }
    .back-link:hover { color: #004e92; }
    .page-title { font-size: 1.2rem; font-weight: 900; color: #111; margin: 0 0 24px; }

    .card {
        background: #fff; border-radius: 10px; border: 1.5px solid #f0f0f0;
        padding: 24px 28px; margin-bottom: 20px;
    }
    .card-title { font-size: 0.72rem; font-weight: 700; color: #9ca3af;
        letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 4px; }
    .card-value { font-size: 0.92rem; color: #333; margin-bottom: 18px; line-height: 1.7; }
    .card-value:last-child { margin-bottom: 0; }

    .status-wrap { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
    .status-badge {
        display: inline-block; padding: 6px 16px; border-radius: 20px;
        font-size: 0.82rem; font-weight: 700;
    }
    .rs-0 { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .rs-1 { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .rs-2 { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .rs-9 { background: #f9fafb; color: #9ca3af; border: 1px solid #e5e7eb; }

    .info-box {
        background: #f0f4ff; border-radius: 8px; padding: 14px 18px;
        font-size: 0.85rem; color: #374151; border-left: 4px solid #004e92;
        margin-bottom: 20px; line-height: 1.7;
    }

    .detail-body {
        background: #f9fafb; border-radius: 6px; padding: 14px 16px;
        font-size: 0.875rem; color: #374151; white-space: pre-wrap;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
<div class="wrap">

    <a href="{{ route('user.dashboard') }}" class="back-link">← マイページに戻る</a>
    <h1 class="page-title">通報 #{{ $report->id }} の処理状況</h1>

    @php
    $statusLabels = [0 => '未対応', 1 => '対応中', 2 => '対応済み', 9 => '却下'];
    @endphp

    {{-- ステータス --}}
    <div class="status-wrap">
        <span>現在のステータス：</span>
        <span class="status-badge rs-{{ $report->status }}">
            {{ $statusLabels[$report->status] ?? '?' }}
        </span>
    </div>

    {{-- お知らせ --}}
    @if ($report->status === 0)
    <div class="info-box">
        🔍 この通報は現在確認中です。運営が内容を確認次第、対応を開始いたします。
    </div>
    @elseif ($report->status === 1)
    <div class="info-box">
        ⚙️ 現在、運営が対応中です。しばらくお待ちください。
    </div>
    @elseif ($report->status === 2)
    <div class="info-box">
        ✅ この通報への対応が完了しました。ご協力いただきありがとうございました。
    </div>
    @elseif ($report->status === 9)
    <div class="info-box" style="border-left-color:#9ca3af;background:#f9fafb;">
        この通報は規約や事実確認の結果、対応対象外と判断されました。
    </div>
    @endif

    {{-- 通報内容 --}}
    <div class="card">
        <div class="card-title">通報対象のエージェント</div>
        <div class="card-value">{{ $report->agent?->name ?? '削除済みエージェント' }}</div>

        <div class="card-title">通報理由</div>
        <div class="card-value">{{ $report->reason }}</div>

        @if ($report->detail)
        <div class="card-title">詳細内容</div>
        <div class="detail-body">{{ $report->detail }}</div>
        @endif

        <div class="card-title" style="margin-top:16px;">送信日時</div>
        <div class="card-value">{{ $report->created_at->format('Y年n月j日 H:i') }}</div>
    </div>

</div>
@endsection
