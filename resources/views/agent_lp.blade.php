@extends('layouts.app')

@section('title', '保険Agentの掲載登録 | ERAPRO')

@push('styles')
<style>
    /* Agent LP: white-base + monochrome accent */
    /* ヘッダー: #111 (ほぼ純黒) でロゴの黒背景と一致させ、浮きを解消 */
    header { background: #111 !important; border-bottom: none !important; box-shadow: none !important; }
    main   { padding-top: 0 !important; margin-top: 0 !important; }

    /* ヒーロー: ヘッダーと同系の濃チャコールでシームレスに接続 */
    .lp-hero { background: linear-gradient(160deg, #111 0%, #2a2a2a 100%); color: #fff; text-align: center; padding: 100px 24px 96px; }
    .lp-hero .sub { font-size: 0.8rem; letter-spacing: 0.25em; opacity: 0.5; margin-bottom: 20px; font-weight: 600; }
    .lp-hero h1 { font-size: 2.8rem; font-weight: 900; line-height: 1.25; margin-bottom: 20px; letter-spacing: -0.02em; }
    .lp-hero p  { font-size: 1rem; opacity: 0.72; line-height: 1.9; margin-bottom: 40px; }
    .btn-lp-cta {
        display: inline-block; background: #fff; color: #111; font-weight: 700;
        font-size: 1rem; padding: 16px 52px; border-radius: 6px; letter-spacing: 0.03em;
        transition: all 0.2s; box-shadow: 0 8px 32px rgba(0,0,0,0.22);
    }
    .btn-lp-cta:hover { background: #f2f2f2; transform: translateY(-2px); box-shadow: 0 14px 40px rgba(0,0,0,0.3); }

    /* 白ベースのコンテンツエリア */
    .lp-section { max-width: 1040px; margin: 96px auto 0; padding: 0 28px; }
    .lp-section-title { text-align: center; font-size: 1.9rem; font-weight: 900; color: #111; margin-bottom: 12px; letter-spacing: -0.02em; }
    .lp-section-lead  { text-align: center; font-size: 0.95rem; color: #888; margin-bottom: 52px; }
    .merit-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
    .merit-card { background: #fff; border-radius: 8px; border: 1px solid #e8e8e8; padding: 36px 28px; }
    .merit-num  { font-size: 0.72rem; font-weight: 700; color: #aaa; letter-spacing: 0.18em; margin-bottom: 14px; display: block; }
    .merit-card h3 { font-size: 1.15rem; font-weight: 900; color: #111; margin-bottom: 12px; }
    .merit-card p  { font-size: 0.88rem; color: #666; line-height: 1.85; margin: 0; }

    /* 料金 */
    .plan-single { max-width: 480px; margin: 0 auto; }
    .plan-card { background: #fff; border-radius: 8px; border: 1px solid #e8e8e8; padding: 40px 36px; text-align: center; }
    .plan-name  { font-size: 1.1rem; font-weight: 900; color: #111; margin-bottom: 8px; }
    .plan-price { font-size: 2.4rem; font-weight: 900; color: #111; line-height: 1; margin-bottom: 4px; }
    .plan-price span { font-size: 0.95rem; font-weight: 400; color: #888; }
    .plan-desc  { font-size: 0.82rem; color: #888; margin: 12px 0 20px; line-height: 1.7; }
    .plan-list  { list-style: none; padding: 0; text-align: left; font-size: 0.85rem; color: #444; }
    .plan-list li { padding: 7px 0; border-bottom: 1px solid #f0f0f0; }
    .plan-list li::before { content: '✓ '; color: #111; font-weight: 700; }

    /* ステップ */
    .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0; }
    .step  { padding: 32px 24px; text-align: center; position: relative; }
    .step::after { content: '→'; position: absolute; right: -10px; top: 50%; transform: translateY(-50%); font-size: 1.4rem; color: #d4d4d4; }
    .step:last-child::after { display: none; }
    .step-num { width: 48px; height: 48px; border-radius: 50%; background: #111; color: #fff; font-size: 1.2rem; font-weight: 900; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
    .step h4 { font-size: 1rem; font-weight: 700; color: #111; margin-bottom: 8px; }
    .step p  { font-size: 0.82rem; color: #888; line-height: 1.7; margin: 0; }

    /* CTA底部: 黒アクセントバナー */
    .lp-cta-bottom { max-width: 1040px; margin: 96px auto 112px; padding: 0 28px; }
    .lp-cta-inner  { background: #111; border-radius: 10px; padding: 64px 48px; text-align: center; color: #fff; }
    .lp-cta-inner h2 { font-size: 2rem; font-weight: 900; margin-bottom: 12px; }
    .lp-cta-inner p  { opacity: 0.7; font-size: 0.95rem; margin-bottom: 36px; }

    /* フッター */
    footer a:hover { color: #111 !important; }
</style>
@endpush

@section('content')

{{-- ヒーロー --}}
<div class="lp-hero">
    <p class="sub">ERAPRO / FOR INSURANCE PROFESSIONALS</p>
    <h1>あなたの「想い」を、<br>ユーザーに届けよう。</h1>
    <p>ERAPROは、保険Agentが自分のストーリーとフィロソフィーで<br>ユーザーと出会えるプラットフォームです。</p>
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
    <h2 class="lp-section-title">ご利用料金</h2>
    <p class="lp-section-lead">現在は無料で掲載・相談受付をご利用いただけます。</p>
    <div class="plan-single">
        <div class="plan-card">
            <div class="plan-name">基本プラン</div>
            <div class="plan-price">¥0<span>/月</span></div>
            <p class="plan-desc">サービス立ち上げ期間中につき、基本機能を無料でご利用いただけます。</p>
            <ul class="plan-list">
                <li>プロフィール掲載</li>
                <li>問い合わせ受付</li>
                <li>クチコミ収集</li>
                <li>顧客管理機能</li>
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
            <p>Agent証・身分証を提出して認証バッジを取得。</p>
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
