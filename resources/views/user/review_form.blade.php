@extends('layouts.app')

@section('title', $agent->name . ' のクチコミを投稿 - ERAPRO')

@push('styles')
<style>
    .form-wrap { max-width:640px; margin:0 auto; padding:40px 28px 100px; }
    .form-header { margin-bottom:32px; }
    .form-header h1 { font-size:1.5rem; font-weight:900; color:#111; margin:0 0 6px; letter-spacing:-0.02em; }
    .form-header p  { font-size:0.88rem; color:#888; margin:0; }

    .agent-mini {
        display:flex; align-items:center; gap:16px;
        background:#f8f9ff; border-radius:8px; padding:16px 20px; margin-bottom:32px;
    }
    .agent-mini-img { width:52px; height:52px; border-radius:50%; object-fit:cover; background:#eee; flex-shrink:0; }
    .agent-mini-name { font-size:1rem; font-weight:700; color:#111; }
    .agent-mini-title { font-size:0.82rem; color:#888; margin-top:2px; }

    .form-group { margin-bottom:28px; }
    .form-label { display:block; font-size:0.88rem; font-weight:700; color:#333; margin-bottom:12px; }
    .form-control {
        width:100%; padding:12px 14px; border:1px solid #e0e0e0; border-radius:6px;
        font-size:0.9rem; font-family:inherit; color:#333; background:#fafafa;
        transition:border-color 0.2s; box-sizing:border-box;
    }
    .form-control:focus { outline:none; border-color:#004e92; background:#fff; }
    textarea.form-control { resize:vertical; min-height:120px; line-height:1.7; }

    /* 星評価（CSSラジオボタン方式）*/
    .star-rating { display:flex; flex-direction:row-reverse; gap:6px; }
    .star-rating input[type="radio"] { display:none; }
    .star-rating label {
        font-size:2.2rem; color:#ddd; cursor:pointer;
        transition:color 0.15s, transform 0.15s;
        line-height:1;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label { color:#f4c430; }
    .star-rating label:hover { transform:scale(1.12); }
    .star-hint { font-size:0.78rem; color:#aaa; margin-top:6px; }

    .btn-submit {
        width:100%; padding:16px; background:#004e92; color:#fff;
        border:none; border-radius:8px; font-size:1rem; font-weight:700;
        cursor:pointer; letter-spacing:0.04em; transition:background 0.2s; margin-top:8px;
    }
    .btn-submit:hover { background:#003a70; }
    .back-link { display:inline-block; margin-top:16px; font-size:0.85rem; color:#999; }
    .back-link:hover { color:#004e92; }

    .alert-info {
        background:#fff3e0; border:1px solid #ffe0b2; color:#e65100;
        border-radius:6px; padding:12px 16px; font-size:0.88rem; margin-bottom:24px;
    }
    .invalid-feedback { color:#e91e63; font-size:0.8rem; margin-top:4px; display:block; }
</style>
@endpush

@section('content')
<div class="form-wrap">

    <div class="form-header">
        <h1>★ クチコミを投稿する</h1>
        <p>あなたの体験を共有して、同じ悩みを持つ人の参考にしましょう。</p>
    </div>

    <div class="agent-mini">
        @php
            $img = $agent->profile_img
                ? asset('storage/' . $agent->profile_img)
                : 'https://picsum.photos/seed/agent' . $agent->id . '/100/100';
        @endphp
        <img src="{{ $img }}" class="agent-mini-img" alt="{{ $agent->name }}">
        <div>
            <div class="agent-mini-name">{{ $agent->name }}</div>
            <div class="agent-mini-title">{{ $agent->title }}</div>
        </div>
    </div>

    @if ($existing)
    <div class="alert-info">✏️ 既存のクチコミを編集しています。送信すると内容が更新されます。</div>
    @endif

    <form action="{{ route('review.store') }}" method="POST">
        @csrf
        <input type="hidden" name="agent_id" value="{{ $agent->id }}">

        <div class="form-group">
            <label class="form-label">総合評価</label>
            <div class="star-rating">
                @php $currentRating = old('rating', $existing->rating ?? 0); @endphp
                @for ($i = 5; $i >= 1; $i--)
                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                    {{ (int)$currentRating === $i ? 'checked' : '' }}>
                <label for="star{{ $i }}">★</label>
                @endfor
            </div>
            <div class="star-hint">1〜5の星で評価してください</div>
            @error('rating')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="comment">コメント（任意）</label>
            <textarea name="comment" id="comment" class="form-control"
                placeholder="実際に相談してみての感想、良かった点など自由にお書きください。">{{ old('comment', $existing->comment ?? '') }}</textarea>
            @error('comment')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn-submit">クチコミを送信する</button>
    </form>

    <a href="{{ route('agent.profile', $agent->id) }}" class="back-link">← プロフィールに戻る</a>

</div>
@endsection
