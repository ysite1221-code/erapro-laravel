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
    .step { flex:1; text-align:center; position:relative; z-index:1; }
    .step-circle {
        width:36px; height:36px; border-radius:50%; border:3px solid #e0e0e0;
        background:#fff; display:flex; align-items:center; justify-content:center;
        font-size:0.85rem; font-weight:700; color:#ccc; margin:0 auto 8px;
        transition:all 0.3s;
    }
    .step.done    .step-circle { background:#004e92; border-color:#004e92; color:#fff; }
    .step.current .step-circle { background:#fff; border-color:#004e92; color:#004e92; box-shadow:0 0 0 4px rgba(0,78,146,0.12); }
    .step-label { font-size:0.72rem; color:#aaa; line-height:1.4; }
    .step.done    .step-label { color:#004e92; font-weight:600; }
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

    /* ─── チャットUI ─── */
    .chat-card {
        background:#fff; border-radius:10px; border:1.5px solid #e0e8f4;
        overflow:hidden; margin-bottom:24px;
        box-shadow:0 2px 12px rgba(0,78,146,0.06);
    }
    .chat-header {
        background:linear-gradient(90deg,#004e92,#1a6fba);
        color:#fff; padding:14px 20px;
        display:flex; align-items:center; gap:10px;
    }
    .chat-header-icon  { font-size:1.2rem; }
    .chat-header-title { font-size:0.97rem; font-weight:700; }
    .chat-header-sub   { font-size:0.75rem; opacity:0.75; margin-left:auto; }

    .chat-log {
        display:flex; flex-direction:column; gap:14px;
        height:360px; overflow-y:auto; padding:20px;
        background:#f8faff;
    }

    .msg-row { display:flex; flex-direction:column; }
    .msg-row.mine   { align-items:flex-end; }
    .msg-row.theirs { align-items:flex-start; }

    .msg-inner { display:flex; align-items:flex-end; gap:8px; max-width:78%; }
    .msg-row.mine   .msg-inner { flex-direction:row-reverse; }

    .msg-avatar {
        width:32px; height:32px; border-radius:50%; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        font-size:0.9rem; font-weight:700;
    }
    .msg-row.mine   .msg-avatar { background:#004e92; color:#fff; }
    .msg-row.theirs .msg-avatar { background:#e0e7ff; color:#004e92; }

    .msg-bubble {
        padding:10px 14px; border-radius:18px;
        font-size:0.9rem; line-height:1.65; white-space:pre-wrap;
        word-break:break-word;
    }
    .msg-row.mine   .msg-bubble {
        background:#004e92; color:#fff;
        border-bottom-right-radius:4px;
    }
    .msg-row.theirs .msg-bubble {
        background:#fff; color:#222;
        border-bottom-left-radius:4px;
        box-shadow:0 1px 4px rgba(0,0,0,0.08);
    }

    .msg-meta { font-size:0.7rem; color:#aaa; margin-top:4px; padding:0 4px; }

    .no-messages {
        text-align:center; color:#bbb; font-size:0.85rem;
        padding:40px 20px;
    }

    .chat-footer {
        border-top:1px solid #e8ecf2; padding:14px 16px;
        background:#fff;
    }
    .chat-input-row { display:flex; gap:10px; align-items:flex-end; }
    .chat-textarea {
        flex:1; padding:10px 14px; border:1.5px solid #d1d5db; border-radius:12px;
        font-size:0.9rem; font-family:inherit; resize:none; outline:none;
        line-height:1.5; max-height:120px; overflow-y:auto;
        transition:border-color 0.2s;
    }
    .chat-textarea:focus { border-color:#004e92; }
    .btn-send {
        padding:10px 20px; background:#004e92; color:#fff; border:none;
        border-radius:12px; font-size:0.88rem; font-weight:700; cursor:pointer;
        transition:background 0.2s; white-space:nowrap; flex-shrink:0;
    }
    .btn-send:hover    { background:#003a70; }
    .btn-send:disabled { background:#94a3b8; cursor:not-allowed; }
    .chat-hint { font-size:0.72rem; color:#aaa; margin-top:6px; }

    /* 通報 */
    .btn-report-link {
        display:inline-block; padding:8px 18px; font-size:0.82rem;
        color:#dc2626; border:1.5px solid #dc2626; border-radius:6px;
        text-decoration:none; font-weight:600; transition:all 0.2s;
    }
    .btn-report-link:hover { background:#dc2626; color:#fff; }
</style>
@endpush

@section('content')
<div class="inq-wrap">

    <a href="{{ route('user.inquiries.index') }}" class="back-link">← 送信履歴に戻る</a>
    <h1 class="page-title">相談の詳細・進捗</h1>

    {{-- エージェント情報 --}}
    @php
        $agent   = $inquiry->agent;
        $current = $inquiry->status;
        $img     = $agent->profile_img
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

        @php $steps = [1 => 'リクエスト送信済', 2 => '日程調整中', 3 => '面談完了・提案中', 4 => '完了']; @endphp

        @if ($current === 5)
        <div class="status-cancelled">⚠️ この相談はキャンセルされました。</div>
        @else
        <div class="progress-steps">
            @foreach ($steps as $step => $label)
            <div class="step {{ $current > $step ? 'done' : ($current === $step ? 'current' : '') }}">
                <div class="step-circle">
                    @if ($current > $step) ✓ @else {{ $step }} @endif
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

    {{-- ─── チャット ─── --}}
    <div class="chat-card" id="chat-card">
        <div class="chat-header">
            <span class="chat-header-icon">💬</span>
            <span class="chat-header-title">エージェントとのメッセージ</span>
            <span class="chat-header-sub">{{ $inquiry->messages->count() }} 件</span>
        </div>

        <div class="chat-log" id="chat-log">
            @forelse ($inquiry->messages as $msg)
                @php $isMine = $msg->sender_type === 'user'; @endphp
                <div class="msg-row {{ $isMine ? 'mine' : 'theirs' }}"
                     data-id="{{ $msg->id }}">
                    <div class="msg-inner">
                        <div class="msg-avatar">
                            {{ $isMine ? '私' : 'A' }}
                        </div>
                        <div class="msg-bubble">{{ $msg->message }}</div>
                    </div>
                    <div class="msg-meta">
                        {{ $isMine ? 'あなた' : ($inquiry->agent->name ?? 'エージェント') }}
                        　{{ $msg->created_at->format('n/j H:i') }}
                    </div>
                </div>
            @empty
                <div class="no-messages" id="no-msg-placeholder">
                    まだメッセージはありません。<br>エージェントに質問・相談を送ってみましょう。
                </div>
            @endforelse
        </div>

        <div class="chat-footer">
            <div class="chat-input-row">
                <textarea id="chat-textarea" class="chat-textarea" rows="2"
                          placeholder="メッセージを入力…（Shift+Enter で改行）"
                          maxlength="2000"></textarea>
                <button id="btn-send" class="btn-send">送信</button>
            </div>
            <div class="chat-hint">最大 2000 文字 ／ 送信後、エージェントにメール通知が届きます</div>
            <div id="chat-error" style="color:#ef4444;font-size:0.8rem;margin-top:6px;display:none;"></div>
        </div>
    </div>

    {{-- 相談内容編集 / 通報 --}}
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
        @if ($current !== 4 && $current !== 5)
        <a href="{{ route('inquiry.create', $inquiry->agent_id) }}" class="btn-edit">✏️ 相談内容を編集・再送信する</a>
        @else
        <span></span>
        @endif

        <a href="{{ route('user.report.create', $inquiry->agent_id) }}" class="btn-report-link">
            🚨 このエージェントを通報する
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var log      = document.getElementById('chat-log');
    var textarea = document.getElementById('chat-textarea');
    var btnSend  = document.getElementById('btn-send');
    var errEl    = document.getElementById('chat-error');

    var postUrl  = '{{ route('user.inquiries.messages.store', $inquiry->id) }}';
    var myName   = '{{ addslashes(Auth::guard('user')->user()->name ?? '') }}';
    var csrf     = '{{ csrf_token() }}';

    function scrollBottom() {
        if (log) log.scrollTop = log.scrollHeight;
    }
    scrollBottom();

    function escHtml(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function appendBubble(data) {
        var isMine = data.sender_type === 'user';
        var row = document.createElement('div');
        row.className = 'msg-row ' + (isMine ? 'mine' : 'theirs');
        row.dataset.id = data.id;
        row.innerHTML =
            '<div class="msg-inner">' +
              '<div class="msg-avatar">' + (isMine ? '私' : 'A') + '</div>' +
              '<div class="msg-bubble">' + escHtml(data.message) + '</div>' +
            '</div>' +
            '<div class="msg-meta">' + escHtml(data.sender_name) + '　' + escHtml(data.created_at) + '</div>';

        var placeholder = document.getElementById('no-msg-placeholder');
        if (placeholder) placeholder.remove();

        log.appendChild(row);
        scrollBottom();
    }

    function sendMessage() {
        var text = textarea.value.trim();
        if (!text) return;

        btnSend.disabled = true;
        btnSend.textContent = '送信中…';
        errEl.style.display = 'none';

        fetch(postUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: 'message=' + encodeURIComponent(text),
        })
        .then(function(res) {
            if (!res.ok) throw new Error('送信に失敗しました（' + res.status + '）');
            return res.json();
        })
        .then(function(data) {
            appendBubble(data);
            textarea.value = '';
            textarea.style.height = '';
        })
        .catch(function(err) {
            errEl.textContent = err.message;
            errEl.style.display = 'block';
        })
        .finally(function() {
            btnSend.disabled = false;
            btnSend.textContent = '送信';
            textarea.focus();
        });
    }

    btnSend.addEventListener('click', sendMessage);

    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });
})();
</script>
@endpush
