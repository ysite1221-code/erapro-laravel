<x-mail::message>
# 本人確認（KYC）の審査結果のお知らせ

{{ $agentName }} 様

本人確認（KYC）の審査が完了しました。

**審査結果：**
@if($status === 2)
**承認されました。**

おめでとうございます。本人確認が承認されました。引き続きERAEROをご利用いただけます。
@else
**否認されました。**

申し訳ございませんが、本人確認書類の審査が通りませんでした。
再度、書類をご確認の上、お手続きをお願いいたします。
@endif

詳細はダッシュボードよりご確認ください。

<x-mail::button :url="$dashboardUrl">
ダッシュボードを確認する
</x-mail::button>

よろしくお願いいたします。<br>
{{ config('app.name') }}
</x-mail::message>
