@extends('layouts.admin')

@section('title', '通報管理 - ERAPRO Admin')
@section('page-title', '通報管理')

@push('styles')
<style>
    .toolbar {
        display: flex; gap: 12px; align-items: center; margin-bottom: 20px; flex-wrap: wrap;
    }
    .filter-form { display: flex; gap: 8px; flex-wrap: wrap; flex: 1; }
    .filter-select {
        padding: 9px 14px; border: 1px solid #d1d5db; border-radius: 6px;
        font-size: 0.875rem; font-family: inherit; background: #fff;
        cursor: pointer; transition: border-color 0.2s;
    }
    .filter-select:focus { outline: none; border-color: #6366f1; }
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

    .btn-sm {
        display: inline-block; padding: 5px 12px; border-radius: 5px;
        font-size: 0.76rem; font-weight: 700; cursor: pointer; border: none;
        font-family: inherit; transition: all 0.18s; text-decoration: none;
    }
    .btn-detail { background: #eff6ff; color: #1d4ed8; }
    .btn-detail:hover { background: #dbeafe; }

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
    <form method="GET" action="{{ route('admin.reports.index') }}" class="filter-form">
        <select name="reporter_type" class="filter-select">
            <option value="">全通報者</option>
            <option value="user"  @selected(request('reporter_type') === 'user')>ユーザーから</option>
            <option value="agent" @selected(request('reporter_type') === 'agent')>エージェントから</option>
        </select>
        <select name="status" class="filter-select">
            <option value="">全ステータス</option>
            @foreach ($statusLabels as $val => $label)
            <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-search">絞り込み</button>
        @if (request('reporter_type') || request('status'))
        <a href="{{ route('admin.reports.index') }}" style="padding:9px 12px;font-size:0.82rem;color:#6b7280;text-decoration:none;">クリア</a>
        @endif
    </form>
    <span class="result-count">全 {{ $reports->total() }} 件</span>
</div>

<div class="data-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>通報者タイプ</th>
                <th>通報者</th>
                <th>対象</th>
                <th>理由</th>
                <th>ステータス</th>
                <th>日時</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $report)
            <tr>
                <td style="color:#9ca3af;font-size:0.76rem;">#{{ $report->id }}</td>
                <td>
                    @if ($report->reporter_type === 'agent')
                    <span class="reporter-badge rtype-agent">エージェント</span>
                    @else
                    <span class="reporter-badge rtype-user">ユーザー</span>
                    @endif
                </td>
                <td>
                    @if ($report->reporter_type === 'agent')
                        {{ $report->agent?->name ?? '-' }}
                    @else
                        {{ $report->user?->name ?? '-' }}
                    @endif
                </td>
                <td>
                    @if ($report->reporter_type === 'agent')
                        {{ $report->user?->name ?? '-' }}
                    @else
                        {{ $report->agent?->name ?? '-' }}
                    @endif
                </td>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ $report->reason }}
                </td>
                <td>
                    <span class="status-badge rs-{{ $report->status }}">
                        {{ $statusLabels[$report->status] ?? '?' }}
                    </span>
                </td>
                <td style="color:#9ca3af;font-size:0.76rem;white-space:nowrap;">
                    {{ $report->created_at->format('Y/m/d H:i') }}
                </td>
                <td>
                    <a href="{{ route('admin.reports.show', $report) }}" class="btn-sm btn-detail">詳細</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;color:#9ca3af;padding:32px;">
                通報はありません。
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($reports->hasPages())
<div class="pagination">{{ $reports->links() }}</div>
@endif

@endsection
