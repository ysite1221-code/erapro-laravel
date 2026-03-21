@extends('layouts.agent')

@section('title', '問い合わせ詳細 - ERAPRO Agent')

@push('styles')
<style>
    .inq-wrap { max-width:780px; margin:0 auto; padding:32px 28px 80px; }
    .back-link { display:inline-block; font-size:0.85rem; color:#999; margin-bottom:20px; }
    .back-link:hover { color:#004e92; }
    .page-title { font-size:1.3rem; font-weight:900; color:#111; margin:0 0 24px; }

    .alert-success {
        background:#e8f5e9; border:1px solid #c8e6c9; color:#2e7d32;
        border-radius:6px; padding:12px 16px; font-size:0.88rem; margin-bottom:20px;
    }

    .user-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0;
        padding:20px 24px; display:flex; align-items:center; gap:16px; margin-bottom:24px;
    }
    .user-avatar {
        width:52px; height:52px; border-radius:50%; background:#e8f0fe;
        display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0;
    }
    .user-name { font-size:1rem; font-weight:700; color:#111; margin-bottom:3px; }
    .user-meta { font-size:0.8rem; color:#999; }

    .detail-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0;
        padding:28px 32px; margin-bottom:24px;
    }
    .detail-row { display:flex; gap:16px; padding:14px 0; border-bottom:1px solid #f5f5f5; }
    .detail-row:last-child { border-bottom:none; }
    .detail-label { width:160px; flex-shrink:0; font-size:0.82rem; font-weight:700; color:#888; padding-top:2px; }
    .detail-value { flex:1; font-size:0.9rem; color:#333; line-height:1.7; }

    /* ステータス */
    .status-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0; padding:24px 28px;
        margin-bottom:24px;
    }
    .status-card h3 { font-size:0.97rem; font-weight:700; color:#111; margin:0 0 16px; }
    .status-select {
        width:100%; padding:11px 14px; border:1.5px solid #e0e0e0; border-radius:6px;
        font-size:0.9rem; font-family:inherit; color:#333; background:#fafafa;
        transition:border-color 0.2s; margin-bottom:14px; cursor:pointer;
    }
    .status-select:focus { outline:none; border-color:#004e92; background:#fff; }
    .btn-update {
        padding:11px 28px; background:#004e92; color:#fff; border:none;
        border-radius:6px; font-size:0.9rem; font-weight:700; cursor:pointer;
        transition:background 0.2s; letter-spacing:0.03em;
    }
    .btn-update:hover { background:#003a70; }

    .status-badge {
        display:inline-block; padding:5px 14px; border-radius:20px;
        font-size:0.82rem; font-weight:700;
    }
    .status-1 { background:#fff3e0; color:#e65100; }
    .status-2 { background:#e3f2fd; color:#1565c0; }
    .status-3 { background:#f3e5f5; color:#6a1b9a; }
    .status-4 { background:#e8f5e9; color:#2e7d32; }
    .status-5 { background:#fafafa; color:#bbb; border:1px solid #e0e0e0; }

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
    .chat-header-icon { font-size:1.2rem; }
    .chat-header-title { font-size:0.97rem; font-weight:700; }
    .chat-header-sub { font-size:0.75rem; opacity:0.75; margin-left:auto; }

    .chat-log {
        display:flex; flex-direction:column; gap:14px;
        height:360px; overflow-y:auto; padding:20px;
        background:#f8faff;
    }

    /* 各メッセージ行のラッパー */
    .msg-row { display:flex; flex-direction:column; }
    .msg-row.mine  { align-items:flex-end; }
    .msg-row.theirs { align-items:flex-start; }

    /* アバター + バブル横並び */
    .msg-inner { display:flex; align-items:flex-end; gap:8px; max-width:78%; }
    .msg-row.mine  .msg-inner { flex-direction:row-reverse; }

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

    /* 日付区切り */
    .date-divider {
        text-align:center; font-size:0.72rem; color:#bbb;
        position:relative; margin:6px 0;
    }
    .date-divider::before, .date-divider::after {
        content:''; position:absolute; top:50%; width:38%;
        height:1px; background:#e8e8e8;
    }
    .date-divider::before { left:0; }
    .date-divider::after  { right:0; }

    .no-messages {
        text-align:center; color:#bbb; font-size:0.85rem;
        padding:40px 20px; flex:1;
    }

    /* 入力エリア */
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
    .btn-send:hover  { background:#003a70; }
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
<div class="dashboard">
    @php $agent = Auth::guard('agent')->user(); @endphp
    <x-agent-sidebar :agent="$agent" active="inquiries" />

    <main class="main-content">
        <div class="inq-wrap" style="padding-left:0;padding-right:0;">

            <a href="{{ route('agent.inquiries.index') }}" class="back-link">← 問い合わせ一覧に戻る</a>
            <h2 class="page-title">問い合わせ詳細</h2>

            @if (session('status'))
            <div class="alert-success">✅ {{ session('status') }}</div>
            @endif

            {{-- ユーザー情報 --}}
            <div class="user-card">
                <div class="user-avatar">👤</div>
                <div>
                    <div class="user-name">{{ $inquiry->user->name ?? '退会済みユーザー' }}</div>
                    <div class="user-meta">
                        送信日時: {{ $inquiry->created_at->format('Y年n月j日 H:i') }}
                        　現在のステータス:
                        <span class="status-badge status-{{ $inquiry->status }}">
                            {{ $statusLabels[$inquiry->status] ?? '不明' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- 相談内容 --}}
            <div class="detail-card">
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
            </div>

            {{-- 完了メモ表示 --}}
            @if ($inquiry->completion_note)
            <div class="detail-card" style="border-left:4px solid #2e7d32;">
                <div class="detail-row">
                    <div class="detail-label" style="color:#2e7d32;">✅ 成約メモ</div>
                    <div class="detail-value" style="white-space:pre-wrap;">{{ $inquiry->completion_note }}</div>
                </div>
            </div>
            @endif

            {{-- ─── チャット ─── --}}
            <div class="chat-card" id="chat-card">
                <div class="chat-header">
                    <span class="chat-header-icon">💬</span>
                    <span class="chat-header-title">メッセージ</span>
                    <span class="chat-header-sub">{{ $inquiry->messages->count() }} 件</span>
                </div>

                <div class="chat-log" id="chat-log">
                    @forelse ($inquiry->messages as $msg)
                        @php $isMine = $msg->sender_type === 'agent'; @endphp
                        <div class="msg-row {{ $isMine ? 'mine' : 'theirs' }}"
                             data-id="{{ $msg->id }}">
                            <div class="msg-inner">
                                <div class="msg-avatar">
                                    {{ $isMine ? '自' : 'U' }}
                                </div>
                                <div class="msg-bubble">{{ $msg->message }}</div>
                            </div>
                            <div class="msg-meta">
                                {{ $isMine ? 'あなた（エージェント）' : ($inquiry->user->name ?? 'ユーザー') }}
                                　{{ $msg->created_at->format('n/j H:i') }}
                            </div>
                        </div>
                    @empty
                        <div class="no-messages" id="no-msg-placeholder">
                            まだメッセージはありません。<br>最初のメッセージを送ってみましょう。
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
                    <div class="chat-hint">最大 2000 文字 ／ 送信後、ユーザーにメール通知が届きます</div>
                    <div id="chat-error" style="color:#ef4444;font-size:0.8rem;margin-top:6px;display:none;"></div>
                </div>
            </div>

            {{-- ユーザー通報リンク --}}
            @if ($inquiry->user)
            <div style="margin-bottom:24px; text-align:right;">
                <a href="{{ route('agent.report_user.create', $inquiry->user->id) }}" class="btn-report-link">
                    🚨 このユーザーを通報する
                </a>
            </div>
            @endif

            {{-- ステータス変更 --}}
            <div class="status-card">
                <h3>ステータスを更新する</h3>
                <form action="{{ route('agent.inquiries.update_status', $inquiry->id) }}" method="POST" id="status-form">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="status-select" id="status-select"
                            onchange="toggleCompletionNote(this.value)">
                        @foreach ($statusLabels as $val => $label)
                        <option value="{{ $val }}" {{ $inquiry->status === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>

                    {{-- 完了時の成約メモ入力欄 --}}
                    <div id="completion-note-wrap" style="margin-top:16px; display:{{ $inquiry->status === 4 ? 'block' : 'none' }};">
                        <label style="display:block;font-size:0.84rem;font-weight:600;color:#374151;margin-bottom:6px;">
                            成約メモ（任意）
                        </label>
                        <textarea name="completion_note" rows="4"
                                  style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:0.9rem;resize:vertical;"
                                  placeholder="成約内容・提案商品・次のアクションなどを記録してください">{{ old('completion_note', $inquiry->completion_note) }}</textarea>
                        <p style="font-size:0.78rem;color:#9ca3af;margin-top:4px;">※ステータスを「完了」に設定した際のみ保存されます</p>
                    </div>

                    <button type="submit" class="btn-update" style="margin-top:16px;">ステータスを更新する</button>
                </form>
            </div>

        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
function toggleCompletionNote(val) {
    document.getElementById('completion-note-wrap').style.display = (val === '4') ? 'block' : 'none';
}

// ─── チャット Fetch API ───
(function () {
    var log      = document.getElementById('chat-log');
    var textarea = document.getElementById('chat-textarea');
    var btnSend  = document.getElementById('btn-send');
    var errEl    = document.getElementById('chat-error');

    var postUrl  = '{{ route('agent.inquiries.messages.store', $inquiry->id) }}';
    var myName   = '{{ addslashes($agent->name) }}（エージェント）';
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
        var isMine = data.sender_type === 'agent';
        var row = document.createElement('div');
        row.className = 'msg-row ' + (isMine ? 'mine' : 'theirs');
        row.dataset.id = data.id;
        row.innerHTML =
            '<div class="msg-inner">' +
              '<div class="msg-avatar">' + (isMine ? '自' : 'U') + '</div>' +
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

    // 自動リサイズ
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });
})();
</script>
@endpush
