@extends('layouts.agent')

@section('title', '本人確認（KYC） - ERAPRO Agent')

@push('styles')
<style>
    .kyc-wrap { max-width:720px; margin:0 auto; padding:32px 28px 80px; }
    .page-title { font-size:1.4rem; font-weight:900; color:#111; margin:0 0 6px; letter-spacing:-0.02em; }
    .page-sub   { font-size:0.85rem; color:#999; margin:0 0 32px; }

    .alert-success {
        background:#e8f5e9; border:1px solid #c8e6c9; color:#2e7d32;
        border-radius:6px; padding:12px 16px; font-size:0.88rem; margin-bottom:24px;
    }
    .alert-error {
        background:#ffebee; border:1px solid #ffcdd2; color:#c62828;
        border-radius:6px; padding:12px 16px; font-size:0.88rem; margin-bottom:24px;
    }

    /* ステップバー */
    .step-bar { display:flex; align-items:flex-start; gap:0; margin-bottom:36px; position:relative; }
    .step-bar::before {
        content:''; position:absolute; top:18px; left:18px;
        width:calc(100% - 36px); height:3px; background:#e0e0e0; z-index:0;
    }
    .kyc-step { flex:1; text-align:center; position:relative; z-index:1; }
    .kyc-step-circle {
        width:36px; height:36px; border-radius:50%; border:3px solid #e0e0e0;
        background:#fff; display:flex; align-items:center; justify-content:center;
        font-size:0.85rem; font-weight:700; color:#ccc; margin:0 auto 8px;
    }
    .kyc-step-label { font-size:0.72rem; color:#aaa; line-height:1.4; }
    .kyc-step.done .kyc-step-circle  { background:#2e7d32; border-color:#2e7d32; color:#fff; }
    .kyc-step.current .kyc-step-circle { background:#fff; border-color:#004e92; color:#004e92; box-shadow:0 0 0 4px rgba(0,78,146,0.12); }
    .kyc-step.done .kyc-step-label { color:#2e7d32; font-weight:600; }
    .kyc-step.current .kyc-step-label { color:#004e92; font-weight:700; }

    /* ステータスカード */
    .status-card {
        border-radius:12px; padding:24px 28px; margin-bottom:28px;
        display:flex; align-items:flex-start; gap:16px;
    }
    .status-card.pending  { background:#e3f2fd; border:1.5px solid #90caf9; }
    .status-card.approved { background:#e8f5e9; border:1.5px solid #a5d6a7; }
    .status-card.rejected { background:#ffebee; border:1.5px solid #ef9a9a; }
    .status-card.none     { background:#fff8e1; border:1.5px solid #ffe082; }
    .status-card-icon { font-size:1.8rem; flex-shrink:0; margin-top:2px; }
    .status-card-title { font-size:1rem; font-weight:700; margin-bottom:6px; }
    .status-card.pending  .status-card-title { color:#1565c0; }
    .status-card.approved .status-card-title { color:#2e7d32; }
    .status-card.rejected .status-card-title { color:#c62828; }
    .status-card.none     .status-card-title { color:#e65100; }
    .status-card-body { font-size:0.875rem; color:#555; line-height:1.7; }

    /* フォームカード */
    .form-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0;
        padding:28px 32px; margin-bottom:24px;
    }
    .form-card h3 { font-size:1rem; font-weight:900; color:#111; margin:0 0 8px; }
    .form-card p  { font-size:0.85rem; color:#888; margin:0 0 20px; line-height:1.7; }

    .form-group { margin-bottom:20px; }
    .form-label { display:block; font-size:0.84rem; font-weight:700; color:#555; margin-bottom:6px; }
    .form-label .req { color:#e91e63; margin-left:4px; font-size:0.75rem; }
    .form-control {
        width:100%; padding:11px 14px; border:1.5px solid #e0e0e0; border-radius:6px;
        font-size:0.9rem; font-family:inherit; color:#333; background:#fafafa;
        transition:border-color 0.2s; box-sizing:border-box;
    }
    .form-control:focus { outline:none; border-color:#004e92; background:#fff; }
    .invalid-feedback { color:#e91e63; font-size:0.78rem; margin-top:4px; display:block; }
    .url-hint { font-size:0.77rem; color:#aaa; margin-top:6px; }

    /* 注意事項 */
    .notice-box {
        background:#f8f9ff; border:1.5px solid #d0d8ee; border-radius:8px;
        padding:16px 20px; margin-bottom:24px;
    }
    .notice-box h4 { font-size:0.85rem; font-weight:700; color:#004e92; margin:0 0 8px; }
    .notice-box ul { margin:0; padding:0 0 0 18px; }
    .notice-box li { font-size:0.82rem; color:#555; line-height:1.8; }

    .btn-submit {
        width:100%; padding:14px; background:#004e92; color:#fff; border:none;
        border-radius:8px; font-size:0.95rem; font-weight:700; cursor:pointer;
        letter-spacing:0.04em; transition:background 0.2s;
    }
    .btn-submit:hover { background:#003a70; }
    .btn-submit:disabled { background:#9ebbe0; cursor:not-allowed; }
</style>
@endpush

@section('content')
<div class="dashboard">
    <aside class="sidebar">
        <img src="{{ $agent->profile_img ? asset('storage/' . $agent->profile_img) : 'https://placehold.co/150x150/e0e0e0/888?text=No+Img' }}"
             class="sidebar-avatar" alt="プロフィール">
        <ul>
            <li><a href="{{ route('agent.dashboard') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">dashboard</span>ダッシュボード
            </a></li>
            <li><a href="{{ route('agent.profile.edit') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">person</span>プロフィール編集
            </a></li>
            <li><a href="{{ route('agent.inquiries.index') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">chat</span>問い合わせ管理
            </a></li>
            <li><a href="#" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">people</span>顧客リスト
            </a></li>
            <li><a href="{{ route('agent.kyc.form') }}" class="sidebar-link active">
                <span class="material-icons-outlined sidebar-icon">verified_user</span>本人確認（KYC）
            </a></li>
        </ul>
        <a href="{{ route('agent.profile', $agent->id) }}" target="_blank" class="sidebar-public-btn">自分の公開ページを見る</a>
    </aside>

    <main class="main-content">
        <div class="kyc-wrap" style="padding-left:0;padding-right:0;">

            <h2 class="page-title">本人確認（KYC）</h2>
            <p class="page-sub">所属先・募集人登録情報のURLを提出してください。審査完了後にプロフィールが公開されます。</p>

            @if (session('status'))
            <div class="alert-success">✅ {{ session('status') }}</div>
            @endif

            @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $err){{ $err }}@endforeach
            </div>
            @endif

            {{-- ステップバー --}}
            @php
                $vs = $agent->verification_status; // 0:未提出 1:審査待ち 2:承認済 9:否認
                $step1Done    = in_array($vs, [1, 2]);
                $step1Current = $vs === 0 || $vs === 9;
                $step2Done    = $vs === 2;
                $step2Current = $vs === 1;
            @endphp
            <div class="step-bar">
                <div class="kyc-step {{ $step1Done ? 'done' : ($step1Current ? 'current' : '') }}">
                    <div class="kyc-step-circle">{{ $step1Done ? '✓' : '1' }}</div>
                    <div class="kyc-step-label">URL提出</div>
                </div>
                <div class="kyc-step {{ $step2Done ? 'done' : ($step2Current ? 'current' : '') }}">
                    <div class="kyc-step-circle">{{ $step2Done ? '✓' : '2' }}</div>
                    <div class="kyc-step-label">審査中</div>
                </div>
                <div class="kyc-step {{ $step2Done ? 'done' : '' }}">
                    <div class="kyc-step-circle">{{ $step2Done ? '✓' : '3' }}</div>
                    <div class="kyc-step-label">承認・公開</div>
                </div>
            </div>

            {{-- ステータスメッセージ --}}
            @if ($vs === 1)
            <div class="status-card pending">
                <div class="status-card-icon">⏳</div>
                <div>
                    <div class="status-card-title">審査中です</div>
                    <div class="status-card-body">
                        URLを受け付けました。運営チームが審査中です。通常2〜5営業日以内にご連絡します。<br>
                        提出済みURL: <code style="background:#e3f2fd;padding:2px 6px;border-radius:4px;">{{ $agent->affiliation_url }}</code>
                    </div>
                </div>
            </div>

            @elseif ($vs === 2)
            <div class="status-card approved">
                <div class="status-card-icon">✅</div>
                <div>
                    <div class="status-card-title">本人確認が完了しています</div>
                    <div class="status-card-body">
                        審査が承認されました。プロフィールは公開中です。<br>
                        <a href="{{ route('agent.profile', $agent->id) }}" target="_blank"
                           style="color:#2e7d32;font-weight:600;">自分の公開ページを確認する →</a>
                    </div>
                </div>
            </div>

            @elseif ($vs === 9)
            <div class="status-card rejected">
                <div class="status-card-icon">❌</div>
                <div>
                    <div class="status-card-title">審査が否認されました</div>
                    <div class="status-card-body">
                        提出されたURLでは募集人登録情報を確認できませんでした。<br>
                        正しいURLに修正して再提出してください。<br>
                        @if ($agent->affiliation_url)
                        前回のURL: <code style="background:#ffebee;padding:2px 6px;border-radius:4px;">{{ $agent->affiliation_url }}</code>
                        @endif
                    </div>
                </div>
            </div>

            @else
            <div class="status-card none">
                <div class="status-card-icon">🔐</div>
                <div>
                    <div class="status-card-title">本人確認が未提出です</div>
                    <div class="status-card-body">
                        プロフィールを公開するには本人確認が必要です。<br>
                        下記のフォームから所属先のURLを提出してください。
                    </div>
                </div>
            </div>
            @endif

            {{-- フォーム（承認済みは非表示） --}}
            @if ($vs !== 2)
            <div class="notice-box">
                <h4>📌 提出するURLについて</h4>
                <ul>
                    <li>所属保険代理店・生命保険会社の公式サイト上に、あなたのお名前・募集人番号が掲載されているページのURLを入力してください。</li>
                    <li>金融庁「保険募集人情報検索サービス」のURLも使用可能です。</li>
                    <li>URLは <strong>https://</strong> から始まる公開URLである必要があります。</li>
                    <li>審査結果はメールにてお知らせします。</li>
                </ul>
            </div>

            <div class="form-card">
                <h3>募集人登録情報 URL</h3>
                <p>所属先または金融庁のサイトで、あなたの募集人登録が確認できるページのURLを入力してください。</p>
                <form action="{{ route('agent.kyc.submit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="affiliation_url">
                            登録情報確認URL<span class="req">必須</span>
                        </label>
                        <input type="url" id="affiliation_url" name="affiliation_url" class="form-control"
                               value="{{ old('affiliation_url', $agent->affiliation_url) }}"
                               placeholder="https://www.example.com/agent/taro-yamada"
                               required>
                        <p class="url-hint">例: https://www.your-agency.co.jp/staff/yamada</p>
                        @error('affiliation_url')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="btn-submit">
                        {{ $vs === 9 ? '再提出する' : ($vs === 1 ? 'URLを更新して再提出する' : '提出する') }}
                    </button>
                </form>
            </div>
            @endif

        </div>
    </main>
</div>
@endsection
