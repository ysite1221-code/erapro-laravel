@extends('layouts.app')

@section('title', '利用規約 | ERAPRO')

@push('styles')
<style>
    .static-wrap { max-width:800px; margin:60px auto 100px; padding:0 28px; }
    .static-wrap h1 { font-size:1.8rem; font-weight:900; color:#111; margin-bottom:8px; letter-spacing:-0.02em; }
    .static-wrap .updated { font-size:0.82rem; color:#9ca3af; margin-bottom:40px; }
    .static-wrap h2 { font-size:1.1rem; font-weight:700; color:#111; margin:36px 0 12px; padding-bottom:8px; border-bottom:2px solid #f0f0f0; }
    .static-wrap p, .static-wrap li { font-size:0.9rem; color:#555; line-height:1.9; margin:0 0 12px; }
    .static-wrap ul { padding-left:1.4em; margin-bottom:12px; }
    .static-wrap li { margin-bottom:6px; }
    .back-link { display:inline-block; margin-bottom:24px; font-size:0.85rem; color:#999; }
    .back-link:hover { color:#004e92; }
</style>
@endpush

@section('content')
<div class="static-wrap">
    <a href="{{ route('home') }}" class="back-link">← トップへ戻る</a>
    <h1>利用規約</h1>
    <p class="updated">最終更新日：2026年3月21日</p>

    <p>本利用規約（以下「本規約」）は、ERAPRO（以下「当社」）が提供する保険募集人マッチングサービス「ERAPRO」（以下「本サービス」）の利用条件を定めるものです。ユーザーおよび募集人の皆様には、本規約に同意いただいた上でご利用いただきます。</p>

    <h2>第1条（適用）</h2>
    <p>本規約は、本サービスの利用に関わるすべての関係について適用されます。本規約に同意した時点で、利用者は本規約のすべての条項に拘束されることに同意したものとします。</p>

    <h2>第2条（利用登録）</h2>
    <p>登録希望者が所定の方法で利用登録を申請し、当社がこれを承認した時点で、利用登録が完了するものとします。当社は以下の場合に利用登録の申請を承認しないことがあります。</p>
    <ul>
        <li>虚偽の情報を届け出た場合</li>
        <li>過去に本規約違反等により登録を抹消された場合</li>
        <li>その他、当社が利用登録を相当でないと判断した場合</li>
    </ul>

    <h2>第3条（禁止事項）</h2>
    <p>ユーザーは、本サービスの利用にあたり、以下の行為をしてはなりません。</p>
    <ul>
        <li>法令または公序良俗に違反する行為</li>
        <li>犯罪行為に関連する行為</li>
        <li>当社のサーバーまたはネットワークの機能を破壊・妨害する行為</li>
        <li>当社のサービス運営を妨害するおそれのある行為</li>
        <li>他のユーザーまたは第三者に対するハラスメント行為</li>
        <li>虚偽の情報を登録・掲載する行為</li>
        <li>不正な勧誘・営業活動</li>
        <li>その他、当社が不適切と判断する行為</li>
    </ul>

    <h2>第4条（本サービスの提供の停止等）</h2>
    <p>当社は、以下のいずれかの事由があると判断した場合、ユーザーに事前に通知することなく本サービスの全部または一部の提供を停止または中断することができます。</p>
    <ul>
        <li>本サービスにかかるコンピュータシステムの保守点検または更新を行う場合</li>
        <li>地震・落雷・火災・停電または天災などの不可抗力により、本サービスの提供が困難となった場合</li>
        <li>その他、当社が本サービスの提供が困難と判断した場合</li>
    </ul>

    <h2>第5条（免責事項）</h2>
    <p>当社は、本サービスに関して、ユーザーと他のユーザーまたは第三者との間において生じたトラブルに関して一切の責任を負いません。当社は、本サービスの内容変更、提供停止・中止によってユーザーに生じた損害についても、一切の責任を負いません。</p>

    <h2>第6条（サービス内容の変更等）</h2>
    <p>当社は、ユーザーへの事前の告知なしに、本サービスの内容を変更しまたは本サービスの提供を中止することができるものとします。</p>

    <h2>第7条（利用規約の変更）</h2>
    <p>当社は必要と判断した場合には、ユーザーに通知することなく本規約を変更することができるものとします。変更後の本規約は、当社ウェブサイトに掲示された時点から効力を生じるものとします。</p>

    <h2>第8条（準拠法・裁判管轄）</h2>
    <p>本規約の解釈にあたっては、日本法を準拠法とします。本サービスに関して紛争が生じた場合には、東京地方裁判所を第一審の専属的合意管轄裁判所とします。</p>
</div>
@endsection
