@extends('layouts.app')

@section('title', 'ぴったり診断 - ERAPRO')

@push('styles')
<style>
    body { background: #f5f5f5; }
    .diag-wrap { max-width: 700px; margin: 0 auto; padding: 48px 28px 100px; }

    .diag-header { text-align: center; margin-bottom: 48px; }
    .diag-header h1 { font-size: 1.8rem; font-weight: 900; color: #111; margin-bottom: 10px; letter-spacing: -0.02em; }
    .diag-header p  { font-size: 0.95rem; color: #888; line-height: 1.7; }

    .progress-bar-wrap { background: #e0e0e0; border-radius: 4px; height: 6px; margin-bottom: 40px; }
    .progress-bar { height: 6px; background: #004e92; border-radius: 4px; transition: width 0.4s ease; }
    .progress-label { font-size: 0.78rem; color: #aaa; text-align: right; margin-top: 6px; }

    .q-card {
        background: #fff; border-radius: 12px; padding: 32px 36px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07); margin-bottom: 28px;
        display: none;
    }
    .q-card.active { display: block; }
    .q-num   { font-size: 0.75rem; font-weight: 700; color: #004e92; letter-spacing: 0.1em; margin-bottom: 10px; }
    .q-text  { font-size: 1.1rem; font-weight: 700; color: #111; margin-bottom: 24px; line-height: 1.5; }

    .options { display: flex; flex-direction: column; gap: 10px; }
    .option-label {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 14px 18px; border: 1.5px solid #e0e0e0; border-radius: 8px;
        cursor: pointer; transition: all 0.18s; background: #fafafa;
    }
    .option-label:hover { border-color: #004e92; background: #f0f4ff; }
    .option-label input[type="radio"] { display: none; }
    .option-label:has(input:checked) { border-color: #004e92; background: #f0f4ff; }
    .option-mark {
        width: 22px; height: 22px; border-radius: 50%; border: 2px solid #ddd;
        flex-shrink: 0; display: flex; align-items: center; justify-content: center;
        margin-top: 1px; transition: all 0.18s;
    }
    .option-label:has(input:checked) .option-mark { border-color: #004e92; background: #004e92; }
    .option-label:has(input:checked) .option-mark::after { content: ''; width: 8px; height: 8px; border-radius: 50%; background: #fff; display: block; }
    .option-text { font-size: 0.9rem; color: #333; line-height: 1.6; }

    .q-nav { display: flex; gap: 12px; margin-top: 28px; justify-content: flex-end; }
    .btn-prev {
        padding: 11px 24px; background: #fff; color: #888;
        border: 1.5px solid #e0e0e0; border-radius: 6px; font-size: 0.9rem;
        cursor: pointer; font-family: inherit; transition: all 0.18s;
    }
    .btn-prev:hover { border-color: #999; color: #555; }
    .btn-next, .btn-submit {
        padding: 11px 28px; background: #004e92; color: #fff; border: none;
        border-radius: 6px; font-size: 0.9rem; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background 0.18s;
    }
    .btn-next:hover, .btn-submit:hover { background: #003a70; }
    .btn-next:disabled { background: #ccc; cursor: not-allowed; }
</style>
@endpush

@section('content')
<div class="diag-wrap">

    <div class="diag-header">
        <h1>📋 ぴったり診断</h1>
        <p>5つの質問に答えるだけで、あなたに合った保険のプロのタイプがわかります。<br>所要時間：約1分</p>
    </div>

    <div class="progress-bar-wrap">
        <div class="progress-bar" id="progressBar" style="width:20%;"></div>
    </div>
    <p class="progress-label" id="progressLabel">1 / {{ count($questions) }}</p>

    <form action="{{ route('diagnosis.store') }}" method="POST" id="diagForm">
        @csrf

        @foreach ($questions as $i => $q)
        <div class="q-card {{ $i === 0 ? 'active' : '' }}" id="q-{{ $i }}">
            <div class="q-num">Q{{ $i + 1 }} / {{ count($questions) }}</div>
            <div class="q-text">{{ $q['text'] }}</div>
            <div class="options">
                @foreach ($q['options'] as $score => $text)
                <label class="option-label">
                    <input type="radio" name="answers[{{ $i }}]" value="{{ $score }}" required>
                    <span class="option-mark"></span>
                    <span class="option-text">{{ $text }}</span>
                </label>
                @endforeach
            </div>
            <div class="q-nav">
                @if ($i > 0)
                <button type="button" class="btn-prev" onclick="goTo({{ $i - 1 }})">← 戻る</button>
                @endif
                @if ($i < count($questions) - 1)
                <button type="button" class="btn-next" id="next-{{ $i }}" onclick="goTo({{ $i + 1 }})" disabled>
                    次へ →
                </button>
                @else
                <button type="submit" class="btn-submit" id="submit-btn" disabled>
                    診断結果を見る ✨
                </button>
                @endif
            </div>
        </div>
        @endforeach

    </form>

</div>
@endsection

@push('scripts')
<script>
const totalQ = {{ count($questions) }};
let current = 0;

function goTo(idx) {
    document.getElementById('q-' + current).classList.remove('active');
    current = idx;
    document.getElementById('q-' + current).classList.add('active');
    const pct = ((current + 1) / totalQ * 100).toFixed(0);
    document.getElementById('progressBar').style.width = pct + '%';
    document.getElementById('progressLabel').textContent = (current + 1) + ' / ' + totalQ;
}

// ラジオ選択時にボタン有効化
document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function () {
        const qi = parseInt(this.name.match(/\d+/)[0]);
        const nextBtn   = document.getElementById('next-' + qi);
        const submitBtn = document.getElementById('submit-btn');
        if (nextBtn)   nextBtn.disabled   = false;
        if (submitBtn) submitBtn.disabled = false;
    });
});
</script>
@endpush
