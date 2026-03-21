@props(['agent', 'active' => ''])

@php
    $sidebarUnread = 0;
    if (Auth::guard('agent')->check()) {
        $agentId = Auth::guard('agent')->id();
        $sidebarUnread = \App\Models\InquiryMessage::whereHas('inquiry', fn($q) => $q->where('agent_id', $agentId))
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->count();
    }
@endphp

<aside class="sidebar">
    <img src="{{ $agent->profile_img ? asset('storage/' . $agent->profile_img) : 'https://placehold.co/150x150/e0e0e0/888?text=No+Img' }}"
         class="sidebar-avatar" alt="プロフィール">
    <ul>
        <li><a href="{{ route('agent.dashboard') }}"
               class="sidebar-link {{ $active === 'dashboard' ? 'active' : '' }}">
            <span class="material-icons-outlined sidebar-icon">dashboard</span>ダッシュボード
        </a></li>
        <li><a href="{{ route('agent.profile.edit') }}"
               class="sidebar-link {{ $active === 'profile' ? 'active' : '' }}">
            <span class="material-icons-outlined sidebar-icon">person</span>プロフィール編集
        </a></li>
        <li><a href="{{ route('agent.inquiries.index') }}"
               class="sidebar-link {{ $active === 'inquiries' ? 'active' : '' }}"
               style="display:flex;align-items:center;justify-content:space-between;">
            <span style="display:flex;align-items:center;">
                <span class="material-icons-outlined sidebar-icon">chat</span>問い合わせ管理
            </span>
            @if ($sidebarUnread > 0)
            <span style="background:#ef4444;color:#fff;font-size:0.68rem;font-weight:700;
                         min-width:18px;height:18px;border-radius:9px;display:inline-flex;
                         align-items:center;justify-content:center;padding:0 4px;line-height:1;">
                {{ $sidebarUnread > 99 ? '99+' : $sidebarUnread }}
            </span>
            @endif
        </a></li>
        <li><a href="{{ route('agent.customers.index') }}"
               class="sidebar-link {{ $active === 'customers' ? 'active' : '' }}">
            <span class="material-icons-outlined sidebar-icon">people</span>顧客リスト
        </a></li>
        <li><a href="{{ route('agent.report') }}"
               class="sidebar-link {{ $active === 'report' ? 'active' : '' }}">
            <span class="material-icons-outlined sidebar-icon">analytics</span>レポート
        </a></li>
        <li><a href="{{ route('agent.kyc.form') }}"
               class="sidebar-link {{ $active === 'kyc' ? 'active' : '' }}">
            <span class="material-icons-outlined sidebar-icon">verified_user</span>本人確認（KYC）
        </a></li>
    </ul>
    <a href="{{ route('agent.profile', $agent->id) }}" target="_blank" class="sidebar-public-btn">
        自分の公開ページを見る
    </a>

    <form action="{{ route('agent.withdraw') }}" method="post" style="margin-top:16px;text-align:center;"
          onsubmit="return confirm('本当に退会しますか？\n退会するとアカウント情報が削除され、元に戻せません。');">
        @csrf
        <button type="submit"
                style="width:100%;padding:9px;background:transparent;color:#dc3545;
                       border:1px solid #dc3545;border-radius:4px;font-size:0.82rem;
                       cursor:pointer;transition:all 0.2s;"
                onmouseover="this.style.background='#dc3545';this.style.color='#fff';"
                onmouseout="this.style.background='transparent';this.style.color='#dc3545';">
            退会する
        </button>
    </form>
</aside>
