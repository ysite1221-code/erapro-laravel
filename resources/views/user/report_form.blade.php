@extends('layouts.app')

@section('title', '通報 - ' . $agent->name . ' | ERAPRO')

@push('styles')
<style>
    .report-wrap { max-width:560px; margin:60px auto; padding:0 24px 80px; }
    .report-card { background:#fff; border-radius:8px; box-shadow:0 2px 16px rgba(0,0,0,0.08); padding:40px 40px 48px; }
    .report-card h1 { font-size:1.4rem; font-weight:900; color:#111; margin-bottom:8px; }
    .report-subtitle { font-size:0.875rem; color:#6b7280; margin-bottom:28px; }
    .form-group { margin-bottom:22px; }
    .form-group label { display:block; font-size:0.84rem; font-weight:600; color:#374151; margin-bottom:8px; }
    .form-group select,
    .form-group textarea {
        width:100%; padding:11px 14px; border:1px solid #d1d5db; border-radius:8px;
        font-size:0.92rem; outline:none; transition:border-color .2s; font-family:inherit;
    }
    .form-group select:focus,
    .form-group textarea:focus { border-color:#dc2626; }
    .form-group textarea { resize:vertical; min-height:120px; }
    .error-msg { color:#ef4444; font-size:0.78rem; margin-top:4px; }
    .btn-report {
        width:100%; padding:13px; background:#dc2626; color:#fff;
        font-size:0.95rem; font-weight:700; border:none; border-radius:8px;
        cursor:pointer; transition:background .2s;
    }
    .btn-report:hover { background:#b91c1c; }
    .back-link { display:inline-block; margin-bottom:20px; font-size:0.85rem; color:#999; }
    .back-link:hover { color:#dc2626; }
    .notice-box {
        background:#fef2f2; border:1px solid #fecaca; border-radius:8px;
        padding:12px 16px; font-size:0.82rem; color:#dc2626; margin-bottom:24px;
    }
</style>
@endpush

@section('content')
<div class="report-wrap">
    <a href="{{ route('agent.profile', $agent->id) }}" class="back-link">← {{ $agent->name }} さんのプロフィールに戻る</a>

    <div class="report-card">
        <h1>🚨 通報する</h1>
        <p class="report-subtitle">{{ $agent->name }} さんについての通報フォームです。</p>

        <div class="notice-box">
            虚偽の通報は禁止されています。悪質な場合はアカウントを停止することがあります。
        </div>

        @if ($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#dc2626;font-size:0.875rem;padding:12px 16px;margin-bottom:20px;">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('user.report.store', $agent->id) }}">
            @csrf

            <div class="form-group">
                <label for="reason">通報理由 <span style="color:#dc2626;">*</span></label>
                <select id="reason" name="reason" required>
                    <option value="" disabled {{ old('reason') ? '' : 'selected' }}>選択してください</option>
                    <option value="不正勧誘" {{ old('reason') === '不正勧誘' ? 'selected' : '' }}>不正な勧誘・強引な営業</option>
                    <option value="虚偽情報" {{ old('reason') === '虚偽情報' ? 'selected' : '' }}>プロフィールの虚偽情報</option>
                    <option value="無資格" {{ old('reason') === '無資格' ? 'selected' : '' }}>無資格・不正登録の疑い</option>
                    <option value="ハラスメント" {{ old('reason') === 'ハラスメント' ? 'selected' : '' }}>ハラスメント・不適切な言動</option>
                    <option value="個人情報漏洩" {{ old('reason') === '個人情報漏洩' ? 'selected' : '' }}>個人情報の不正利用・漏洩</option>
                    <option value="その他" {{ old('reason') === 'その他' ? 'selected' : '' }}>その他</option>
                </select>
                @error('reason')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label for="detail">詳細（任意・最大1000文字）</label>
                <textarea id="detail" name="detail" placeholder="具体的な状況や日時などを入力してください">{{ old('detail') }}</textarea>
                @error('detail')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-report"
                    onclick="return confirm('この内容で通報しますか？')">
                通報する
            </button>
        </form>
    </div>
</div>
@endsection
