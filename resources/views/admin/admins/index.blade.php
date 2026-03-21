@extends('layouts.admin')

@section('title', '管理者管理 - ERAPRO Admin')
@section('page-title', '管理者管理')

@push('styles')
<style>
    .toolbar {
        display: flex; justify-content: flex-end; margin-bottom: 20px;
    }
    .btn-create {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 22px; background: #1a1f36; color: #fff; border: none;
        border-radius: 6px; font-size: 0.855rem; font-weight: 600;
        cursor: pointer; font-family: inherit; text-decoration: none;
        transition: background 0.18s;
    }
    .btn-create:hover { background: #374151; color: #fff; }

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

    .badge {
        display: inline-block; padding: 2px 10px; border-radius: 10px;
        font-size: 0.72rem; font-weight: 700;
    }
    .badge-active   { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .badge-inactive { background: #f9fafb; color: #9ca3af; border: 1px solid #e5e7eb; }
    .badge-super    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

    .you-badge {
        display: inline-block; margin-left: 8px; padding: 1px 8px;
        background: #fef3c7; color: #92400e; border: 1px solid #fde68a;
        border-radius: 10px; font-size: 0.68rem; font-weight: 700; vertical-align: middle;
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
    <a href="{{ route('admin.admins.create') }}" class="btn-create">
        <span class="material-icons-outlined" style="font-size:1rem;">add</span>
        新しい管理者を追加
    </a>
</div>

<div class="data-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>権限</th>
                <th>ステータス</th>
                <th>作成日時</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($admins as $adm)
            <tr>
                <td style="color:#9ca3af;font-size:0.76rem;">#{{ $adm->id }}</td>
                <td>
                    {{ $adm->name }}
                    @auth('admin')
                    @if (Auth::guard('admin')->id() === $adm->id)
                    <span class="you-badge">あなた</span>
                    @endif
                    @endauth
                </td>
                <td style="font-size:0.82rem;">{{ $adm->email }}</td>
                <td>
                    @if ($adm->kanri_flg)
                    <span class="badge badge-super">管理者</span>
                    @else
                    <span class="badge badge-inactive">一般</span>
                    @endif
                </td>
                <td>
                    @if ($adm->life_flg)
                    <span class="badge badge-inactive">無効</span>
                    @else
                    <span class="badge badge-active">有効</span>
                    @endif
                </td>
                <td style="color:#9ca3af;font-size:0.76rem;white-space:nowrap;">
                    {{ $adm->created_at->format('Y/m/d H:i') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#9ca3af;padding:32px;">
                    管理者が登録されていません。
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($admins->hasPages())
<div class="pagination">{{ $admins->links() }}</div>
@endif

@endsection
