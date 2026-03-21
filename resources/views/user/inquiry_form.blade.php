@extends('layouts.app')

@section('title', $agent->name . ' に相談する - ERAPRO')

@push('styles')
<style>
    .form-wrap { max-width:680px; margin:0 auto; padding:40px 28px 100px; }
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

    .form-group { margin-bottom:24px; }
    .form-label { display:block; font-size:0.88rem; font-weight:700; color:#333; margin-bottom:8px; }
    .form-label span { color:#e91e63; margin-left:4px; font-size:0.75rem; }
    .form-control {
        width:100%; padding:12px 14px; border:1px solid #e0e0e0; border-radius:6px;
        font-size:0.9rem; font-family:inherit; color:#333; background:#fafafa;
        transition:border-color 0.2s; box-sizing:border-box;
    }
    .form-control:focus { outline:none; border-color:#004e92; background:#fff; }
    textarea.form-control { resize:vertical; min-height:100px; line-height:1.7; }

    .radio-group { display:flex; flex-wrap:wrap; gap:8px; }
    .radio-label {
        display:inline-flex; align-items:center; gap:6px;
        padding:8px 16px; border:1.5px solid #e0e0e0; border-radius:6px;
        font-size:0.85rem; cursor:pointer; transition:all 0.2s; background:#fff;
        user-select:none;
    }
    .radio-label input { display:none; }
    .radio-label:has(input:checked) { border-color:#004e92; background:#f0f4ff; color:#004e92; font-weight:700; }
    .radio-label:hover { border-color:#004e92; }

    .btn-submit {
        width:100%; padding:16px; background:#004e92; color:#fff;
        border:none; border-radius:8px; font-size:1rem; font-weight:700;
        cursor:pointer; letter-spacing:0.04em; transition:background 0.2s; margin-top:8px;
    }
    .btn-submit:hover { background:#003a70; }
    .back-link { display:inline-block; margin-top:16px; font-size:0.85rem; color:#999; }
    .back-link:hover { color:#004e92; }

    .alert-info {
        background:#e8f5e9; border:1px solid #c8e6c9; color:#2e7d32;
        border-radius:6px; padding:12px 16px; font-size:0.88rem; margin-bottom:24px;
    }
    .invalid-feedback { color:#e91e63; font-size:0.8rem; margin-top:4px; display:block; }
</style>
@endpush

@section('content')
<div class="form-wrap">

    <div class="form-header">
        <h1>💬 エージェントに相談する</h1>
        <p>あなたの状況を伝えることで、より的確なアドバイスが受けられます。</p>
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
    <div class="alert-info">✅ すでに相談リクエストを送信済みです。内容を更新して再送信できます。</div>
    @endif

    <form action="{{ route('inquiry.store') }}" method="POST">
        @csrf
        <input type="hidden" name="agent_id" value="{{ $agent->id }}">

        <div class="form-group">
            <label class="form-label">相談の目的<span>必須</span></label>
            <div class="radio-group">
                @foreach ([
                    '保険の見直し' => '保険の見直し',
                    '新規加入を検討' => '新規加入を検討',
                    '将来の資産設計' => '将来の資産設計',
                    '相続・贈与対策' => '相続・贈与対策',
                    '老後の備え' => '老後の備え',
                    'その他' => 'その他',
                ] as $val => $label)
                <label class="radio-label">
                    <input type="radio" name="purpose" value="{{ $val }}"
                        {{ old('purpose', $existing->purpose ?? '') === $val ? 'checked' : '' }}>
                    {{ $label }}
                </label>
                @endforeach
            </div>
            @error('purpose')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">相談のきっかけ<span style="color:#999;font-size:0.75rem;">任意</span></label>
            <div class="radio-group">
                @foreach ([
                    '結婚・出産' => '結婚・出産',
                    '転職・独立' => '転職・独立',
                    '住宅購入' => '住宅購入',
                    '親の介護・相続' => '親の介護・相続',
                    '更新のタイミング' => '更新のタイミング',
                    '特になし' => '特になし',
                ] as $val => $label)
                <label class="radio-label">
                    <input type="radio" name="trigger" value="{{ $val }}"
                        {{ old('trigger', $existing->trigger ?? '') === $val ? 'checked' : '' }}>
                    {{ $label }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">希望するスタイル<span style="color:#999;font-size:0.75rem;">任意</span></label>
            <div class="radio-group">
                @foreach ([
                    'データ・数字で丁寧に説明してほしい' => '📊 データ重視',
                    'まずは話を聞いてほしい' => '💛 寄り添い重視',
                    'バランスよく提案してほしい' => '🤝 バランス重視',
                ] as $val => $label)
                <label class="radio-label">
                    <input type="radio" name="preferred_style" value="{{ $val }}"
                        {{ old('preferred_style', $existing->preferred_style ?? '') === $val ? 'checked' : '' }}>
                    {{ $label }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="note">その他、伝えたいこと<span style="color:#999;font-size:0.75rem;">任意</span></label>
            <textarea name="note" id="note" class="form-control" placeholder="自由にご記入ください（家族構成、現在の保険状況、気になることなど）">{{ old('note', $existing->note ?? '') }}</textarea>
            @error('note')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn-submit">送信する</button>
    </form>

    <a href="{{ route('agent.profile', $agent->id) }}" class="back-link">← プロフィールに戻る</a>

</div>
@endsection
