<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERAPRO - 保険のプロを、想いで選ぶ</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Noto Sans JP', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #eff6ff 0%, #ffffff 50%, #eef2ff 100%); }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

{{-- ========== ナビゲーション ========== --}}
<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-5 h-16 flex items-center justify-between">
        <a href="{{ route('home') }}">
            <img src="{{ asset('img/logo_blue.png') }}" alt="ERAPRO" class="h-7">
        </a>
        <div class="flex items-center gap-3 sm:gap-5">
            @if(Auth::guard('user')->check())
                <a href="{{ route('user.dashboard') }}" class="text-sm text-gray-600 font-medium hover:text-blue-700 transition-colors">マイページ</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-800 transition-colors">ログアウト</button>
                </form>
            @elseif(Auth::guard('agent')->check())
                <a href="{{ route('agent.dashboard') }}" class="text-sm text-gray-600 font-medium hover:text-blue-700 transition-colors">マイページ</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-800 transition-colors">ログアウト</button>
                </form>
            @else
                <a href="{{ route('agent.lp') }}" class="hidden sm:block text-sm text-gray-500 hover:text-gray-800 transition-colors">Agentの方へ</a>
                <a href="{{ route('login') }}" class="text-sm text-gray-700 font-semibold hover:text-blue-700 transition-colors">ログイン</a>
                <a href="{{ route('user.register') }}" class="text-sm bg-blue-700 text-white font-bold px-4 py-2 rounded-full hover:bg-blue-800 transition-colors shadow-sm">
                    無料登録
                </a>
            @endif
        </div>
    </div>
</nav>

{{-- ========== ヒーロー ========== --}}
<section class="hero-gradient py-20 sm:py-28 px-5">
    <div class="max-w-3xl mx-auto text-center">
        <p class="inline-block text-blue-700 text-xs font-bold tracking-widest bg-blue-50 px-3 py-1 rounded-full mb-5">INSURANCE × TRUST</p>
        <h1 class="text-3xl sm:text-5xl font-black text-gray-900 leading-tight mb-6 tracking-tight">
            保険選びは、<br>「商品」より「<span class="text-blue-700">人</span>」で。
        </h1>
        <p class="text-gray-500 text-base sm:text-lg leading-relaxed mb-10 max-w-xl mx-auto">
            あなたの価値観に合った保険のプロを見つけましょう。<br class="hidden sm:block">口コミと経歴で、信頼できるパートナーを選べます。
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('search') }}" class="bg-blue-700 text-white text-base font-bold px-8 py-4 rounded-full shadow-lg hover:bg-blue-800 hover:shadow-xl transition-all inline-flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                保険のプロを探す
            </a>
            <a href="{{ route('diagnosis') }}" class="bg-white text-blue-700 text-base font-bold px-8 py-4 rounded-full border-2 border-blue-200 hover:border-blue-400 transition-all inline-flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                AI診断で探す
            </a>
        </div>
        <p class="text-gray-400 text-xs mt-6">登録無料 &nbsp;•&nbsp; 強引な勧誘なし &nbsp;•&nbsp; KYC本人確認済みAgent</p>
    </div>
</section>

{{-- ========== 信頼バッジ ========== --}}
<div class="border-y border-gray-100 bg-gray-50 py-5 px-5">
    <ul class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-sm text-gray-500 font-medium">
        <li class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            KYC本人確認済みAgentのみ
        </li>
        <li class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/></svg>
            口コミ・評価が公開されている
        </li>
        <li class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/></svg>
            強引な勧誘は禁止
        </li>
        <li class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/></svg>
            いつでも相談をキャンセル可能
        </li>
    </ul>
</div>

{{-- ========== Agentカードプレビュー ========== --}}
<section class="py-20 px-5 bg-white">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-3">どんなAgentがいるの？</h2>
            <p class="text-gray-500 text-sm sm:text-base">実績と想いを持ったプロフェッショナルが、あなたの相談を待っています。</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-hover overflow-hidden">
                <div class="h-32 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-5xl">👨‍💼</div>
                <div class="p-5">
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        <span class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full font-medium">子育て</span>
                        <span class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full font-medium">老後設計</span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1">ライフプランから逆算した保険提案</h3>
                    <p class="text-xs text-gray-400 mb-3">東京都 &nbsp;•&nbsp; 経験12年</p>
                    <div class="flex items-center gap-1 text-yellow-400 text-sm font-bold">
                        ★★★★★ <span class="text-gray-400 text-xs font-normal ml-1">(47件)</span>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-hover overflow-hidden">
                <div class="h-32 bg-gradient-to-br from-green-100 to-teal-100 flex items-center justify-center text-5xl">👩‍💼</div>
                <div class="p-5">
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        <span class="text-xs bg-green-50 text-green-700 px-2.5 py-1 rounded-full font-medium">相続</span>
                        <span class="text-xs bg-green-50 text-green-700 px-2.5 py-1 rounded-full font-medium">資産形成</span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1">家族を守るための保険選びをサポート</h3>
                    <p class="text-xs text-gray-400 mb-3">大阪府 &nbsp;•&nbsp; 経験8年</p>
                    <div class="flex items-center gap-1 text-yellow-400 text-sm font-bold">
                        ★★★★☆ <span class="text-gray-400 text-xs font-normal ml-1">(32件)</span>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-hover overflow-hidden sm:col-span-2 lg:col-span-1">
                <div class="h-32 bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center text-5xl">🧑‍💼</div>
                <div class="p-5">
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        <span class="text-xs bg-purple-50 text-purple-700 px-2.5 py-1 rounded-full font-medium">経営者</span>
                        <span class="text-xs bg-purple-50 text-purple-700 px-2.5 py-1 rounded-full font-medium">節税</span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1">経営者・法人向け保険のスペシャリスト</h3>
                    <p class="text-xs text-gray-400 mb-3">愛知県 &nbsp;•&nbsp; 経験15年</p>
                    <div class="flex items-center gap-1 text-yellow-400 text-sm font-bold">
                        ★★★★★ <span class="text-gray-400 text-xs font-normal ml-1">(61件)</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('search') }}" class="inline-flex items-center gap-2 border-2 border-blue-700 text-blue-700 font-bold px-8 py-3.5 rounded-full hover:bg-blue-700 hover:text-white transition-all">
                すべてのAgentを見る
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ========== ご利用の流れ ========== --}}
<section class="py-20 px-5 bg-gray-50">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-14">
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-3">ご利用の流れ</h2>
            <p class="text-gray-500 text-sm sm:text-base">最短5分で、あなたに合うAgentへ相談できます。</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl p-8 shadow-sm text-center">
                <div class="w-12 h-12 bg-blue-700 text-white rounded-full flex items-center justify-center text-xl font-black mx-auto mb-5">1</div>
                <h3 class="font-bold text-gray-800 mb-2">Agentを探す</h3>
                <p class="text-sm text-gray-500 leading-relaxed">エリア・専門分野・口コミで検索。AI診断で相性のいいAgentも見つかります。</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm text-center">
                <div class="w-12 h-12 bg-blue-700 text-white rounded-full flex items-center justify-center text-xl font-black mx-auto mb-5">2</div>
                <h3 class="font-bold text-gray-800 mb-2">相談を申し込む</h3>
                <p class="text-sm text-gray-500 leading-relaxed">気になるAgentへメッセージで相談を申込み。プロフィールや口コミを読んで納得した上で依頼できます。</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm text-center">
                <div class="w-12 h-12 bg-blue-700 text-white rounded-full flex items-center justify-center text-xl font-black mx-auto mb-5">3</div>
                <h3 class="font-bold text-gray-800 mb-2">保険を選ぶ</h3>
                <p class="text-sm text-gray-500 leading-relaxed">自分のペースで保険を検討。相談後に口コミを投稿して、次の人の参考にできます。</p>
            </div>
        </div>
    </div>
</section>

{{-- ========== 選ばれる理由 ========== --}}
<section class="py-20 px-5 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-14">
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-3">ERAPROが選ばれる理由</h2>
            <p class="text-gray-500 text-sm sm:text-base">保険選びの不安を、ERAPROが解消します。</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="flex gap-5 p-6 rounded-2xl bg-blue-50 border border-blue-100">
                <div class="text-3xl flex-shrink-0 mt-0.5">🔍</div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">想いと実績で選べる</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">資格だけでなく、原体験や哲学、口コミ評価が公開されています。自分に合う人を、納得して選べます。</p>
                </div>
            </div>
            <div class="flex gap-5 p-6 rounded-2xl bg-green-50 border border-green-100">
                <div class="text-3xl flex-shrink-0 mt-0.5">🛡️</div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">強引な勧誘はありません</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">全Agentに行動指針の遵守を義務付け。強引な勧誘があった場合はすぐに通報できます。</p>
                </div>
            </div>
            <div class="flex gap-5 p-6 rounded-2xl bg-yellow-50 border border-yellow-100">
                <div class="text-3xl flex-shrink-0 mt-0.5">✅</div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">本人確認済みAgentだけ</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">登録Agentは全員KYC（本人確認）審査を通過。募集人資格の確認も完了しています。</p>
                </div>
            </div>
            <div class="flex gap-5 p-6 rounded-2xl bg-purple-50 border border-purple-100">
                <div class="text-3xl flex-shrink-0 mt-0.5">💬</div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">口コミが積み上がる</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">実際に相談したユーザーの評価とコメントが蓄積。よいAgentがきちんと評価される仕組みです。</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== CTA ========== --}}
<section class="py-20 px-5 bg-gradient-to-r from-blue-700 to-indigo-700">
    <div class="max-w-2xl mx-auto text-center text-white">
        <h2 class="text-2xl sm:text-3xl font-black mb-4">まず、探してみよう。</h2>
        <p class="opacity-80 text-base mb-8 leading-relaxed">全国のERAPRO Agentが、あなたの相談を待っています。</p>
        <a href="{{ route('search') }}" class="inline-block bg-white text-blue-700 font-bold text-base px-10 py-4 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
            保険のプロを探す →
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
                <a href="{{ route('agent.lp') }}" class="hover:text-white transition-colors">Agentの方へ</a>
            </nav>
        </div>
        <p class="text-center text-xs mt-6 opacity-40">© {{ date('Y') }} ERAPRO. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
