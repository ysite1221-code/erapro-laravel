<x-mail::message>
# 新しい相談リクエストが届きました

この度はご登録ありがとうございます。

新しい相談リクエストが届きました。

**相談者：** {{ $userName }}
**相談目的：** {{ $purpose }}

早めにご確認いただき、対応をお願いいたします。

<x-mail::button :url="$inquiryUrl">
相談内容を確認する
</x-mail::button>

よろしくお願いいたします。<br>
{{ config('app.name') }}
</x-mail::message>
