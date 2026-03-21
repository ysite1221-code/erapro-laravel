@extends('layouts.agent')

@section('title', 'プロフィール編集 - ERAPRO Agent')

@push('styles')
<style>
    .edit-wrap { max-width:860px; margin:0 auto; padding:32px 28px 80px; }
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

    .section-card {
        background:#fff; border-radius:10px; border:1.5px solid #f0f0f0;
        padding:28px 32px; margin-bottom:24px;
    }
    .section-card h3 {
        font-size:1rem; font-weight:900; color:#111; margin:0 0 20px;
        padding-bottom:12px; border-bottom:2px solid #f5f5f5;
    }

    .form-row { display:grid; gap:20px; margin-bottom:20px; }
    .form-row.col2 { grid-template-columns:1fr 1fr; }
    .form-row.col1 { grid-template-columns:1fr; }

    .form-group { display:flex; flex-direction:column; gap:6px; }
    .form-label { font-size:0.84rem; font-weight:700; color:#555; }
    .form-label .req { color:#e91e63; margin-left:4px; font-size:0.75rem; }
    .form-label .hint { color:#aaa; font-weight:400; margin-left:8px; font-size:0.75rem; }

    .form-control {
        padding:11px 14px; border:1.5px solid #e0e0e0; border-radius:6px;
        font-size:0.9rem; font-family:inherit; color:#333; background:#fafafa;
        transition:border-color 0.2s; box-sizing:border-box; width:100%;
    }
    .form-control:focus { outline:none; border-color:#004e92; background:#fff; }
    textarea.form-control { resize:vertical; min-height:130px; line-height:1.8; }
    select.form-control { cursor:pointer; }

    .invalid-feedback { color:#e91e63; font-size:0.78rem; margin-top:2px; }

    /* アバタープレビュー */
    .avatar-row { display:flex; align-items:flex-start; gap:24px; }
    .avatar-preview {
        width:96px; height:96px; border-radius:50%; object-fit:cover;
        background:#eee; border:3px solid #f0f0f0; flex-shrink:0;
    }
    .avatar-file-wrap { flex:1; }
    .file-input-label {
        display:inline-block; padding:10px 20px; background:#f0f4ff;
        border:1.5px solid #c5d3f0; border-radius:6px; cursor:pointer;
        font-size:0.85rem; color:#004e92; font-weight:600; transition:all 0.2s;
    }
    .file-input-label:hover { background:#e0e8ff; }
    .file-input-hint { font-size:0.76rem; color:#aaa; margin-top:6px; }

    /* タグ入力補助 */
    .tags-hint { font-size:0.76rem; color:#aaa; margin-top:4px; }

    /* KYCバナー */
    .kyc-banner {
        background:linear-gradient(135deg, #fff8e1, #fff3cd);
        border:1.5px solid #ffd54f; border-radius:8px;
        padding:14px 20px; display:flex; align-items:center; gap:14px;
        margin-bottom:24px; text-decoration:none;
    }
    .kyc-banner-icon { font-size:1.6rem; flex-shrink:0; }
    .kyc-banner-text { flex:1; }
    .kyc-banner-text strong { font-size:0.9rem; color:#555; display:block; margin-bottom:2px; }
    .kyc-banner-text span { font-size:0.8rem; color:#888; }
    .kyc-banner-arrow { font-size:0.9rem; color:#f9a825; font-weight:700; }

    /* 送信ボタン */
    .form-actions { display:flex; gap:12px; align-items:center; justify-content:flex-end; margin-top:8px; }
    .btn-submit {
        padding:13px 36px; background:#004e92; color:#fff; border:none;
        border-radius:6px; font-size:0.95rem; font-weight:700; cursor:pointer;
        letter-spacing:0.04em; transition:background 0.2s;
    }
    .btn-submit:hover { background:#003a70; }
    .btn-cancel {
        padding:13px 24px; background:#fff; color:#888;
        border:1.5px solid #e0e0e0; border-radius:6px; font-size:0.9rem;
        text-decoration:none; transition:all 0.2s;
    }
    .btn-cancel:hover { border-color:#999; color:#555; }

    @media (max-width:600px) {
        .form-row.col2 { grid-template-columns:1fr; }
        .avatar-row { flex-direction:column; }
    }
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
            <li><a href="{{ route('agent.profile.edit') }}" class="sidebar-link active">
                <span class="material-icons-outlined sidebar-icon">person</span>プロフィール編集
            </a></li>
            <li><a href="{{ route('agent.inquiries.index') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">chat</span>問い合わせ管理
            </a></li>
            <li><a href="#" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">people</span>顧客リスト
            </a></li>
            <li><a href="{{ route('agent.kyc.form') }}" class="sidebar-link">
                <span class="material-icons-outlined sidebar-icon">verified_user</span>本人確認（KYC）
            </a></li>
        </ul>
        <a href="{{ route('agent.profile', $agent->id) }}" target="_blank" class="sidebar-public-btn">自分の公開ページを見る</a>
    </aside>

    <main class="main-content">
        <div class="edit-wrap" style="padding-left:0;padding-right:0;">

            <h2 class="page-title">プロフィール編集</h2>
            <p class="page-sub">あなたのプロフィールを充実させてユーザーに魅力を伝えましょう。</p>

            @if (session('status'))
            <div class="alert-success">✅ {{ session('status') }}</div>
            @endif

            @if ($errors->any())
            <div class="alert-error">
                <strong>入力内容にエラーがあります：</strong>
                <ul style="margin:6px 0 0 16px;padding:0;">
                    @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if ($agent->verification_status !== 2)
            <a href="{{ route('agent.kyc.form') }}" class="kyc-banner">
                <span class="kyc-banner-icon">🔐</span>
                <div class="kyc-banner-text">
                    <strong>本人確認（KYC）が未完了です</strong>
                    <span>所属先・募集人登録情報のURLを提出することで、プロフィールが公開されます。</span>
                </div>
                <span class="kyc-banner-arrow">→</span>
            </a>
            @endif

            <form action="{{ route('agent.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- 基本情報 --}}
                <div class="section-card">
                    <h3>👤 基本情報</h3>

                    <div class="form-row col1">
                        <div class="form-group">
                            <label class="form-label">プロフィール写真</label>
                            <div class="avatar-row">
                                <img id="avatarPreview"
                                     src="{{ $agent->profile_img ? asset('storage/' . $agent->profile_img) : 'https://placehold.co/96x96/e0e0e0/888?text=No+Img' }}"
                                     class="avatar-preview" alt="現在のプロフィール写真">
                                <div class="avatar-file-wrap">
                                    <label for="profile_img" class="file-input-label">📷 写真を変更する</label>
                                    <input type="file" id="profile_img" name="profile_img"
                                           accept="image/*" style="display:none;"
                                           onchange="previewImage(this)">
                                    <p class="file-input-hint">JPEG / PNG / WebP・5MB以内</p>
                                    @error('profile_img')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row col2">
                        <div class="form-group">
                            <label class="form-label" for="name">氏名<span class="req">必須</span></label>
                            <input type="text" id="name" name="name" class="form-control"
                                   value="{{ old('name', $agent->name) }}" required>
                            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">メールアドレス<span class="req">必須</span></label>
                            <input type="email" id="email" name="email" class="form-control"
                                   value="{{ old('email', $agent->email) }}" required>
                            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="form-row col1">
                        <div class="form-group">
                            <label class="form-label" for="title">
                                キャッチコピー
                                <span class="hint">検索カードのタイトルに表示されます（例: 子育て世代の保険見直しを徹底サポート）</span>
                            </label>
                            <input type="text" id="title" name="title" class="form-control"
                                   value="{{ old('title', $agent->title) }}" maxlength="255">
                            @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                {{-- 活動エリア --}}
                <div class="section-card">
                    <h3>📍 活動エリア</h3>
                    <div class="form-row col2">
                        <div class="form-group">
                            <label class="form-label" for="area">都道府県</label>
                            <select id="area" name="area" class="form-control">
                                <option value="">選択してください</option>
                                @foreach ($prefectures as $pref)
                                <option value="{{ $pref }}" {{ old('area', $agent->area) === $pref ? 'selected' : '' }}>
                                    {{ $pref }}
                                </option>
                                @endforeach
                            </select>
                            @error('area')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="area_detail">
                                詳細エリア
                                <span class="hint">例: 渋谷区, 新宿区, 港区</span>
                            </label>
                            <input type="text" id="area_detail" name="area_detail" class="form-control"
                                   value="{{ old('area_detail', $agent->area_detail) }}" maxlength="255"
                                   placeholder="市区町村をカンマ区切りで">
                            @error('area_detail')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                {{-- 専門・タグ --}}
                <div class="section-card">
                    <h3>🏷️ 専門分野・タグ</h3>
                    <div class="form-group">
                        <label class="form-label" for="tags">
                            専門タグ
                            <span class="hint">検索やマッチングに使用されます</span>
                        </label>
                        <input type="text" id="tags" name="tags" class="form-control"
                               value="{{ old('tags', $agent->tags) }}" maxlength="500"
                               placeholder="例: 子育て, 相続, 節税, 老後, 資産形成">
                        <p class="tags-hint">カンマ（,）で区切って複数入力できます</p>
                        @error('tags')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- 自己紹介 --}}
                <div class="section-card">
                    <h3>✍️ 自己紹介文</h3>
                    <div class="form-row col1">
                        <div class="form-group">
                            <label class="form-label" for="story">
                                My Story（原体験）
                                <span class="hint">保険の仕事を始めた動機や印象的なエピソード</span>
                            </label>
                            <textarea id="story" name="story" class="form-control" maxlength="3000"
                                      style="min-height:160px;">{{ old('story', $agent->story) }}</textarea>
                            @error('story')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="philosophy">
                                Philosophy（仕事の哲学・信念）
                                <span class="hint">あなたが大切にしていることや仕事への姿勢</span>
                            </label>
                            <textarea id="philosophy" name="philosophy" class="form-control" maxlength="3000"
                                      style="min-height:160px;">{{ old('philosophy', $agent->philosophy) }}</textarea>
                            @error('philosophy')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('agent.dashboard') }}" class="btn-cancel">キャンセル</a>
                    <button type="submit" class="btn-submit">変更を保存する</button>
                </div>
            </form>

        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
