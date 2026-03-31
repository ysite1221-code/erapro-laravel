@extends('layouts.app')

@section('title', 'マイページ - ERAPRO')

@push('styles')
<style>
    body { background: #f4f6f9; }
    .mypage-wrap { max-width: 900px; margin: 0 auto; padding: 32px 20px 80px; }

    /* ウェルカムバナー */
    .welcome-banner {
        background: linear-gradient(135deg, #004e92, #000428);
        color: #fff;
        border-radius: 14px;
        padding: 28px 32px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .welcome-avatar {
        width: 56px; height: 56px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; flex-shrink: 0;
    }
    .welcome-text h2 { font-size: 1.3rem; margin-bottom: 4px; }
    .welcome-text p  { font-size: 0.9rem; opacity: 0.8; }
    .diag-badge {
        margin-left: auto;
        background: rgba(255,255,255,0.15);
        border-radius: 8px; padding: 10px 14px;
        text-align: center; font-size: 0.8rem; white-space: nowrap;
    }
    .diag-badge .emoji { font-size: 1.4rem; display: block; }

    /* クイックアクション */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px; margin-bottom: 32px;
    }
    .qa-card {
        background: #fff; border-radius: 10px; padding: 18px 16px;
        text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
        color: #333; text-decoration: none; display: block;
    }
    .qa-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); color: #004e92; }
    .qa-card .qa-icon { font-size: 1.8rem; margin-bottom: 8px; display: block; }
    .qa-card h3 { font-size: 0.95rem; margin-bottom: 4px; }
    .qa-card p  { font-size: 0.78rem; color: #999; margin: 0; }

    /* 関心事セクション */
    .interest-section {
        background: #fff; border-radius: 12px;
        padding: 22px 28px; margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .interest-section-header { margin-bottom: 14px; }
    .interest-section-header h3 { font-size: 0.97rem; font-weight: 700; color: #111; margin: 0 0 3px; }
    .interest-section-header p  { font-size: 0.8rem; color: #999; margin: 0; }
    .interest-chips { display: flex; flex-wrap: wrap; gap: 9px; }
    .interest-chip {
        padding: 7px 16px; border: 1.5px solid #d8d8d8;
        border-radius: 24px; background: #fafafa; color: #555;
        font-size: 0.84rem; font-family: inherit; cursor: pointer;
        transition: all 0.17s; line-height: 1;
    }
    .interest-chip:hover { border-color: #004e92; color: #004e92; background: #f0f4ff; }
    .interest-chip.active { border-color: #004e92; background: #004e92; color: #fff; font-weight: 600; }
    .interest-chip.saving { opacity: 0.5; pointer-events: none; }

    /* おすすめ・近隣セクション */
    .recommend-section, .nearby-section { margin-bottom: 28px; }
    .recommend-header, .nearby-header {
        display: flex; align-items: baseline; gap: 10px; margin-bottom: 14px;
    }
    .recommend-header h3, .nearby-header h3 { font-size: 1rem; font-weight: 700; color: #111; margin: 0; }
    .recommend-header p,  .nearby-header p  { font-size: 0.8rem; color: #999; margin: 0; }
    .recommend-scroll {
        display: flex; gap: 14px; overflow-x: auto;
        padding-bottom: 10px; scrollbar-width: thin; scrollbar-color: #e0e0e0 transparent;
    }
    .recommend-scroll::-webkit-scrollbar { height: 4px; }
    .recommend-scroll::-webkit-scrollbar-thumb { background: #ddd; border-radius: 2px; }
    .rec-card {
        flex-shrink: 0; width: 196px; background: #fff;
        border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        overflow: hidden; text-decoration: none; color: inherit;
        transition: transform 0.2s, box-shadow 0.2s; display: block;
    }
    .rec-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.11); }
    .rec-card-img-wrap { width: 100%; height: 108px; overflow: hidden; }
    .rec-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; display: block; }
    .rec-card:hover .rec-card-img { transform: scale(1.05); }
    .rec-card-body { padding: 11px 13px 13px; }
    .rec-card-catch { font-size: 0.79rem; font-weight: 700; color: #111; margin: 0 0 3px; line-height: 1.4; }
    .rec-card-name  { font-size: 0.76rem; color: #888; margin: 0 0 6px; }
    .rec-card-area  { font-size: 0.71rem; color: #bbb; }
    .rec-card-more {
        display: flex; align-items: center; justify-content: center;
        background: #f4f6f9; border: 1.5px dashed #d0d0d0; color: #aaa;
        font-size: 0.82rem; text-align: center;
    }
    .rec-card-more:hover { border-color: #004e92; color: #004e92; background: #f0f4ff; }
    .interest-badge {
        display: inline-block; font-size: 0.71rem; font-weight: 600;
        background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9;
        padding: 2px 8px; border-radius: 10px; margin-bottom: 5px;
    }
    .compat-badge {
        display: inline-block; font-size: 0.72rem; font-weight: 700;
        background: linear-gradient(135deg, #f4c430, #e8961c);
        color: #fff; padding: 2px 8px; border-radius: 10px; margin-bottom: 6px;
    }
    .nearby-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px; }
    .nearby-card {
        background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden; text-decoration: none; color: inherit;
        transition: transform 0.2s, box-shadow 0.2s; display: block;
    }
    .nearby-card:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,0,0,0.1); }
    .nearby-card-img { width: 100%; height: 100px; object-fit: cover; display: block; }
    .nearby-card-body { padding: 11px 14px 13px; }
    .nearby-card-area {
        font-size: 0.72rem; color: #004e92; background: #f0f4ff;
        padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 6px;
    }
    .nearby-card-catch { font-size: 0.8rem; font-weight: 700; color: #111; margin: 0 0 3px; line-height: 1.4; }
    .nearby-card-name  { font-size: 0.76rem; color: #888; margin: 0; }

    /* タブ */
    .tab-nav { display: flex; gap: 4px; border-bottom: 2px solid #e0e0e0; margin-bottom: 24px; }
    .tab-btn {
        padding: 12px 22px; border: none; background: none;
        font-size: 0.95rem; font-weight: 600; color: #999; cursor: pointer;
        border-bottom: 3px solid transparent; margin-bottom: -2px;
        transition: color 0.2s, border-color 0.2s;
    }
    .tab-btn.active { color: #004e92; border-color: #004e92; }
    .tab-btn .count {
        display: inline-block; background: #004e92; color: #fff;
        font-size: 0.7rem; padding: 1px 7px; border-radius: 12px;
        margin-left: 6px; vertical-align: middle;
    }
    .tab-btn:not(.active) .count { background: #ccc; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }

    /* Agentカード */
    .agent-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; }
    .agent-card {
        background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden; transition: transform 0.2s; position: relative;
    }
    .agent-card:hover { transform: translateY(-3px); }
    .agent-card-img { width: 100%; height: 120px; object-fit: cover; background: #eee; }
    .agent-card-body { padding: 14px 16px; }
    .agent-card-body h3 { font-size: 1rem; color: #004e92; margin-bottom: 4px; }
    .agent-card-body .catch { font-size: 0.82rem; color: #555; margin-bottom: 8px; }
    .agent-card-body .area-tag {
        display: inline-block; font-size: 0.75rem;
        background: #e8f0fe; color: #004e92;
        padding: 2px 8px; border-radius: 12px; margin-bottom: 10px;
    }
    .agent-card-actions { display: flex; gap: 8px; padding: 0 16px 14px; }
    .btn-profile {
        flex: 1; padding: 8px; background: #004e92; color: #fff;
        border: none; border-radius: 6px; font-size: 0.82rem; cursor: pointer;
        text-align: center; text-decoration: none; display: block; transition: background 0.2s;
    }
    .btn-profile:hover { background: #003a70; color: #fff; }
    .btn-remove {
        padding: 8px 12px; background: #fff; color: #dc3545;
        border: 1px solid #dc3545; border-radius: 6px; font-size: 0.82rem;
        cursor: pointer; transition: all 0.2s;
    }
    .btn-remove:hover { background: #dc3545; color: #fff; }
    .btn-review {
        padding: 8px 12px; background: #fff; color: #f4c430;
        border: 1px solid #f4c430; border-radius: 6px; font-size: 0.82rem;
        cursor: pointer; text-decoration: none; text-align: center; display: block; transition: all 0.2s;
    }
    .btn-review:hover { background: #f4c430; color: #fff; }
    .my-agent-badge {
        position: absolute; top: 10px; right: 10px;
        background: #004e92; color: #fff; font-size: 0.7rem; font-weight: bold;
        padding: 4px 10px; border-radius: 12px;
    }

    /* 空状態 */
    .empty-state { text-align: center; padding: 60px 20px; color: #aaa; }
    .empty-state .empty-icon { font-size: 3rem; margin-bottom: 16px; display: block; }
    .empty-state p { font-size: 0.95rem; margin-bottom: 20px; }
    .empty-state a {
        display: inline-block; padding: 12px 28px;
        background: #004e92; color: #fff; border-radius: 8px;
        font-weight: bold; font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="mypage-wrap">

    {{-- ウェルカムバナー --}}
    <div class="welcome-banner">
        <div class="welcome-avatar">👤</div>
        <div class="welcome-text">
            <h2>こんにちは、{{ $user->name }} さん</h2>
            <p>今日はどんなプロを探しますか？</p>
        </div>
        @if ($user->diagnosis_type && isset($typeLabels[$user->diagnosis_type]))
        <div class="diag-badge">
            <span class="emoji">{{ $typeLabels[$user->diagnosis_type][1] }}</span>
            {{ $typeLabels[$user->diagnosis_type][0] }}
        </div>
        @endif
    </div>

    {{-- 関心事選択 --}}
    <div class="interest-section">
        <div class="interest-section-header">
            <h3>今のあなたに当てはまる関心事は？</h3>
            <p>選択した関心事に強いプロをおすすめします（複数選択可）</p>
        </div>
        <div class="interest-chips" id="interest-chips">
            @foreach ($allInterests as $kw)
            <button
                class="interest-chip {{ in_array($kw, $userInterests) ? 'active' : '' }}"
                data-interest="{{ $kw }}"
                onclick="toggleInterest(this)">
                {{ in_array($kw, $userInterests) ? '✓ ' : '' }}{{ $kw }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- クイックアクション --}}
    <div class="quick-actions">
        <a href="{{ route('search') }}" class="qa-card">
            <span class="qa-icon">🔍</span>
            <h3>保険のプロを探す</h3>
            <p>エリア・タグで検索</p>
        </a>
        <a href="{{ route('diagnosis') }}" class="qa-card">
            <span class="qa-icon">📋</span>
            <h3>ぴったり診断</h3>
            <p>{{ $user->diagnosis_type ? '再診断する' : '診断してみる' }}</p>
        </a>
        @if ($user->diagnosis_type)
        <a href="{{ route('search', ['type' => $user->diagnosis_type]) }}" class="qa-card">
            <span class="qa-icon">✨</span>
            <h3>相性のいいプロ</h3>
            <p>診断結果でマッチング</p>
        </a>
        @endif
        <a href="{{ route('user.inquiries.index') }}" class="qa-card" style="position:relative;">
            @if ($unreadCount > 0)
            <span style="position:absolute;top:10px;right:10px;background:#ef4444;color:#fff;
                         font-size:0.68rem;font-weight:700;min-width:20px;height:20px;
                         border-radius:10px;display:inline-flex;align-items:center;
                         justify-content:center;padding:0 5px;line-height:1;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
            @endif
            <span class="qa-icon">💬</span>
            <h3>送信履歴・進捗</h3>
            <p>相談の対応状況を確認</p>
        </a>
    </div>

    {{-- おすすめAgent --}}
    @if ($recommended->isNotEmpty())
    <div class="recommend-section">
        <div class="recommend-header">
            <h3>✨ あなたへのおすすめのプロ</h3>
            <p>選択した関心事と診断タイプからピックアップ</p>
        </div>
        <div class="recommend-scroll">
            @foreach ($recommended as $r)
            @php
                $recImg = $r->profile_img
                    ? asset('storage/' . $r->profile_img)
                    : 'https://picsum.photos/seed/agent' . $r->id . '/300/200';
                $matchedKw = '';
                foreach ($userInterests as $kw) {
                    if (!empty($r->tags) && mb_stripos($r->tags, $kw) !== false) {
                        $matchedKw = $kw; break;
                    }
                }
                $compat = ($user->diagnosis_score !== null && $r->diagnosis_score !== null)
                    ? 100 - abs($user->diagnosis_score - $r->diagnosis_score)
                    : null;
            @endphp
            <a href="{{ route('agent.profile', $r->id) }}" class="rec-card">
                <div class="rec-card-img-wrap">
                    <img src="{{ $recImg }}" class="rec-card-img" alt="{{ $r->name }}">
                </div>
                <div class="rec-card-body">
                    @if ($matchedKw)
                    <span class="interest-badge">💡 {{ $matchedKw }}に強いプロ</span><br>
                    @endif
                    @if ($compat !== null)
                    <span class="compat-badge" style="display:inline-block;font-size:0.7rem;margin-bottom:4px;">✨ 相性 {{ $compat }}%</span>
                    @endif
                    <p class="rec-card-catch">{{ mb_substr($r->title ?? '', 0, 28) }}{{ mb_strlen($r->title ?? '') > 28 ? '…' : '' }}</p>
                    <p class="rec-card-name">{{ $r->name }}</p>
                    <span class="rec-card-area">📍 {{ $r->area ?: '未設定' }}</span>
                </div>
            </a>
            @endforeach
            <a href="{{ route('search') }}" class="rec-card rec-card-more">
                <div>
                    <span style="font-size:1.4rem;display:block;margin-bottom:6px;">→</span>
                    <span>もっと見る</span>
                </div>
            </a>
        </div>
    </div>
    @endif

    {{-- 近隣Agent --}}
    @if ($nearbyAgents->isNotEmpty())
    <div class="nearby-section">
        <div class="nearby-header">
            <h3>📍 近隣のおすすめプロ（{{ $user->area }}）</h3>
            <p>あなたのエリアで活動しているプロフェッショナル</p>
        </div>
        <div class="nearby-grid">
            @foreach ($nearbyAgents as $na)
            @php
                $naImg = $na->profile_img
                    ? asset('storage/' . $na->profile_img)
                    : 'https://picsum.photos/seed/agent' . $na->id . '/400/200';
                $naArea = $na->area ?? '';
                if (!empty($na->area_detail)) {
                    $parts = array_map('trim', explode(',', $na->area_detail));
                    if (!empty($parts[0])) { $naArea .= ' ' . $parts[0]; }
                }
                $naCompat = ($user->diagnosis_score !== null && $na->diagnosis_score !== null)
                    ? 100 - abs($user->diagnosis_score - $na->diagnosis_score)
                    : null;
            @endphp
            <a href="{{ route('agent.profile', $na->id) }}" class="nearby-card">
                <img src="{{ $naImg }}" class="nearby-card-img" alt="{{ $na->name }}">
                <div class="nearby-card-body">
                    @if ($naCompat !== null)
                    <span class="compat-badge" style="display:inline-block;font-size:0.7rem;margin-bottom:5px;">✨ 相性 {{ $naCompat }}%</span><br>
                    @endif
                    <span class="nearby-card-area">📍 {{ $naArea }}</span>
                    <p class="nearby-card-catch">{{ mb_substr($na->title ?? '', 0, 28) }}{{ mb_strlen($na->title ?? '') > 28 ? '…' : '' }}</p>
                    <p class="nearby-card-name">{{ $na->name }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- タブ: お気に入り / My Agent / 通報履歴 --}}
    <div class="tab-nav">
        <button class="tab-btn active" data-tab="favorites">
            ❤️ お気に入り
            <span class="count">{{ $favorites->count() }}</span>
        </button>
        <button class="tab-btn" data-tab="my_agents">
            ⭐ My Agent
            <span class="count">{{ $myAgents->count() }}</span>
        </button>
        <button class="tab-btn" data-tab="reports" style="position:relative;">
            🚨 通報履歴
            <span class="count">{{ $myReports->count() }}</span>
            @if ($unreadReportCount > 0)
            <span style="position:absolute;top:4px;right:4px;background:#ef4444;color:#fff;
                         font-size:0.62rem;font-weight:700;min-width:16px;height:16px;
                         border-radius:8px;display:inline-flex;align-items:center;
                         justify-content:center;padding:0 3px;line-height:1;">
                {{ $unreadReportCount }}
            </span>
            @endif
        </button>
    </div>

    {{-- お気に入りタブ --}}
    <div class="tab-content active" id="tab-favorites">
        @if ($favorites->isNotEmpty())
        <div class="agent-list">
            @foreach ($favorites as $fav)
            @php
                $a = $fav->agent;
                $img = $a->profile_img
                    ? asset('storage/' . $a->profile_img)
                    : 'https://picsum.photos/seed/agent' . $a->id . '/600/360';
                $compat = ($user->diagnosis_score !== null && $a->diagnosis_score !== null)
                    ? 100 - abs($user->diagnosis_score - $a->diagnosis_score)
                    : null;
            @endphp
            <div class="agent-card" id="fav-card-{{ $a->id }}">
                <img src="{{ $img }}" class="agent-card-img" alt="{{ $a->name }}">
                <div class="agent-card-body">
                    @if ($compat !== null)
                    <span class="compat-badge">✨ 相性 {{ $compat }}%</span>
                    @endif
                    <span class="area-tag">📍 {{ $a->area ?: '未設定' }}</span>
                    <h3>{{ $a->name }}</h3>
                    <p class="catch">{{ mb_substr($a->title ?? '', 0, 40) }}</p>
                </div>
                <div class="agent-card-actions">
                    <a href="{{ route('agent.profile', $a->id) }}" class="btn-profile">プロフィールを見る</a>
                    <button class="btn-remove" onclick="removeFavorite({{ $a->id }}, 'fav-card-{{ $a->id }}')">削除</button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <span class="empty-icon">❤️</span>
            <p>まだお気に入りに追加していません。<br>気になるプロを♡でお気に入り登録しましょう。</p>
            <a href="{{ route('search') }}">保険のプロを探す</a>
        </div>
        @endif
    </div>

    {{-- My Agentタブ --}}
    <div class="tab-content" id="tab-my_agents">
        @if ($myAgents->isNotEmpty())
        <div class="agent-list">
            @foreach ($myAgents as $fav)
            @php
                $a = $fav->agent;
                $img = $a->profile_img
                    ? asset('storage/' . $a->profile_img)
                    : 'https://picsum.photos/seed/agent' . $a->id . '/600/360';
                $compat = ($user->diagnosis_score !== null && $a->diagnosis_score !== null)
                    ? 100 - abs($user->diagnosis_score - $a->diagnosis_score)
                    : null;
            @endphp
            <div class="agent-card" id="myagent-card-{{ $a->id }}">
                <span class="my-agent-badge">⭐ My Agent</span>
                <img src="{{ $img }}" class="agent-card-img" alt="{{ $a->name }}">
                <div class="agent-card-body">
                    @if ($compat !== null)
                    <span class="compat-badge">✨ 相性 {{ $compat }}%</span>
                    @endif
                    <span class="area-tag">📍 {{ $a->area ?: '未設定' }}</span>
                    <h3>{{ $a->name }}</h3>
                    <p class="catch">{{ mb_substr($a->title ?? '', 0, 40) }}</p>
                </div>
                <div class="agent-card-actions">
                    <a href="{{ route('agent.profile', $a->id) }}" class="btn-profile">プロフィールを見る</a>
                    <a href="{{ route('review.create', $a->id) }}" class="btn-review">★ クチコミ</a>
                    <button class="btn-remove" onclick="removeMyAgent({{ $a->id }}, 'myagent-card-{{ $a->id }}')">解除</button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <span class="empty-icon">⭐</span>
            <p>まだMy Agentが登録されていません。<br>プロフィールページから「My Agentに登録」してみましょう。</p>
            <a href="{{ route('search') }}">保険のプロを探す</a>
        </div>
        @endif
    </div>

    {{-- 通報履歴タブ --}}
    <div class="tab-content" id="tab-reports">
        @if ($myReports->isNotEmpty())
        <div style="display:flex;flex-direction:column;gap:12px;">
            @php
            $reportStatusLabels = [0 => '未対応', 1 => '対応中', 2 => '対応済み', 9 => '却下'];
            $reportStatusColors = [
                0 => 'background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;',
                1 => 'background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;',
                2 => 'background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;',
                9 => 'background:#f9fafb;color:#9ca3af;border:1px solid #e5e7eb;',
            ];
            @endphp
            @foreach ($myReports as $rpt)
            <a href="{{ route('user.reports.show', $rpt->id) }}"
               style="background:#fff;border-radius:10px;border:1.5px solid {{ $rpt->is_read_by_user ? '#f0f0f0' : '#ef4444' }};
                      padding:16px 20px;text-decoration:none;color:inherit;display:block;
                      transition:box-shadow 0.2s;position:relative;"
               onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'"
               onmouseout="this.style.boxShadow='none'">
                @if (! $rpt->is_read_by_user)
                <span style="position:absolute;top:10px;right:10px;background:#ef4444;color:#fff;
                             font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:10px;">
                    NEW
                </span>
                @endif
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                    <span style="font-size:0.82rem;color:#9ca3af;">#{{ $rpt->id }}</span>
                    <span style="display:inline-block;padding:3px 10px;border-radius:10px;
                                 font-size:0.72rem;font-weight:700;{{ $reportStatusColors[$rpt->status] ?? '' }}">
                        {{ $reportStatusLabels[$rpt->status] ?? '?' }}
                    </span>
                    <span style="font-size:0.8rem;color:#555;font-weight:600;">
                        対象: {{ $rpt->agent?->name ?? '削除済みエージェント' }}
                    </span>
                </div>
                <div style="font-size:0.88rem;color:#333;margin-bottom:4px;">{{ $rpt->reason }}</div>
                <div style="font-size:0.76rem;color:#aaa;">{{ $rpt->created_at->format('Y年n月j日 H:i') }} 送信</div>
            </a>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <span class="empty-icon">🚨</span>
            <p>まだ通報の履歴はありません。</p>
        </div>
        @endif
    </div>

    {{-- 退会セクション --}}
    <div style="margin-top:48px;padding-top:32px;border-top:1px solid #e0e0e0;text-align:center;">
        <p style="font-size:0.85rem;color:#aaa;margin-bottom:12px;">アカウントを削除する場合はこちら</p>
        <form action="{{ route('user.withdraw') }}" method="POST"
              onsubmit="return confirm('本当に退会しますか？\n退会するとアカウント情報が削除され、元に戻せません。');">
            @csrf
            <button type="submit"
                    style="padding:10px 28px;background:#fff;color:#dc3545;border:1px solid #dc3545;
                           border-radius:6px;font-size:0.88rem;cursor:pointer;transition:all 0.2s;"
                    onmouseover="this.style.background='#dc3545';this.style.color='#fff';"
                    onmouseout="this.style.background='#fff';this.style.color='#dc3545';">
                退会する
            </button>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
// 関心事チップ
function toggleInterest(el) {
    const interest = el.dataset.interest;
    el.classList.add('saving');
    fetch('{{ route('user.interests.toggle') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: 'interest=' + encodeURIComponent(interest)
    })
    .then(r => r.json())
    .then(data => {
        el.classList.remove('saving');
        if (data.result === 'added') {
            el.classList.add('active');
            el.textContent = '✓ ' + interest;
        } else {
            el.classList.remove('active');
            el.textContent = interest;
        }
        setTimeout(() => location.reload(), 350);
    })
    .catch(() => el.classList.remove('saving'));
}

// タブ切り替え
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
});

// お気に入り削除
function removeFavorite(agentId, cardId) {
    if (!confirm('お気に入りから削除しますか？')) return;
    fetch('{{ route('favorite.toggle') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: 'agent_id=' + agentId + '&action=favorite'
    })
    .then(r => r.json())
    .then(data => {
        if (data.result === 'removed') {
            const card = document.getElementById(cardId);
            if (card) { card.style.transition = 'opacity .3s'; card.style.opacity = '0'; setTimeout(() => card.remove(), 300); }
        }
    })
    .catch(() => alert('エラーが発生しました。'));
}

// My Agent解除
function removeMyAgent(agentId, cardId) {
    if (!confirm('My Agentの登録を解除しますか？')) return;
    fetch('{{ route('favorite.toggle') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: 'agent_id=' + agentId + '&action=my_agent'
    })
    .then(r => r.json())
    .then(data => {
        if (data.result === 'removed') {
            const card = document.getElementById(cardId);
            if (card) { card.style.transition = 'opacity .3s'; card.style.opacity = '0'; setTimeout(() => card.remove(), 300); }
        }
    })
    .catch(() => alert('エラーが発生しました。'));
}
</script>
@endpush
