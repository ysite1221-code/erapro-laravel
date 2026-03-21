@extends('layouts.app')

@section('title', '保険募集人の掲載登録 | ERAPRO')

@push('styles')
<style>
    /* ヒーロー */
    .lp-hero { background:linear-gradient(135deg,#0d0d0d,#2a2a2a); color:#fff; text-align:center; padding:100px 24px 90px; }
    .lp-hero .sub { font-size:0.85rem; letter-spacing:0.2em; opacity:0.65; margin-bottom:20px; font-weight:600; }
    .lp-hero h1 { font-size:2.8rem; font-weight:900; line-height:1.25; margin-bottom:20px; letter-spacing:-0.02em; }
    .lp-hero p  { font-size:1rem; opacity:0.75; line-height:1.9; margin-bottom:40px; }
    .btn-lp-cta {
        display:inline-block; background:#fff; color:#1a1a1a; font-weight:700;
        font-size:1rem; padding:16px 52px; border-radius:6px; letter-spacing:0.03em;
        transition:all 0.2s; box-shadow:0 8px 32px rgba(0,0,0,0.18);
    }
    .btn-lp-cta:hover { background:#f2f2f2; transform:translateY(-2px); box-shadow:0 14px 40px rgba(0,0,0,0.22); }

    /* メリットセクション */
    .lp-section { max-width:1040px; margin:96px auto 0; padding:0 28px; }
    .lp-section-title { text-align:center; font-size:1.9rem; font-weight:900; color:#111; margin-bottom:12px; letter-spacing:-0.02em; }
    .lp-section-lead  { text-align:center; font-size:0.95rem; color:#888; margin-bottom:52px; }
    .merit-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:28px; }
    .merit-card { background:#fff; border-radius:8px; box-shadow:0 2px 16px rgba(0,0,0,0.07); padding:36px 28px; }
    .merit-num  { font-size:0.75rem; font-weight:700; color:#1a1a1a; letter-spacing:0.12em; margin-bottom:14px; display:block; }
    .merit-card h3 { font-size:1.2rem; font-weight:900; color:#111; margin-bottom:12px; }
    .merit-card p  { font-size:0.88rem; color:#666; line-height:1.85; margin:0; }

    /* 料金 */
    .plan-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:24px; }
    .plan-card {
        background:#fff; border-radius:8px; box-shadow:0 2px 16px rgba(0,0,0,0.07);
        padding:36px 28px; text-align:center; border:2px solid transparent;
    }
    .plan-card.featured { border-color:#1a1a1a; position:relative; }
    .plan-card.featured::before {
        content:'おすすめ'; position:absolute; top:-14px; left:50%; transform:translateX(-50%);
        background:#1a1a1a; color:#fff; font-size:0.75rem; font-weight:700;
        padding:4px 16px; border-radius:20px; letter-spacing:0.05em;
    }
    .plan-name  { font-size:1.1rem; font-weight:900; color:#111; margin-bottom:8px; }
    .plan-price { font-size:2.4rem; font-weight:900; color:#1a1a1a; line-height:1; margin-bottom:4px; }
    .plan-price span { font-size:0.95rem; font-weight:500; color:#666; }
    .plan-desc  { font-size:0.82rem; color:#888; margin:12px 0 20px; line-height:1.7; }
    .plan-list  { list-style:none; padding:0; text-align:left; font-size:0.85rem; color:#444; }
    .plan-list li { padding:6px 0; border-bottom:1px solid #f0f0f0; }
    .plan-list li::before { content:'✓ '; color:#1a1a1a; font-weight:700; }

    /* ステップ */
    .steps { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:0; counter-reset:step; }
    .step  { padding:32px 24px; text-align:center; position:relative; }
    .step::after { content:'→'; position:absolute; right:-10px; top:50%; transform:translateY(-50%); font-size:1.5rem; color:#d1d5db; }
    .step:last-child::after { display:none; }
    .step-num { width:48px; height:48px; border-radius:50%; background:#1a1a1a; color:#fff; font-size:1.2rem; font-weight:900; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; }
    .step h4 { font-size:1rem; font-weight:700; color:#111; margin-bottom:8px; }
    .step p  { font-size:0.82rem; color:#888; line-height:1.7; margin:0; }

    /* CTA底部 */
    .lp-cta-bottom { max-width:1040px; margin:96px auto 112px; padding:0 28px; }
    .lp-cta-inner  { background:linear-gradient(135deg,#1a1a1a,#0d0d0d); border-radius:8px; padding:64px 48px; text-align:center; color:#fff; }
    .lp-cta-inner h2 { font-size:2rem; font-weight:900; margin-bottom:12px; }
    .lp-cta-inner p  { opacity:0.75; font-size:0.95rem; margin-bottom:36px; }

    /* フッター */
    footer { border-top:1px solid #ebebeb; background:#fff; padding:40px 28px; text-align:center; }
    .footer-inner { max-width:1040px; margin:0 auto; }
    footer p { font-size:0.82rem; color:#aaa; margin:0 0 8px; }
    footer a { font-size:0.82rem; color:#999; }
    footer a:hover { color:#1a1a1a; }
</style>
@endpush

@section('content')

{{-- ヒーロー --}}
<div class="lp-hero">
    <p class="sub">ERAPRO / FOR INSURANCE PROFESSIONALS</p>
    <h1>あなたの「想い」を、<br>ユーザーに届けよう。</h1>
    <p>ERAPROは、保険募集人が自分のストーリーとフィロソフィーで<br>ユーザーと出会えるプラットフォームです。</p>
    <a href="{{ route('agent.register') }}" class="btn-lp-cta">無料で掲載登録する</a>
</div>

{{-- メリット --}}
<div class="lp-section">
    <h2 class="lp-section-title">ERAPROに掲載するメリット</h2>
    <p class="lp-section-lead">商品ではなく「人」で選ばれる時代へ。</p>
    <div class="merit-grid">
        <div class="merit-card">
            <span class="merit-num">01 / PROFILE</span>
            <h3>想いが伝わるプロフィール</h3>
            <p>資格・経歴だけでなく、原体験（My Story）や哲学（Philosophy）を掲載。ユーザーとの深い信頼関係を構築できます。</p>
        </div>
        <div class="merit-card">
            <span class="merit-num">02 / MATCH</span>
            <h3>相性の合うユーザーと出会える</h3>
            <p>タグ検索・AI診断から見つけてもらえるため、あなたの得意領域（子育て・経営者・相続など）に合ったユーザーと繋がれます。</p>
        </div>
        <div class="merit-card">
            <span class="merit-num">03 / REPUTATION</span>
            <h3>クチコミが資産になる</h3>
            <p>実際に相談したユーザーからの評価・コメントが蓄積されます。実績の可視化が、次の顧客獲得につながります。</p>
        </div>
    </div>
</div>

{{-- 料金プラン --}}
<div class="lp-section" style="margin-top:96px;">
    <h2 class="lp-section-title">料金プラン</h2>
    <p class="lp-section-lead">まずは無料プランでお試しいただけます。</p>
    <div class="plan-grid">
        <div class="plan-card">
            <div class="plan-name">フリープラン</div>
            <div class="plan-price">¥0<span>/月</span></div>
            <p class="plan-desc">基本機能を無料で利用できます。</p>
            <ul class="plan-list">
                <li>プロフィール掲載</li>
                <li>問い合わせ受付（月5件まで）</li>
                <li>クチコミ収集</li>
            </ul>
        </div>
        <div class="plan-card featured">
            <div class="plan-name">スタンダードプラン</div>
            <div class="plan-price">¥3,980<span>/月</span></div>
            <p class="plan-desc">本格的な集客・管理に。</p>
            <ul class="plan-list">
                <li>プロフィール掲載（優先表示）</li>
                <li>問い合わせ受付（無制限）</li>
                <li>顧客管理機能</li>
                <li>検索上位表示</li>
                <li>KYC認証バッジ</li>
            </ul>
        </div>
    </div>
</div>

{{-- 登録ステップ --}}
<div class="lp-section" style="margin-top:96px;">
    <h2 class="lp-section-title">登録の流れ</h2>
    <p class="lp-section-lead">最短5分で掲載スタートできます。</p>
    <div class="steps" style="background:#fff;border-radius:8px;box-shadow:0 2px 16px rgba(0,0,0,0.07);">
        <div class="step">
            <div class="step-num">1</div>
            <h4>メール登録</h4>
            <p>メールアドレスとパスワードを入力して仮登録。</p>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <h4>プロフィール作成</h4>
            <p>写真・ストーリー・タグを設定してプロフィールを公開。</p>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <h4>KYC（本人確認）</h4>
            <p>募集人証・身分証を提出して認証バッジを取得。</p>
        </div>
        <div class="step">
            <div class="step-num">4</div>
            <h4>掲載スタート</h4>
            <p>ユーザーからの問い合わせを受け取り、活動開始！</p>
        </div>
    </div>
</div>

{{-- 下部CTA --}}
<div class="lp-cta-bottom">
    <div class="lp-cta-inner">
        <h2>今すぐ、掲載を始めよう。</h2>
        <p>登録は無料。まずはプロフィールを作成してみてください。</p>
        <a href="{{ route('agent.register') }}" class="btn-lp-cta">無料で掲載登録する</a>
    </div>
</div>

@endsection
