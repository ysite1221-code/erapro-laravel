@extends('layouts.agent')

@section('title', '顧客リスト - ERAPRO Agent')

@push('styles')
<style>
    .list-header { display:flex; align-items:center; gap:12px; margin-bottom:16px; }
    .list-header h2 { margin:0; font-size:1.3rem; }
    .list-count { background:#004e92; color:#fff; font-size:0.8rem; font-weight:700; padding:3px 12px; border-radius:20px; }
    .search-bar { margin-bottom:20px; }
    .search-bar input {
        width:100%; max-width:340px; padding:10px 14px; border:1px solid #d1d5db;
        border-radius:8px; font-size:0.9rem; outline:none; transition:border-color .2s;
    }
    .search-bar input:focus { border-color:#004e92; }
    .customer-table { width:100%; border-collapse:collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.07); }
    .customer-table th { background:#f8f9fb; font-size:0.8rem; font-weight:700; color:#6b7280; padding:12px 16px; text-align:left; border-bottom:1px solid #eee; }
    .customer-table td { padding:14px 16px; font-size:0.9rem; color:#374151; border-bottom:1px solid #f0f0f0; vertical-align:middle; }
    .customer-table tr:last-child td { border-bottom:none; }
    .customer-table tr:hover td { background:#f8f9ff; }
    .badge { display:inline-block; font-size:0.75rem; font-weight:600; padding:3px 9px; border-radius:4px; margin-right:4px; }
    .badge-myagent { background:#e0edff; color:#004e92; }
    .badge-fav     { background:#fce4ec; color:#c2185b; }
    .badge-inq     { background:#e8f5e9; color:#2e7d32; }
    .btn-msg { font-size:0.8rem; color:#004e92; border:1px solid #c5d3f0; padding:5px 12px; border-radius:4px; white-space:nowrap; transition:all .2s; }
    .btn-msg:hover { background:#004e92; color:#fff; }
    .empty-state { text-align:center; padding:64px 24px; color:#9ca3af; }
    .empty-state .empty-icon { font-size:2.5rem; display:block; margin-bottom:12px; }
</style>
@endpush

@section('content')
<div class="dashboard">

    <x-agent-sidebar :agent="$agent" active="customers" />

    <main class="main-content">

        <div class="list-header">
            <h2>👥 顧客リスト</h2>
            <span class="list-count" id="visible-count">{{ count($customers) }} 件</span>
        </div>
        <p style="color:#6b7280;font-size:0.88rem;margin-bottom:16px;">
            お気に入り登録・My Agent登録・問い合わせがあるユーザーの一覧です。
        </p>

        <div class="search-bar">
            <input type="text" id="search-input" placeholder="名前で絞り込み..." oninput="filterCustomers()">
        </div>

        @if (empty($customers))
        <div class="empty-state">
            <span class="empty-icon">👥</span>
            <p>まだ接触したユーザーがいません。<br>
               お気に入り登録やお問い合わせがあるユーザーがここに表示されます。</p>
        </div>
        @else
        <table class="customer-table" id="customer-table">
            <thead>
                <tr>
                    <th>ユーザー名</th>
                    <th>接触タイプ</th>
                    <th>問い合わせ数</th>
                    <th>最終接触日時</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $c)
                <tr class="customer-row" data-name="{{ mb_strtolower($c['user']->name) }}">
                    <td><strong>{{ $c['user']->name }}</strong></td>
                    <td>
                        @if ($c['fav_status'] === 2)
                            <span class="badge badge-myagent">⭐ My Agent</span>
                        @elseif ($c['fav_status'] === 1)
                            <span class="badge badge-fav">❤️ お気に入り</span>
                        @endif
                        @if ($c['has_inq'])
                            <span class="badge badge-inq">💬 問い合わせあり</span>
                        @endif
                    </td>
                    <td>{{ $c['inq_count'] > 0 ? $c['inq_count'] . ' 件' : '—' }}</td>
                    <td>{{ $c['contact_at'] ? \Carbon\Carbon::parse($c['contact_at'])->format('Y/m/d H:i') : '—' }}</td>
                    <td>
                        <a href="{{ route('agent.inquiries.index') }}?user_id={{ $c['user']->id }}"
                           class="btn-msg">💬 問い合わせを見る</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

    </main>
</div>
@endsection

@push('scripts')
<script>
function filterCustomers() {
    const q    = document.getElementById('search-input').value.toLowerCase();
    const rows = document.querySelectorAll('.customer-row');
    let visible = 0;
    rows.forEach(function(row) {
        const name = row.dataset.name || '';
        if (name.includes(q)) { row.style.display = ''; visible++; }
        else                   { row.style.display = 'none'; }
    });
    document.getElementById('visible-count').textContent = visible + ' 件';
}
</script>
@endpush
