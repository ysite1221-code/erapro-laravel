<x-mail::message>
# 通報が届きました

ERAPROに新しい通報が届きました。管理画面よりご確認ください。

---

**■ 通報者情報**

- 種別：{{ $reporterType === 'agent' ? 'エージェント' : 'ユーザー' }}
- 名前：{{ $reporterName }}
- メール：{{ $reporterEmail }}

**■ 通報対象**

- 種別：{{ $targetType === 'agent' ? 'エージェント' : 'ユーザー' }}
- 名前：{{ $targetName }}
- メール：{{ $targetEmail }}

**■ 通報理由**

{{ $reason }}

---

<x-mail::button :url="$adminReportsUrl">
管理画面で通報を確認する
</x-mail::button>

{{ config('app.name') }} 運営事務局
</x-mail::message>
