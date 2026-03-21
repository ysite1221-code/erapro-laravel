@extends('layouts.admin')

@section('title', 'KYC審査 - ' . $agent->name . ' - ERAPRO Admin')
@section('page-title', 'KYC審査')

@push('styles')
<style>
    .back-link { display: inline-flex; align-items: center; gap: 4px; font-size: 0.84rem; color: #6b7280; text-decoration: none; margin-bottom: 20px; }
    .back-link:hover { color: #1a1f36; }

    .kyc-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; }

    .info-card {
        background: #fff; border-radius: 10px; border: 1px solid #e8eaf0; overflow: hidden;
    }
    .info-card-header {
        padding: 16px 20px; background: #f9fafb;
        border-bottom: 1px solid #e8eaf0; font-size: 0.85rem; font-weight: 700; color: #374151;
    }
    .info-table { width: 100%; border-collapse: collapse; }
    .info-table td { padding: 12px 20px; font-size: 0.875rem; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
    .info-table td:first-child { width: 140px; font-weight: 700; color: #6b7280; font-size: 0.8rem; }
    .info-table tr:last-child td { border-bottom: none; }

    .url-box {
        background: #f9fafb; border: 1px solid #e8eaf0; border-radius: 6px;
        padding: 10px 14px; word-break: break-all; font-size: 0.84rem;
    }
    .url-box a { color: #1d4ed8; text-decoration: none; }
    .url-box a:hover { text-decoration: underline; }

    /* ステータスバッジ */
    .status-badge {
        display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;
    }
    .vs-0 { background: #f9fafb; color: #9ca3af; border: 1px solid #e5e7eb; }
    .vs-1 { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .vs-2 { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .vs-9 { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    /* アクションカード */
    .action-card {
        background: #fff; border-radius: 10px; border: 1px solid #e8eaf0;
        padding: 24px; position: sticky; top: 80px;
    }
    .action-card h3 { font-size: 0.97rem; font-weight: 700; color: #1a1f36; margin-bottom: 16px; }

    .btn-approve {
        display: block; width: 100%; padding: 13px; background: #166534; color: #fff;
        border: none; border-radius: 6px; font-size: 0.9rem; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background 0.18s; margin-bottom: 10px;
    }
    .btn-approve:hover { background: #15803d; }
    .btn-reject {
        display: block; width: 100%; padding: 13px; background: #fff; color: #991b1b;
        border: 1.5px solid #fca5a5; border-radius: 6px; font-size: 0.9rem; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: all 0.18s;
    }
    .btn-reject:hover { background: #fef2f2; }
    .btn-disabled {
        display: block; width: 100%; padding: 13px; background: #f3f4f6; color: #9ca3af;
        border: none; border-radius: 6px; font-size: 0.9rem; font-weight: 700;
        cursor: not-allowed; text-align: center; margin-bottom: 10px;
    }
    .action-note { font-size: 0.78rem; color: #9ca3af; margin-top: 12px; line-height: 1.6; }

    .profile-img-wrap { text-align: center; padding: 20px; }
    .profile-img-wrap img {
        width: 100px; height: 100px; border-radius: 50%; object-fit: cover;
        background: #eee; border: 3px solid #e8eaf0;
    }
</style>
@endpush

@section('content')

<a href="{{ route('admin.dashboard') }}" class="back-link">← ダッシュボードに戻る</a>

@if (session('status'))
<div class="alert-success">✅ {{ session('status') }}</div>
@endif

<div class="kyc-grid">

    {{-- 左：エージェント情報 --}}
    <div>
        <div class="info-card" style="margin-bottom:16px;">
            <div class="info-card-header">エージェント情報</div>
            <div class="profile-img-wrap">
                <img src="{{ $agent->profile_img ? asset('storage/' . $agent->profile_img) : 'https://placehold.co/100x100/e0e0e0/888?text=No+Img' }}"
                     alt="{{ $agent->name }}">
            </div>
            <table class="info-table">
                <tr><td>ID</td><td>#{{ $agent->id }}</td></tr>
                <tr><td>氏名</td><td><strong>{{ $agent->name }}</strong></td></tr>
                <tr><td>メール</td><td>{{ $agent->email }}</td></tr>
                <tr><td>エリア</td><td>{{ $agent->area ?: '-' }}</td></tr>
                <tr><td>キャッチコピー</td><td>{{ $agent->title ?: '-' }}</td></tr>
                <tr><td>タグ</td><td>{{ $agent->tags ?: '-' }}</td></tr>
                <tr>
                    <td>KYCステータス</td>
                    <td>
                        <span class="status-badge vs-{{ $agent->verification_status }}">
                            {{ $statusLabels[$agent->verification_status] ?? '不明' }}
                        </span>
                    </td>
                </tr>
                <tr><td>登録日</td><td>{{ $agent->created_at->format('Y年n月j日') }}</td></tr>
            </table>
        </div>

        <div class="info-card" style="margin-bottom:16px;">
            <div class="info-card-header">📎 提出された URL</div>
            <div style="padding:16px 20px;">
                @if ($agent->affiliation_url)
                <div class="url-box">
                    <a href="{{ $agent->affiliation_url }}" target="_blank" rel="noopener noreferrer">
                        {{ $agent->affiliation_url }}
                    </a>
                </div>
                <a href="{{ $agent->affiliation_url }}" target="_blank" rel="noopener noreferrer"
                   style="display:inline-block;margin-top:10px;font-size:0.82rem;color:#1d4ed8;">
                    🔗 リンクを新しいタブで開く →
                </a>
                @else
                <p style="color:#9ca3af;font-size:0.875rem;">URLが提出されていません。</p>
                @endif
            </div>
        </div>

        @if ($agent->story)
        <div class="info-card">
            <div class="info-card-header">✍️ My Story（参考）</div>
            <div style="padding:16px 20px;font-size:0.875rem;color:#374151;line-height:1.8;white-space:pre-wrap;">{{ $agent->story }}</div>
        </div>
        @endif
    </div>

    {{-- 右：審査アクション --}}
    <div>
        <div class="action-card">
            <h3>🔐 審査判定</h3>

            @if ($agent->verification_status === 2)
            <div style="text-align:center;padding:12px 0 16px;">
                <span class="status-badge vs-2" style="font-size:0.9rem;padding:8px 20px;">✅ 承認済み</span>
            </div>
            <p class="action-note">このエージェントはすでに承認済みです。否認に変更する場合は下のボタンを使用してください。</p>
            <form method="POST" action="{{ route('admin.kyc.update', $agent) }}"
                  onsubmit="return confirm('承認済みのステータスを「否認」に変更しますか？');">
                @csrf @method('PATCH')
                <input type="hidden" name="verification_status" value="9">
                <button type="submit" class="btn-reject" style="margin-top:14px;">否認に変更する</button>
            </form>

            @elseif ($agent->verification_status === 9)
            <div style="text-align:center;padding:12px 0 16px;">
                <span class="status-badge vs-9" style="font-size:0.9rem;padding:8px 20px;">❌ 否認済み</span>
            </div>
            <p class="action-note">このエージェントは否認されています。URLを修正して再提出した場合は承認できます。</p>
            <form method="POST" action="{{ route('admin.kyc.update', $agent) }}"
                  onsubmit="return confirm('このエージェントを「承認」しますか？プロフィールが公開されます。');">
                @csrf @method('PATCH')
                <input type="hidden" name="verification_status" value="2">
                <button type="submit" class="btn-approve" style="margin-top:14px;">承認する</button>
            </form>

            @else
            {{-- 審査待ち or 未提出 --}}
            @if (!$agent->affiliation_url)
            <div class="btn-disabled">URLが未提出です</div>
            <p class="action-note">エージェントがURLを提出してから審査してください。</p>
            @else
            <form method="POST" action="{{ route('admin.kyc.update', $agent) }}"
                  onsubmit="return confirm('このエージェントを「承認」しますか？プロフィールが公開されます。');"
                  style="margin-bottom:10px;">
                @csrf @method('PATCH')
                <input type="hidden" name="verification_status" value="2">
                <button type="submit" class="btn-approve">✅ 承認する</button>
            </form>
            <form method="POST" action="{{ route('admin.kyc.update', $agent) }}"
                  onsubmit="return confirm('このエージェントを「否認」しますか？');">
                @csrf @method('PATCH')
                <input type="hidden" name="verification_status" value="9">
                <button type="submit" class="btn-reject">❌ 否認する</button>
            </form>
            @endif
            <p class="action-note">
                承認するとプロフィールが検索画面に公開されます。<br>
                否認するとエージェントに再提出を促します。
            </p>
            @endif
        </div>
    </div>

</div>

@endsection
