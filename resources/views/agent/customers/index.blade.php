@extends('layouts.agent')

@section('title', '顧客リスト - ERAPRO Agent')

@push('styles')
<style>
    .customer-table { width:100%; border-collapse:collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.07); }
    .customer-table th { background:#f8f9fb; font-size:0.8rem; font-weight:700; color:#6b7280; padding:12px 16px; text-align:left; border-bottom:1px solid #eee; }
    .customer-table td { padding:14px 16px; font-size:0.9rem; color:#374151; border-bottom:1px solid #f0f0f0; vertical-align:middle; }
    .customer-table tr:last-child td { border-bottom:none; }
    .customer-table tr:hover td { background:#f8f9ff; }
    .badge-status { display:inline-block; font-size:0.75rem; font-weight:600; padding:3px 10px; border-radius:4px; }
    .badge-new    { background:#e0f2fe; color:#0277bd; }
    .badge-active { background:#e8f5e9; color:#2e7d32; }
    .badge-done   { background:#f3f4f6; color:#9ca3af; }
    .empty-state  { text-align:center; padding:64px 24px; color:#9ca3af; }
    .empty-state p { margin-top:12px; font-size:0.9rem; }
</style>
@endpush

@section('content')
<div class="dashboard">

    {{-- サイドバー --}}
    <aside class="sidebar">
        <img src="{{ $agent->profile_img ? asset('storage/' . $agent->profile_img) : 'https://placehold.co/150x150/e0e0e0/888?text=No+Img' }}"
             class="sidebar-avatar" alt="プロフィール">
        <ul>
            <li><a href="{{ route('agent.dashboard') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">dashboard</span>ダッシュボード
            </a></li>
            <li><a href="{{ route('agent.profile.edit') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">person</span>プロフィール編集
            </a></li>
            <li><a href="{{ route('agent.inquiries.index') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">chat</span>問い合わせ管理
            </a></li>
            <li><a href="{{ route('agent.customers.index') }}" class="sidebar-link active">
                <span class="material-icons-outlined sidebar-icon">people</span>顧客リスト
            </a></li>
            <li><a href="{{ route('agent.kyc.form') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">verified_user</span>本人確認（KYC）
            </a></li>
        </ul>
        <a href="{{ route('agent.profile', $agent->id) }}" target="_blank" class="sidebar-public-btn">自分の公開ページを見る</a>
    </aside>

    <main class="main-content">
        <h2>顧客リスト</h2>
        <p style="color:#6b7280;font-size:0.88rem;margin-bottom:24px;">
            あなたに問い合わせを送ったユーザーの一覧です（重複排除・最新問い合わせ順）。
        </p>

        @if ($customers->isEmpty())
        <div class="empty-state">
            <span style="font-size:2.5rem;">👥</span>
            <p>まだ顧客はいません。<br>プロフィールを充実させてユーザーからの問い合わせを増やしましょう！</p>
        </div>
        @else
        <table class="customer-table">
            <thead>
                <tr>
                    <th>氏名</th>
                    <th>メールアドレス</th>
                    <th>最終問い合わせ日</th>
                    <th>問い合わせ数</th>
                    <th>最新ステータス</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $user)
                @php
                    $latestInquiry = $user->inquiries->first();
                    $statusLabels  = [
                        0 => ['label' => '新着', 'class' => 'badge-new'],
                        1 => ['label' => '対応中', 'class' => 'badge-active'],
                        2 => ['label' => '成約', 'class' => 'badge-done'],
                        9 => ['label' => 'クローズ', 'class' => 'badge-done'],
                    ];
                    $s = $statusLabels[$latestInquiry?->status ?? 0] ?? ['label' => '-', 'class' => 'badge-done'];
                @endphp
                <tr>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->last_inquiry_at ? \Carbon\Carbon::parse($user->last_inquiry_at)->format('Y/m/d') : '-' }}</td>
                    <td>{{ $user->inquiries->count() }} 件</td>
                    <td>
                        @if ($latestInquiry)
                        <span class="badge-status {{ $s['class'] }}">{{ $s['label'] }}</span>
                        @else
                        <span style="color:#ccc;">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:24px;">
            {{ $customers->links() }}
        </div>
        @endif

    </main>
</div>
@endsection
