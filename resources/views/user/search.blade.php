@extends('layouts.app')

@section('title', 'プロを探す - ERAPRO')

@push('styles')
<style>
    body { background: #f5f5f5; }

    .page-header { background:#fff; border-bottom:1px solid #ebebeb; padding:48px 28px 40px; }
    .page-header-inner { max-width:1040px; margin:0 auto; }
    .page-header h1 { font-size:2rem; font-weight:900; color:#111; margin:0 0 6px; letter-spacing:-0.02em; }
    .page-header p  { font-size:0.9rem; color:#999; margin:0; }

    .search-wrap { max-width:1040px; margin:0 auto; padding:0 28px; }
    .search-box {
        background:#fff; border-radius:8px; box-shadow:0 2px 16px rgba(0,0,0,0.07);
        padding:24px 28px; margin:32px 0 0;
        display:flex; gap:12px; flex-wrap:wrap; align-items:center;
    }
    .search-select, .search-input {
        padding:12px 14px; border:1px solid #e0e0e0; border-radius:6px;
        font-size:0.9rem; font-family:inherit; color:#333;
        background:#fafafa; transition:border-color 0.2s;
    }
    .search-select { min-width:140px; }
    .search-input  { flex:1; min-width:180px; }
    .search-select:focus, .search-input:focus { outline:none; border-color:#004e92; background:#fff; }
    .btn-submit {
        background:#004e92; color:#fff; border:none; padding:12px 32px;
        border-radius:6px; font-size:0.9rem; font-weight:700; cursor:pointer;
        font-family:inherit; letter-spacing:0.03em; transition:background 0.2s;
    }
    .btn-submit:hover { background:#003a70; }

    .diag-banner {
        border-radius:8px; padding:20px 24px; margin-top:24px;
        display:flex; align-items:center; gap:16px; color:#fff;
    }
    .diag-banner-emoji { font-size:2rem; line-height:1; }
    .diag-banner-label { font-size:0.78rem; opacity:0.8; margin-bottom:2px; }
    .diag-banner-title { font-size:1.05rem; font-weight:700; }
    .diag-filter-badge { font-size:0.78rem; background:rgba(255,255,255,0.2); padding:3px 12px; border-radius:4px; margin-left:12px; }
    .diag-retry-link   { margin-left:auto; font-size:0.82rem; color:rgba(255,255,255,0.75); white-space:nowrap; }
    .diag-retry-link:hover { color:#fff; }

    .result-meta { max-width:1040px; margin:32px auto 0; padding:0 28px; font-size:0.85rem; color:#999; font-weight:500; }

    .card-list-wrap { max-width:1040px; margin:16px auto 80px; padding:0 28px; }

    .card-img-wrap { position:relative; overflow:hidden; }
    .card-img { width:100%; height:210px; object-fit:cover; display:block; transition:transform 0.4s ease; }
    .card:hover .card-img { transform:scale(1.04); }
    .card-area-chip {
        position:absolute; bottom:10px; left:12px;
        background:rgba(0,0,0,0.55); color:#fff; font-size:0.72rem; font-weight:500;
        padding:3px 10px; border-radius:4px; backdrop-filter:blur(4px);
    }
    .card-body { padding:20px 20px 22px; }
    .card-catch { font-size:0.92rem; font-weight:700; color:#111; margin:0 0 6px; line-height:1.5; }
    .card-name  { font-size:0.82rem; font-weight:500; color:#888; margin:0 0 12px; }
    .card-tags  { margin-bottom:12px; }
    .card-story { font-size:0.83rem; color:#999; line-height:1.7; margin:0; }

    .interest-badge {
        display:block; font-size:0.75rem; font-weight:600;
        background:#e8f5e9; color:#2e7d32; border:1px solid #c8e6c9;
        padding:4px 10px; border-radius:6px; margin-bottom:8px; line-height:1.4;
    }
    .compat-badge {
        display:inline-block; font-size:0.75rem; font-weight:700;
        background:linear-gradient(135deg,#f4c430,#e8961c); color:#fff;
        padding:3px 10px; border-radius:12px; margin-bottom:8px;
    }
    .card-rating { font-size:0.78rem; font-weight:700; color:#e6a800; margin-bottom:6px; }
    .card-rating.card-rating-empty { color:#ccc; font-weight:400; }
    .rating-count { color:#999; font-weight:400; }

    .empty-state { grid-column:1/-1; text-align:center; padding:80px 20px; color:#bbb; font-size:0.95rem; }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <h1>プロフェッショナルを探す</h1>
        <p>エリア・キーワードで、あなたに合う保険のプロを見つけましょう。</p>
    </div>
</div>

<div class="search-wrap">
    {{-- 診断タイプバナー --}}
    @if ($matchedType)
    <div class="diag-banner" style="background:{{ $matchedType['color'] }};">
        <span class="diag-banner-emoji">{{ $matchedType['emoji'] }}</span>
        <div>
            <div class="diag-banner-label">診断タイプ</div>
            <div class="diag-banner-title">
                {{ $matchedType['label'] }}
                @if ($userScore !== null)
                <span class="diag-filter-badge">相性順に表示中</span>
                @endif
            </div>
        </div>
        <a href="{{ route('diagnosis') }}" class="diag-retry-link">再診断する →</a>
    </div>
    @endif

    {{-- 検索フォーム --}}
    <form action="{{ route('search') }}" method="get" class="search-box">
        <select name="area" class="search-select">
            <option value="">都道府県を選択</option>
            @foreach ($prefectures as $p)
            <option value="{{ $p }}" {{ $area === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
        <input type="text" name="tag" class="search-input"
               placeholder="キーワード（例: 子育て, 相続）"
               value="{{ $tag }}">
        @if (!empty($diagType))
        <input type="hidden" name="type" value="{{ $diagType }}">
        @endif
        <button type="submit" class="btn-submit">検索</button>
    </form>
</div>

<div class="result-meta">{{ $agents->count() }} 件のプロフェッショナル</div>

<div class="card-list-wrap">
    <div class="card-list">
        @forelse ($agents as $agent)
        @php
            $img = $agent->profile_img
                ? asset('storage/' . $agent->profile_img)
                : 'https://picsum.photos/seed/agent' . $agent->id . '/600/360';

            $areaChip = $agent->area ?: '未設定';
            if (!empty($agent->area_detail)) {
                $parts = array_map('trim', explode(',', $agent->area_detail));
                if (!empty($parts[0])) { $areaChip .= ' ' . $parts[0]; }
            }

            $matchedKw = '';
            if (!empty($userInterests) && !empty($agent->tags)) {
                foreach ($userInterests as $kw) {
                    if (mb_stripos($agent->tags, $kw) !== false) { $matchedKw = $kw; break; }
                }
            }

            $compat = ($userScore !== null && $agent->diagnosis_score !== null)
                ? 100 - abs($userScore - $agent->diagnosis_score)
                : null;
        @endphp
        <a href="{{ route('agent.profile', $agent->id) }}" class="card">
            <div class="card-img-wrap">
                <img src="{{ $img }}" class="card-img" alt="{{ $agent->name }}">
                <span class="card-area-chip">📍 {{ $areaChip }}</span>
            </div>
            <div class="card-body">
                @if ($matchedKw)
                <span class="interest-badge">💡 あなたの関心（{{ $matchedKw }}）に強いプロです</span>
                @endif
                @if ($compat !== null)
                <span class="compat-badge">✨ 相性 {{ $compat }}%</span>
                @endif
                @if ($agent->reviews_count > 0)
                <div class="card-rating">★ {{ number_format($agent->reviews_avg_rating, 1) }} <span class="rating-count">({{ $agent->reviews_count }}件)</span></div>
                @else
                <div class="card-rating card-rating-empty">クチコミ未投稿</div>
                @endif
                <p class="card-catch">{{ mb_substr($agent->title ?? '', 0, 45) }}</p>
                <h3 class="card-name">{{ $agent->name }}</h3>
                @if (!empty($agent->tags))
                <div class="card-tags">
                    @foreach (array_filter(array_map('trim', explode(',', $agent->tags))) as $t)
                    <span class="tag">#{{ $t }}</span>
                    @endforeach
                </div>
                @endif
                <p class="card-story">{{ mb_substr($agent->story ?? '', 0, 52) }}{{ mb_strlen($agent->story ?? '') > 52 ? '…' : '' }}</p>
            </div>
        </a>
        @empty
        <div class="empty-state">条件に一致するプロフェッショナルは見つかりませんでした。</div>
        @endforelse
    </div>
</div>

@endsection
