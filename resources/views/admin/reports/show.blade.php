@extends('layouts.admin')

@section('title', '通報詳細 #' . $report->id . ' - ERAPRO Admin')
@section('page-title', '通報詳細')

@push('styles')
<style>
    .back-link {
        display: inline-block; margin-bottom: 20px;
        font-size: 0.855rem; color: #6b7280; text-decoration: none;
    }
    .back-link:hover { color: #1a1f36; }

    .detail-card {
        background: #fff; border-radius: 10px; border: 1px solid #e8eaf0;
        padding: 28px 32px; margin-bottom: 24px;
    }
    .detail-title {
        font-size: 0.72rem; font-weight: 700; color: #9ca3af;
        letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 4px;
    }
    .detail-value {
        font-size: 0.925rem; color: #1a1f36; margin-bottom: 20px;
    }
    .detail-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 0 40px;
    }
    .detail-body {
        background: #f9fafb; border-radius: 6px; padding: 14px 16px;
        font-size: 0.875rem; color: #374151; white-space: pre-wrap;
        line-height: 1.6; margin-bottom: 20px;
    }

    .status-badge {
        display: inline-block; padding: 3px 10px; border-radius: 12px;
        font-size: 0.72rem; font-weight: 700; white-space: nowrap;
    }
    .rs-0 { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .rs-1 { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .rs-2 { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .rs-9 { background: #f9fafb; color: #9ca3af; border: 1px solid #e5e7eb; }

    .reporter-badge {
        display: inline-block; padding: 3px 10px; border-radius: 12px;
        font-size: 0.72rem; font-weight: 700;
    }
    .rtype-user  { background: #eff6ff; color: #1d4ed8; }
    .rtype-agent { background: #fdf4ff; color: #7e22ce; }

    .action-card {
        background: #fff; border-radius: 10px; border: 1px solid #e8eaf0; padding: 24px 32px;
    }
    .action-card h3 { font-size: 0.925rem; font-weight: 700; margin-bottom: 16px; color: #1a1f36; }
    .form-row { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    .form-select {
        padding: 9px 14px; border: 1px solid #d1d5db; border-radius: 6px;
        font-size: 0.875rem; font-family: inherit; background: #fff;
        cursor: pointer; transition: border-color 0.2s; min-width: 180px;
    }
    .form-select:focus { outline: none; border-color: #6366f1; }
    .btn-save {
        padding: 9px 24px; background: #1a1f36; color: #fff; border: none;
        border-radius: 6px; font-size: 0.855rem; font-weight: 600;
        cursor: pointer; font-family: inherit; transition: background 0.18s;
    }
    .btn-save:hover { background: #374151; }
</style>
@endpush

@section('content')

<a href="{{ route('admin.reports.index') }}" class="back-link">← 通報一覧に戻る</a>

@if (session('status'))
<div class="alert-success">✅ {{ session('status') }}</div>
@endif

<div class="detail-card">
    <div class="detail-grid">
        <div>
            <div class="detail-title">通報ID</div>
            <div class="detail-value">#{{ $report->id }}</div>

            <div class="detail-title">通報者タイプ</div>
            <div class="detail-value">
                @if ($report->reporter_type === 'agent')
                <span class="reporter-badge rtype-agent">エージェント → ユーザー</span>
                @else
                <span class="reporter-badge rtype-user">ユーザー → エージェント</span>
                @endif
            </div>

            <div class="detail-title">通報者</div>
            <div class="detail-value">
                @if ($report->reporter_type === 'agent')
                    {{ $report->agent?->name ?? '-' }}（{{ $report->agent?->email ?? '' }}）
                @else
                    {{ $report->user?->name ?? '-' }}（{{ $report->user?->email ?? '' }}）
                @endif
            </div>

            <div class="detail-title">通報対象</div>
            <div class="detail-value">
                @if ($report->reporter_type === 'agent')
                    {{ $report->user?->name ?? '-' }}（{{ $report->user?->email ?? '' }}）
                @else
                    {{ $report->agent?->name ?? '-' }}（{{ $report->agent?->email ?? '' }}）
                @endif
            </div>
        </div>
        <div>
            <div class="detail-title">現在のステータス</div>
            <div class="detail-value">
                <span class="status-badge rs-{{ $report->status }}">
                    {{ $statusLabels[$report->status] ?? '?' }}
                </span>
            </div>

            <div class="detail-title">通報日時</div>
            <div class="detail-value">{{ $report->created_at->format('Y年m月d日 H:i') }}</div>

            <div class="detail-title">最終更新</div>
            <div class="detail-value">{{ $report->updated_at->format('Y年m月d日 H:i') }}</div>
        </div>
    </div>

    <div class="detail-title">通報理由</div>
    <div class="detail-value">{{ $report->reason }}</div>

    @if ($report->detail)
    <div class="detail-title">詳細</div>
    <div class="detail-body">{{ $report->detail }}</div>
    @endif
</div>

<div class="action-card">
    <h3>対応ステータスの更新</h3>
    <form method="POST" action="{{ route('admin.reports.update', $report) }}" class="form-row">
        @csrf @method('PATCH')
        <select name="status" class="form-select">
            @foreach ($statusLabels as $val => $label)
            <option value="{{ $val }}" @selected($report->status == $val)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-save">更新する</button>
    </form>
</div>

@endsection
