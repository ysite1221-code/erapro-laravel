@extends('layouts.app')

@section('title', $agent->name . ' - ERAPRO')

@push('styles')
<style>
    .profile-wrap { max-width:800px; margin:0 auto; padding-bottom:100px; }
    .cover-img { width:100%; height:340px; object-fit:cover; display:block; }

    .profile-card {
        background:#fff; margin:-56px 24px 0;
        border-radius:8px; box-shadow:0 4px 24px rgba(0,0,0,0.09);
        padding:40px 48px 48px; position:relative;
    }
    .profile-head { display:flex; gap:24px; align-items:flex-start; margin-bottom:28px; }
    .profile-avatar {
        width:96px; height:96px; border-radius:50%; object-fit:cover;
        border:4px solid #fff; box-shadow:0 4px 16px rgba(0,0,0,0.14);
        flex-shrink:0; margin-top:-72px; background:#eee;
    }
    .profile-head-info { flex:1; padding-top:4px; }
    .area-chip {
        display:inline-block; background:#f0f4ff; color:#004e92;
        font-size:0.75rem; font-weight:500; padding:3px 12px;
        border-radius:4px; margin-bottom:10px; letter-spacing:0.02em;
    }
    .profile-name  { font-size:1.9rem; font-weight:900; color:#111; margin-bottom:6px; letter-spacing:-0.02em; line-height:1.2; }
    .profile-catch { font-size:1rem; color:#004e92; font-weight:700; line-height:1.6; }

    .tag-area { margin:20px 0 32px; }
    .tag { font-size:0.75rem; background:#f0f4ff; color:#004e92; padding:4px 12px; border-radius:4px; margin-right:6px; margin-bottom:6px; display:inline-block; font-weight:500; }

    .sec-title { font-size:1.25rem; font-weight:900; color:#111; margin:48px 0 16px; letter-spacing:-0.01em; padding-bottom:12px; border-bottom:2px solid #f0f0f0; }
    .narrative-text { font-size:1rem; line-height:2.2; color:#444; white-space:pre-wrap; }

    .action-area { display:flex; gap:12px; margin-top:48px; flex-wrap:wrap; }
    .btn-consult {
        flex:2; padding:16px 20px; background:#004e92; color:#fff;
        border:none; border-radius:6px; font-size:0.95rem; font-weight:700;
        cursor:pointer; text-align:center; text-decoration:none; display:block;
        transition:background 0.2s, box-shadow 0.2s; min-width:160px; letter-spacing:0.03em;
    }
    .btn-consult:hover { background:#003a70; color:#fff; box-shadow:0 4px 16px rgba(0,78,146,0.25); }
    .btn-fav {
        flex:1; padding:16px 14px; border-radius:6px; font-size:0.88rem; font-weight:700;
        cursor:pointer; text-align:center; border:2px solid; transition:all 0.2s; background:#fff; min-width:110px;
    }
    .btn-fav-heart { border-color:#e91e63; color:#e91e63; }
    .btn-fav-heart.active { background:#e91e63; color:#fff; }
    .btn-fav-star  { border-color:#004e92; color:#004e92; }
    .btn-fav-star.active  { background:#004e92; color:#fff; }
    .login-hint { font-size:0.8rem; color:#aaa; text-align:center; margin-top:12px; }
    .login-hint a { color:#004e92; }

    .review-summary { display:flex; align-items:center; gap:20px; background:#f8f9ff; border-radius:8px; padding:20px 24px; margin:12px 0 24px; }
    .review-score { font-size:2.8rem; font-weight:900; color:#004e92; line-height:1; letter-spacing:-0.03em; }
    .review-stars-avg { font-size:1.3rem; color:#f4c430; letter-spacing:3px; }
    .review-count { font-size:0.82rem; color:#999; margin-top:4px; }
    .review-list { list-style:none; padding:0; margin:0; }
    .review-item { border-top:1px solid #f0f0f0; padding:20px 0; }
    .review-item:first-child { border-top:none; }
    .review-item-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
    .review-item-stars { font-size:1rem; color:#f4c430; }
    .review-item-date  { font-size:0.78rem; color:#ccc; }
    .review-item-comment { font-size:0.92rem; color:#555; line-height:1.8; }
    .review-empty { color:#bbb; font-size:0.9rem; padding:16px 0; }
    .btn-review-post {
        display:inline-block; margin-top:24px; padding:12px 28px;
        background:#004e92; color:#fff; border-radius:8px;
        font-size:0.9rem; font-weight:bold; transition:background 0.2s;
    }
    .btn-review-post:hover { background:#003a70; color:#fff; }
    .btn-review-edit {
        display:inline-block; margin-top:10px; padding:6px 16px;
        background:#f5f7ff; color:#004e92; border:1px solid #c5d3f0;
        border-radius:6px; font-size:0.8rem; font-weight:600; transition:background 0.2s;
    }
    .btn-review-edit:hover { background:#e8eeff; color:#003a70; }

    .back-link { display:inline-block; padding:20px 0 16px 4px; font-size:0.85rem; color:#999; }
    .back-link:hover { color:#004e92; }

    #toast {
        position:fixed; bottom:32px; left:50%;
        transform:translateX(-50%) translateY(16px);
        background:#111; color:#fff; padding:12px 28px; border-radius:6px;
        font-size:0.875rem; opacity:0; transition:opacity 0.25s, transform 0.25s;
        z-index:1000; pointer-events:none; font-weight:500; letter-spacing:0.02em;
    }
    #toast.show { opacity:1; transform:translateX(-50%) translateY(0); }
</style>
@endpush

@section('content')
<div class="profile-wrap">

    <div style="padding:0 28px;">
        <a href="{{ route('search') }}" class="back-link">← プロ一覧に戻る</a>
    </div>

    @php
        $coverImg = $agent->profile_img
            ? asset('storage/' . $agent->profile_img)
            : 'https://picsum.photos/seed/agent' . $agent->id . '/1200/480';
    @endphp

    <img src="{{ $coverImg }}" class="cover-img" alt="{{ $agent->name }}">

    <div class="profile-card">

        <div class="profile-head">
            <img src="{{ $coverImg }}" class="profile-avatar" alt="{{ $agent->name }}">
            <div class="profile-head-info">
                <span class="area-chip">📍 {{ $areaDisplay }}</span>
                <div class="profile-name">{{ $agent->name }}</div>
                <div class="profile-catch">{{ $agent->title }}</div>
            </div>
        </div>

        @if (!empty($tags))
        <div class="tag-area">
            @foreach ($tags as $t)
            <span class="tag"># {{ $t }}</span>
            @endforeach
        </div>
        @endif

        @if ($agent->story)
        <div class="sec-title">My Story（原体験）</div>
        <div class="narrative-text">{{ $agent->story }}</div>
        @endif

        @if ($agent->philosophy)
        <div class="sec-title">Philosophy（哲学）</div>
        <div class="narrative-text">{{ $agent->philosophy }}</div>
        @endif

        {{-- フラッシュメッセージ --}}
        @if (session('status'))
        <div style="background:#e8f5e9;border:1px solid #c8e6c9;color:#2e7d32;border-radius:6px;padding:12px 16px;font-size:0.9rem;margin-bottom:24px;">
            ✅ {{ session('status') }}
        </div>
        @endif

        {{-- アクションボタン --}}
        <div class="action-area">
            @if ($authUser)
                <a href="{{ route('inquiry.create', $agent->id) }}" class="btn-consult">💬 この人に相談する</a>
                <button
                    class="btn-fav btn-fav-heart {{ $favStatus === 1 ? 'active' : '' }}"
                    id="btnFav"
                    onclick="toggleAction('favorite')"
                >
                    {{ $favStatus === 1 ? '❤️ お気に入り済み' : '♡ お気に入り' }}
                </button>
                <button
                    class="btn-fav btn-fav-star {{ $favStatus === 2 ? 'active' : '' }}"
                    id="btnMyAgent"
                    onclick="toggleAction('my_agent')"
                >
                    {{ $favStatus === 2 ? '⭐ My Agent' : '☆ My Agentに登録' }}
                </button>
            @else
                <a href="{{ route('login') }}" class="btn-consult">💬 ログインして相談する</a>
                <a href="{{ route('login') }}" class="btn-fav btn-fav-heart">♡ お気に入り</a>
            @endif
        </div>

        @if (!$authUser)
        <p class="login-hint">
            <a href="{{ route('login') }}">ログイン</a> または
            <a href="{{ route('user.register') }}">新規登録</a> するとお気に入り・相談機能が使えます
        </p>
        @endif

        {{-- クチコミセクション --}}
        <div class="sec-title">クチコミ・評価</div>

        @if ($reviewPosted)
        <p style="color:#004e92;font-weight:600;margin-bottom:16px;">✅ クチコミを投稿しました。ありがとうございます！</p>
        @endif

        @if ($reviewCount > 0)
        <div class="review-summary">
            <div class="review-score">{{ number_format($avgRating, 1) }}</div>
            <div>
                <div class="review-stars-avg">
                    @php
                        $full  = (int) floor($avgRating);
                        $half  = ($avgRating - $full) >= 0.5 ? 1 : 0;
                        $empty = 5 - $full - $half;
                    @endphp
                    {{ str_repeat('★', $full) }}{{ $half ? '½' : '' }}{{ str_repeat('☆', $empty) }}
                </div>
                <div class="review-count">{{ $reviewCount }} 件の評価</div>
            </div>
        </div>

        <ul class="review-list">
            @foreach ($reviews as $rv)
            <li class="review-item">
                <div class="review-item-header">
                    <span class="review-item-stars">
                        {{ str_repeat('★', $rv->rating) }}{{ str_repeat('☆', 5 - $rv->rating) }}
                    </span>
                    <span class="review-item-date">{{ $rv->updated_at->format('Y年n月') }}</span>
                </div>
                @if ($rv->comment)
                <p class="review-item-comment">{{ $rv->comment }}</p>
                @endif
                @if ($authUser && $rv->user_id === $authUser->id)
                <a href="{{ route('review.create', $agent->id) }}" class="btn-review-edit">✏️ 編集する</a>
                @endif
            </li>
            @endforeach
        </ul>
        @else
        <p class="review-empty">まだクチコミはありません。</p>
        @endif

        @if ($authUser && !$userReviewed)
        <a href="{{ route('review.create', $agent->id) }}" class="btn-review-post">★ クチコミを投稿する</a>
        @endif

        {{-- 通報リンク --}}
        @if ($authUser)
        <div style="margin-top:40px;padding-top:24px;border-top:1px solid #f0f0f0;text-align:right;">
            <a href="{{ route('user.report.create', $agent->id) }}"
               style="font-size:0.78rem;color:#ccc;transition:color 0.2s;"
               onmouseover="this.style.color='#dc2626'"
               onmouseout="this.style.color='#ccc'">
                🚨 このエージェントを通報する
            </a>
        </div>
        @endif

    </div>
</div>

<div id="toast"></div>
@endsection

@push('scripts')
@if ($authUser)
<script>
const agentId   = {{ $agent->id }};
const toggleUrl = '{{ route('favorite.toggle') }}';
const csrfToken = '{{ csrf_token() }}';
let   favStatus = {{ $favStatus }}; // 0=none, 1=fav, 2=myagent

function toggleAction(action) {
    fetch(toggleUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: 'agent_id=' + agentId + '&action=' + action
    })
    .then(r => r.json())
    .then(data => {
        if (action === 'favorite') {
            const btn = document.getElementById('btnFav');
            if (data.result === 'removed') {
                btn.classList.remove('active');
                btn.textContent = '♡ お気に入り';
                favStatus = 0;
                showToast('お気に入りを解除しました');
            } else {
                btn.classList.add('active');
                btn.textContent = '❤️ お気に入り済み';
                if (favStatus === 2) {
                    const btnM = document.getElementById('btnMyAgent');
                    btnM.classList.remove('active');
                    btnM.textContent = '☆ My Agentに登録';
                }
                favStatus = 1;
                showToast('❤️ お気に入りに追加しました');
            }
        } else {
            const btn = document.getElementById('btnMyAgent');
            if (data.result === 'removed') {
                btn.classList.remove('active');
                btn.textContent = '☆ My Agentに登録';
                favStatus = 0;
                showToast('My Agentの登録を解除しました');
            } else {
                btn.classList.add('active');
                btn.textContent = '⭐ My Agent';
                if (favStatus === 1) {
                    const btnF = document.getElementById('btnFav');
                    btnF.classList.remove('active');
                    btnF.textContent = '♡ お気に入り';
                }
                favStatus = 2;
                showToast('⭐ My Agentに登録しました！');
            }
        }
    })
    .catch(() => showToast('エラーが発生しました。再度お試しください。'));
}

function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2500);
}
</script>
@endif
@endpush
