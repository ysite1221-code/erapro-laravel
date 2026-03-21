@extends('layouts.app')

@section('title', '診断結果 - ERAPRO')

@push('styles')
<style>
    body { background: #f5f5f5; }
    .result-wrap { max-width: 640px; margin: 0 auto; padding: 64px 28px 100px; text-align: center; }

    .result-emoji { font-size: 4rem; margin-bottom: 16px; display: block; }
    .result-label { font-size: 0.78rem; font-weight: 700; color: #888; letter-spacing: 0.1em; margin-bottom: 8px; }
    .result-type  { font-size: 2rem; font-weight: 900; margin-bottom: 20px; letter-spacing: -0.02em; }
    .result-desc  {
        font-size: 0.95rem; line-height: 1.9; color: #555; margin-bottom: 36px;
        background: #fff; border-radius: 12px; padding: 24px 28px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    }

    .score-wrap {
        background: #fff; border-radius: 12px; padding: 20px 28px; margin-bottom: 36px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    }
    .score-label { font-size: 0.78rem; color: #999; margin-bottom: 10px; }
    .score-bar-wrap { background: #e8eaf0; border-radius: 4px; height: 8px; position: relative; }
    .score-bar { height: 8px; border-radius: 4px; transition: width 1s ease; }
    .score-ticks { display: flex; justify-content: space-between; margin-top: 6px; font-size: 0.68rem; color: #bbb; }

    .cta-section { display: flex; flex-direction: column; gap: 12px; align-items: center; }
    .btn-search {
        display: inline-block; padding: 16px 48px; background: #004e92; color: #fff;
        border-radius: 8px; font-size: 0.95rem; font-weight: 700; text-decoration: none;
        transition: background 0.2s; letter-spacing: 0.04em; width: 100%; max-width: 360px;
    }
    .btn-search:hover { background: #003a70; color: #fff; }
    .btn-retry {
        font-size: 0.85rem; color: #999; text-decoration: none; margin-top: 4px;
    }
    .btn-retry:hover { color: #004e92; }

    @if (Auth::guard('user')->guest())
    .login-note { font-size: 0.82rem; color: #aaa; margin-top: 12px; }
    .login-note a { color: #004e92; }
    @endif
</style>
@endpush

@section('content')
<div class="result-wrap">

    <span class="result-emoji">{{ $typeInfo['emoji'] }}</span>
    <p class="result-label">あなたの診断タイプ</p>
    <h1 class="result-type" style="color: {{ $typeInfo['color'] }}">{{ $typeInfo['label'] }}</h1>

    <div class="result-desc">{{ $typeInfo['desc'] }}</div>

    <div class="score-wrap">
        <p class="score-label">あなたのスコア（0 = 感情重視 / 100 = 論理重視）</p>
        <div class="score-bar-wrap">
            <div class="score-bar" id="scoreBar"
                 style="width:0%; background:{{ $typeInfo['color'] }};"></div>
        </div>
        <div class="score-ticks">
            <span>💛 感情</span>
            <span>🤝 バランス</span>
            <span>📊 論理</span>
        </div>
    </div>

    <div class="cta-section">
        <a href="{{ route('search', ['type' => $type]) }}" class="btn-search">
            ✨ あなたに合うプロを見る
        </a>
        @auth('user')
        <p style="font-size:0.82rem;color:#aaa;">診断結果はマイページに保存されました</p>
        @else
        <p class="login-note">
            <a href="{{ route('login') }}">ログイン</a> または
            <a href="{{ route('user.register') }}">新規登録</a>
            すると結果が保存されます
        </p>
        @endauth
        <a href="{{ route('diagnosis') }}" class="btn-retry">もう一度診断する</a>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        document.getElementById('scoreBar').style.width = '{{ $score }}%';
    }, 200);
});
</script>
@endpush
