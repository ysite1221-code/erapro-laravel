@extends('layouts.app')

@section('title', '相談の詳細・進捗 - ERAPRO')

@push('styles')
<style>
    body { background:#f5f5f5; }
    .inq-wrap { max-width:720px; margin:0 auto; padding:40px 28px 100px; }
    .back-link { display:inline-block; font-size:0.85rem; color:#999; margin-bottom:20px; }
    .back-link:hover { color:#004e92; }
    .page-title { font-size:1.3rem; font-weight:900; color:#111; margin:0 0 24px; }

    /* エージェントカード */
    .agent-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0;
        padding:20px 24px; display:flex; gap:16px; align-items:center; margin-bottom:24px;
        text-decoration:none; color:inherit; transition:box-shadow 0.2s;
    }
    .agent-card:hover { box-shadow:0 4px 16px rgba(0,0,0,0.07); }
    .agent-img { width:56px; height:56px; border-radius:50%; object-fit:cover; background:#eee; flex-shrink:0; }
    .agent-name { font-size:1rem; font-weight:700; color:#111; margin-bottom:3px; }
    .agent-title { font-size:0.82rem; color:#888; }
    .agent-link { margin-left:auto; font-size:0.82rem; color:#004e92; font-weight:600; white-space:nowrap; }

    /* プログレスバー */
    .progress-section {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0;
        padding:24px 28px; margin-bottom:24px;
    }
    .progress-title { font-size:0.88rem; font-weight:700; color:#888; margin-bottom:20px; }
    .progress-steps { display:flex; align-items:flex-start; gap:0; position:relative; }
    .progress-steps::before {
        content:''; position:absolute; top:18px; left:18px;
        width:calc(100% - 36px); height:3px;
        background:#e0e0e0; z-index:0;
    }
    .step {
        flex:1; text-align:center; position:relative; z-index:1;
    }
    .step-circle {
        width:36px; height:36px; border-radius:50%; border:3px solid #e0e0e0;
        background:#fff; display:flex; align-items:center; justify-content:center;
        font-size:0.85rem; font-weight:700; color:#ccc; margin:0 auto 8px;
        transition:all 0.3s;
    }
    .step.done .step-circle  { background:#004e92; border-color:#004e92; color:#fff; }
    .step.current .step-circle { background:#fff; border-color:#004e92; color:#004e92; box-shadow:0 0 0 4px rgba(0,78,146,0.12); }
    .step-label { font-size:0.72rem; color:#aaa; line-height:1.4; }
    .step.done .step-label    { color:#004e92; font-weight:600; }
    .step.current .step-label { color:#004e92; font-weight:700; }

    .status-cancelled {
        background:#fff3f3; border:1.5px solid #ffd0d0; border-radius:8px;
        padding:14px 18px; font-size:0.88rem; color:#c62828; text-align:center;
        margin-top:16px; font-weight:600;
    }

    /* 相談内容 */
    .detail-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0;
        padding:24px 28px; margin-bottom:24px;
    }
    .detail-card h3 { font-size:0.97rem; font-weight:700; color:#111; margin:0 0 16px; }
    .detail-row { display:flex; gap:16px; padding:12px 0; border-bottom:1px solid #f5f5f5; }
    .detail-row:last-child { border-bottom:none; }
    .detail-label { width:140px; flex-shrink:0; font-size:0.8rem; font-weight:700; color:#888; padding-top:2px; }
    .detail-value { flex:1; font-size:0.88rem; color:#333; line-height:1.7; }

    .btn-edit {
        display:inline-block; padding:11px 24px; background:#fff;
        color:#004e92; border:1.5px solid #004e92; border-radius:6px;
        font-size:0.88rem; font-weight:700; text-decoration:none;
        transition:all 0.2s;
    }
    .btn-edit:hover { background:#004e92; color:#fff; }
</style>
@endpush

@section('content')
<div class="inq-wrap">

    <a href="{{ route('user.inquiries.index') }}" class="back-link">← 送信履歴に戻る</a>
    <h1 class="page-title">相談の詳細・進捗</h1>

    {{-- エージェント情報 --}}
    @php
        $agent = $inquiry->agent;
        $img = $agent->profile_img
            ? asset('storage/' . $agent->profile_img)
            : 'https://picsum.photos/seed/agent' . $inquiry->agent_id . '/100/100';
    @endphp
    <a href="{{ route('agent.profile', $inquiry->agent_id) }}" class="agent-card">
        <img src="{{ $img }}" class="agent-img" alt="{{ $agent->name ?? '' }}">
        <div>
            <div class="agent-name">{{ $agent->name ?? '削除済みエージェント' }}</div>
            <div class="agent-title">{{ $agent->title ?? '' }}</div>
        </div>
        <span class="agent-link">プロフィールを見る →</span>
    </a>

    {{-- プログレスバー --}}
    <div class="progress-section">
        <div class="progress-title">📍 現在の進捗状況</div>

        @php
            $steps = [1 => 'リクエスト送信済', 2 => '日程調整中', 3 => '面談完了・提案中', 4 => '完了'];
            $current = $inquiry->status;
        @endphp

        @if ($current === 5)
        <div class="status-cancelled">⚠️ この相談はキャンセルされました。</div>
        @else
        <div class="progress-steps">
            @foreach ($steps as $step => $label)
            <div class="step {{ $current > $step ? 'done' : ($current === $step ? 'current' : '') }}">
                <div class="step-circle">
                    @if ($current > $step)
                    ✓
                    @else
                    {{ $step }}
                    @endif
                </div>
                <div class="step-label">{{ $label }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- 相談内容 --}}
    <div class="detail-card">
        <h3>送信した相談内容</h3>
        <div class="detail-row">
            <div class="detail-label">相談の目的</div>
            <div class="detail-value">{{ $inquiry->purpose }}</div>
        </div>
        @if ($inquiry->trigger)
        <div class="detail-row">
            <div class="detail-label">相談のきっかけ</div>
            <div class="detail-value">{{ $inquiry->trigger }}</div>
        </div>
        @endif
        @if ($inquiry->preferred_style)
        <div class="detail-row">
            <div class="detail-label">希望スタイル</div>
            <div class="detail-value">{{ $inquiry->preferred_style }}</div>
        </div>
        @endif
        @if ($inquiry->note)
        <div class="detail-row">
            <div class="detail-label">その他・備考</div>
            <div class="detail-value" style="white-space:pre-wrap;">{{ $inquiry->note }}</div>
        </div>
        @endif
        <div class="detail-row">
            <div class="detail-label">送信日時</div>
            <div class="detail-value">{{ $inquiry->created_at->format('Y年n月j日 H:i') }}</div>
        </div>
    </div>

    @if ($current !== 4 && $current !== 5)
    <a href="{{ route('inquiry.create', $inquiry->agent_id) }}" class="btn-edit">✏️ 相談内容を編集・再送信する</a>
    @endif

</div>
@endsection
