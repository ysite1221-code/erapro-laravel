<x-mail::message>
# 相談のステータスが更新されました

ユーザー様

担当エージェント **{{ $agentName }}** により、相談のステータスが更新されました。

**新しいステータス：** {{ $statusLabel }}

最新の状況は以下のリンクよりご確認いただけます。

<x-mail::button :url="$inquiryUrl">
相談の詳細を確認する
</x-mail::button>

よろしくお願いいたします。<br>
{{ config('app.name') }}
</x-mail::message>
