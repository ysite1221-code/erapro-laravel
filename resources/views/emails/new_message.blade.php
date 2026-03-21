<x-mail::message>
# 新着メッセージが届きました

**{{ $senderName }}** さんからメッセージが届きました。

> {{ Str::limit($messagePreview, 100) }}

マイページの相談詳細からご確認ください。

<x-mail::button :url="$inquiryUrl">
メッセージを確認する
</x-mail::button>

---
このメールは ERAPRO から自動送信されています。
心当たりのない場合はこのメールを無視してください。

{{ config('app.name') }}
</x-mail::message>
