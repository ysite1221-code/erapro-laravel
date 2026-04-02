<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>保険AgentのためのプラットフォームEROPRO | ERAPRO for Agent</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Noto Sans JP', sans-serif; }
        .feature-card { transition: transform 0.2s, box-shadow 0.2s; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

{{-- ========== ナビゲーション（ダーク） ========== --}}
<nav class="sticky top-0 z-50 bg-[#111] border-b border-white/10">
    <div class="max-w-6xl mx-auto px-5 h-16 flex items-center justify-between">
        <a href="{{ route('agent.lp') }}">
            <img src="{{ asset('img/logo_white.png') }}" alt="ERAPRO for Agent" class="h-7 object-contain">
        </a>
        <div class="flex items-center gap-4">
            <a href="{{ url('/') }}" class="hidden sm:block text-sm text-white/60 hover:text-white transition-colors">保険の相談をしたい方はこちら</a>
            @if(Auth::guard('agent')->check())
                <a href="{{ route('agent.dashboard') }}" class="text-sm text-white/80 font-semibold hover:text-white transition-colors">マイページ</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-white/50 hover:text-white transition-colors">ログアウト</button>
                </form>
            @else
                <a href="{{ route('agent.login') }}" class="text-sm text-white font-semibold border border-white/30 px-4 py-2 rounded-full hover:bg-white/10 transition-all">
                    ログイン
                </a>
            @endif
        </div>
    </div>
</nav>

{{-- ========== ヒーロー ========== --}}
<section class="bg-[#111] text-white py-28 sm:py-36 px-5">
    <div class="max-w-3xl mx-auto text-center">
        <p class="text-white/40 text-xs font-bold tracking-[0.25em] mb-6 uppercase">ERAPRO / For Insurance Professionals</p>
        <h1 class="text-4xl sm:text-6xl font-black leading-tight mb-6 tracking-tight">
            あなたの「想い」を、<br>顧客との<span class="text-white/70">出会い</span>に。
        </h1>
        <p class="text-white/60 text-base sm:text-lg leading-relaxed mb-12 max-w-xl mx-auto">
            ERAPROは、保険Agentが自分のストーリーとフィロソフィーでユーザーと出会えるプラットフォームです。商品ではなく「人」で選ばれる時代へ。
        </p>
        <a href="{{ route('agent.register') }}" class="inline-block bg-white text-gray-900 font-bold text-base px-10 py-4 rounded-full shadow-xl hover:shadow-2xl hover:-translate-y-0.5 transition-all">
            無料で掲載登録する →
        </a>
        <p class="text-white/30 text-xs mt-5">登録無料 &nbsp;•&nbsp; 審査あり &nbsp;•&nbsp; KYC本人確認</p>
    </div>
</section>

{{-- ========== ビジョン ========== --}}
<section class="py-20 sm:py-28 px-5 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-gray-400 text-xs font-bold tracking-widest mb-4 uppercase">Our Vision</p>
                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight mb-6">
                    なぜ、ERAPROなのか。
                </h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                    保険業界には、良いAgentほど埋もれてしまうという構造的な課題があります。資格や実績は伝わっても、「その人らしさ」や「想い」は伝わりにくい。
                </p>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                    ERAPROは、Agentの原体験・哲学・口コミが積み上がる場所です。あなたの得意なクライアント像に合ったユーザーと、自然に出会える仕組みを提供します。
                </p>
                <p class="text-gray-900 text-sm font-bold">
                    「人で選ばれる」Agent になるための、プラットフォームです。
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-2xl p-6 text-center">
                    <div class="text-3xl font-black text-gray-900 mb-1">#1</div>
                    <div class="text-xs text-gray-500">保険Agent特化型プラットフォーム</div>
                </div>
                <div class="bg-gray-50 rounded-2xl p-6 text-center">
                    <div class="text-3xl font-black text-gray-900 mb-1">KYC</div>
                    <div class="text-xs text-gray-500">全Agent本人確認済み</div>
                </div>
                <div class="bg-gray-50 rounded-2xl p-6 text-center">
                    <div class="text-3xl font-black text-gray-900 mb-1">無料</div>
                    <div class="text-xs text-gray-500">立ち上げ期間は0円で利用可</div>
                </div>
                <div class="bg-gray-50 rounded-2xl p-6 text-center">
                    <div class="text-3xl font-black text-gray-900 mb-1">AI</div>
                    <div class="text-xs text-gray-500">診断でマッチング</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== 参画のメリット ========== --}}
<section class="py-20 px-5 bg-gray-50">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-14">
            <p class="text-gray-400 text-xs font-bold tracking-widest mb-3 uppercase">Benefits</p>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-3">ERAPROに掲載するメリット</h2>
            <p class="text-gray-500 text-sm">商品ではなく「人」で選ばれる時代へ。</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl p-8 shadow-sm feature-card">
                <div class="w-10 h-10 bg-gray-900 text-white rounded-xl flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <p class="text-xs text-gray-400 font-bold tracking-widest mb-3 uppercase">01 / Profile</p>
                <h3 class="font-bold text-gray-900 text-base mb-3">想いが伝わるプロフィール</h3>
                <p class="text-sm text-gray-500 leading-relaxed">資格・経歴だけでなく、原体験（My Story）や哲学（Philosophy）を掲載。ユーザーとの深い信頼関係を構築できます。</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm feature-card">
                <div class="w-10 h-10 bg-gray-900 text-white rounded-xl flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.62 3.24 2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.27a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <p class="text-xs text-gray-400 font-bold tracking-widest mb-3 uppercase">02 / Match</p>
                <h3 class="font-bold text-gray-900 text-base mb-3">相性の合うユーザーと出会える</h3>
                <p class="text-sm text-gray-500 leading-relaxed">タグ検索・AI診断から見つけてもらえるため、あなたの得意領域（子育て・経営者・相続など）に合ったユーザーと繋がれます。</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm feature-card">
                <div class="w-10 h-10 bg-gray-900 text-white rounded-xl flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <p class="text-xs text-gray-400 font-bold tracking-widest mb-3 uppercase">03 / Reputation</p>
                <h3 class="font-bold text-gray-900 text-base mb-3">口コミが資産になる</h3>
                <p class="text-sm text-gray-500 leading-relaxed">実際に相談したユーザーからの評価・コメントが蓄積されます。実績の可視化が、次の顧客獲得につながります。</p>
            </div>
        </div>
    </div>
</section>

{{-- ========== 機能紹介 ========== --}}
<section class="py-20 px-5 bg-white">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-14">
            <p class="text-gray-400 text-xs font-bold tracking-widest mb-3 uppercase">Features</p>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-3">業務を支えるツール</h2>
            <p class="text-gray-500 text-sm">プロフィール公開から顧客管理まで、一つのダッシュボードで完結します。</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="border border-gray-100 rounded-2xl p-7 hover:border-gray-300 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-50 text-blue-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">ダッシュボード</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">相談件数・閲覧数・口コミ評価を一覧で確認。自分のプロフィールの状況がひと目でわかります。</p>
                    </div>
                </div>
            </div>
            <div class="border border-gray-100 rounded-2xl p-7 hover:border-gray-300 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-50 text-green-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">メッセージ機能</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">ユーザーからの相談リクエストをチャット形式で管理。スムーズなやり取りで信頼関係を構築できます。</p>
                    </div>
                </div>
            </div>
            <div class="border border-gray-100 rounded-2xl p-7 hover:border-gray-300 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-yellow-50 text-yellow-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">KYC認証バッジ</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">本人確認と募集人資格の確認が完了すると認証バッジを取得。ユーザーへの信頼性が高まります。</p>
                    </div>
                </div>
            </div>
            <div class="border border-gray-100 rounded-2xl p-7 hover:border-gray-300 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-purple-50 text-purple-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">顧客管理・口コミ収集</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">相談履歴と口コミが自動で蓄積。あなたの実績が可視化され、次のユーザー獲得に活きます。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== 料金 ========== --}}
<section class="py-20 px-5 bg-gray-50">
    <div class="max-w-md mx-auto text-center">
        <p class="text-gray-400 text-xs font-bold tracking-widest mb-3 uppercase">Pricing</p>
        <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-3">ご利用料金</h2>
        <p class="text-gray-500 text-sm mb-10">現在は無料で掲載・相談受付をご利用いただけます。</p>
        <div class="bg-white border border-gray-200 rounded-2xl p-10 shadow-sm">
            <div class="text-sm font-bold text-gray-500 mb-2">基本プラン</div>
            <div class="text-6xl font-black text-gray-900 mb-1">¥0</div>
            <div class="text-gray-400 text-sm mb-6">/ 月</div>
            <p class="text-sm text-gray-500 mb-8 leading-relaxed">サービス立ち上げ期間中につき、基本機能を無料でご利用いただけます。</p>
            <ul class="text-left text-sm text-gray-600 space-y-3 mb-8">
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                    プロフィール掲載
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                    問い合わせ受付・メッセージ
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                    口コミ収集・顧客管理
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                    KYC認証バッジ取得
                </li>
            </ul>
            <a href="{{ route('agent.register') }}" class="block w-full bg-gray-900 text-white font-bold py-4 rounded-xl hover:bg-gray-700 transition-colors text-center">
                無料で始める →
            </a>
        </div>
    </div>
</section>

{{-- ========== 登録の流れ ========== --}}
<section class="py-20 px-5 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-14">
            <p class="text-gray-400 text-xs font-bold tracking-widest mb-3 uppercase">How to Start</p>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-3">登録の流れ</h2>
            <p class="text-gray-500 text-sm">最短5分で掲載スタートできます。</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
            <div class="text-center">
                <div class="w-12 h-12 bg-gray-900 text-white rounded-full flex items-center justify-center text-lg font-black mx-auto mb-4">1</div>
                <h4 class="font-bold text-gray-800 text-sm mb-2">メール登録</h4>
                <p class="text-xs text-gray-500 leading-relaxed">メールアドレスとパスワードを入力して仮登録。</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-gray-900 text-white rounded-full flex items-center justify-center text-lg font-black mx-auto mb-4">2</div>
                <h4 class="font-bold text-gray-800 text-sm mb-2">プロフィール作成</h4>
                <p class="text-xs text-gray-500 leading-relaxed">写真・ストーリー・タグを設定してプロフィールを公開。</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-gray-900 text-white rounded-full flex items-center justify-center text-lg font-black mx-auto mb-4">3</div>
                <h4 class="font-bold text-gray-800 text-sm mb-2">KYC（本人確認）</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Agent証・身分証を提出して認証バッジを取得。</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-gray-900 text-white rounded-full flex items-center justify-center text-lg font-black mx-auto mb-4">4</div>
                <h4 class="font-bold text-gray-800 text-sm mb-2">掲載スタート</h4>
                <p class="text-xs text-gray-500 leading-relaxed">ユーザーからの問い合わせを受け取り、活動開始！</p>
            </div>
        </div>
    </div>
</section>

{{-- ========== 下部CTA ========== --}}
<section class="py-20 px-5 bg-[#111] text-white">
    <div class="max-w-2xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-black mb-4">今すぐ、掲載を始めよう。</h2>
        <p class="text-white/60 text-base mb-10 leading-relaxed">登録は無料。まずはプロフィールを作成してみてください。</p>
        <a href="{{ route('agent.register') }}" class="inline-block bg-white text-gray-900 font-bold text-base px-10 py-4 rounded-full shadow-xl hover:shadow-2xl hover:-translate-y-0.5 transition-all">
            無料で掲載登録する →
        </a>
    </div>
</section>

{{-- ========== フッター ========== --}}
<footer class="bg-gray-900 text-gray-400 py-10 px-5">
    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col md:flex-row items-center justify-between gap-5">
            <img src="{{ asset('img/logo_white.png') }}" alt="ERAPRO" class="h-6 opacity-50">
            <nav class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm">
                <a href="{{ route('terms') }}" class="hover:text-white transition-colors">利用規約</a>
                <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">プライバシーポリシー</a>
                <a href="{{ url('/') }}" class="hover:text-white transition-colors">ユーザー向けトップ</a>
            </nav>
        </div>
        <p class="text-center text-xs mt-6 opacity-40">© {{ date('Y') }} ERAPRO. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
