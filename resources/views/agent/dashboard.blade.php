@extends('layouts.agent')

@section('title', 'ダッシュボード - ERAPRO Agent')

@section('content')
<div class="dashboard">

    {{-- サイドバー --}}
    <aside class="sidebar">
        <img src="{{ $agent->profile_img ? asset('storage/' . $agent->profile_img) : 'https://placehold.co/150x150/e0e0e0/888?text=No+Img' }}"
             class="sidebar-avatar" alt="プロフィール">
        <ul>
            <li><a href="{{ route('agent.dashboard') }}" class="sidebar-link active">
                <span class="material-icons-outlined sidebar-icon">dashboard</span>ダッシュボード
            </a></li>
            <li><a href="{{ route('agent.profile.edit') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">person</span>プロフィール編集
            </a></li>
            <li><a href="{{ route('agent.inquiries.index') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">chat</span>問い合わせ管理
            </a></li>
            <li><a href="#" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">people</span>顧客リスト
            </a></li>
            <li><a href="{{ route('agent.kyc.form') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">verified_user</span>本人確認（KYC）
            </a></li>
        </ul>
        <a href="{{ route('agent.profile', $agent->id) }}" target="_blank" class="sidebar-public-btn">自分の公開ページを見る</a>

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

    <main class="main-content">

        <h2>活動サマリー</h2>

        {{-- プロフィール未完成アラート --}}
        @if (!$isPublic)
        <div class="alert-box" style="background:#fff0f0;border-left-color:#dc3545;">
            <strong style="color:#dc3545;">⚠️ プロフィールが未完成のため、現在ユーザーの検索画面には【非公開】となっています（{{ $completionPct }}%）</strong><br>
            写真・キャッチコピー・My Storyを入力して、プロフィールを公開状態にしましょう！<br>
            <a href="{{ route('agent.profile.edit') }}" style="color:#dc3545;font-weight:bold;">→ 今すぐ編集して公開する</a>
        </div>
        @elseif ($completionPct < 100)
        <div class="alert-box">
            <strong>📝 プロフィールをさらに充実させましょう（{{ $completionPct }}%）</strong><br>
            活動エリア・タグ・Philosophyを追加すると、マッチング率がさらに向上します。<br>
            <a href="{{ route('agent.profile.edit') }}" style="color:#004e92;font-weight:bold;">→ プロフィールを編集する</a>
        </div>
        @endif

        {{-- KPIグリッド --}}
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-label">📊 今月の閲覧数</div>
                <div class="kpi-value">{{ number_format($monthlyViews) }}<span class="kpi-unit"> PV</span></div>
            </div>
            <div class="kpi-card today">
                <div class="kpi-label">🔍 本日の閲覧数</div>
                <div class="kpi-value">{{ number_format($todayViews) }}<span class="kpi-unit"> PV</span></div>
            </div>
            <div class="kpi-card fav">
                <div class="kpi-label">❤️ お気に入り登録</div>
                <div class="kpi-value">{{ number_format($favCount) }}<span class="kpi-unit"> 人</span></div>
            </div>
            <div class="kpi-card myagent">
                <div class="kpi-label">⭐ My Agent 登録</div>
                <div class="kpi-value">{{ number_format($myAgentCount) }}<span class="kpi-unit"> 人</span></div>
            </div>
            <div class="kpi-card review">
                <div class="kpi-label">💬 クチコミ平均評価</div>
                @if ($reviewStats && $reviewStats->review_count > 0)
                <div class="kpi-value">{{ number_format($reviewStats->avg_rating, 1) }}<span class="kpi-unit"> / 5.0</span></div>
                <div style="font-size:0.78rem;color:#bbb;margin-top:8px;">{{ $reviewStats->review_count }}件のクチコミ</div>
                @else
                <div class="kpi-value" style="font-size:1.4rem;color:#ccc;">未投稿</div>
                <div style="font-size:0.78rem;color:#ccc;margin-top:8px;">クチコミを集めましょう</div>
                @endif
            </div>
        </div>

        {{-- プロフィール完成度 --}}
        <div class="completion-wrap">
            <div class="completion-header">
                <span class="completion-label">プロフィール完成度</span>
                <span class="completion-pct">{{ $completionPct }}%</span>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width:{{ $completionPct }}%;"></div>
            </div>
            <div class="completion-items">
                @foreach ($completionItems as $field => $label)
                <span class="ci-chip {{ !empty($agent->$field) ? 'ci-done' : 'ci-miss' }}">
                    {{ !empty($agent->$field) ? '✓' : '✗' }} {{ $label }}
                </span>
                @endforeach
            </div>
        </div>

        {{-- 最近の閲覧アクティビティ --}}
        <div class="activity-wrap">
            <h3>最近のプロフィール閲覧（直近10件）</h3>
            @if ($recentViews->isEmpty())
                <p style="color:#999;font-size:0.9rem;">まだ閲覧されていません。プロフィールを充実させてシェアしましょう！</p>
            @else
            <ul class="activity-list">
                @foreach ($recentViews as $v)
                <li>
                    <span class="act-icon">👤</span>
                    <span>プロフィールが閲覧されました</span>
                    <span class="act-time">{{ $v->viewed_at->format('m/d H:i') }}</span>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        {{-- 直近クチコミ --}}
        <div class="activity-wrap">
            <h3>💬 最近のクチコミ・評価</h3>
            @if ($recentReviews->isEmpty())
                <p style="color:#999;font-size:0.9rem;">まだクチコミが投稿されていません。ユーザーにフィードバックをリクエストしてみましょう！</p>
            @else
            <ul class="activity-list">
                @foreach ($recentReviews as $rv)
                <li>
                    <span class="act-icon">⭐</span>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                            <span style="font-weight:700;color:#e6a800;letter-spacing:1px;">
                                {{ str_repeat('★', $rv->rating) }}{{ str_repeat('☆', 5 - $rv->rating) }}
                            </span>
                            <span style="font-size:0.8rem;color:#888;">{{ $rv->user->name }}</span>
                        </div>
                        @if ($rv->comment)
                        <p style="margin:0;font-size:0.82rem;color:#666;line-height:1.6;">
                            {{ mb_substr($rv->comment, 0, 80) }}{{ mb_strlen($rv->comment) > 80 ? '…' : '' }}
                        </p>
                        @endif
                    </div>
                    <span class="act-time">{{ $rv->updated_at->format('m/d') }}</span>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        {{-- 登録情報確認 --}}
        <div class="info-card">
            <h3>登録情報の確認 <a href="{{ route('agent.profile.edit') }}" style="font-size:0.85rem;font-weight:normal;color:#004e92;">編集する</a></h3>
            <hr style="border:0;border-top:1px solid #eee;margin-bottom:14px;">
            <p><strong>活動名:</strong> {{ $agent->name }}</p>
            <p><strong>エリア:</strong> {{ $agent->area ?: '未設定' }}</p>
            <p><strong>キャッチコピー:</strong> {{ $agent->title ?: '未設定' }}</p>
            <p><strong>タグ:</strong> {{ $agent->tags ?: '未設定' }}</p>
            @if ($agent->story)
            <p style="color:#666;font-size:0.9rem;margin-top:10px;line-height:1.6;">
                {{ mb_substr($agent->story, 0, 100) }}...
            </p>
            @endif
        </div>

    </main>
</div>
@endsection
