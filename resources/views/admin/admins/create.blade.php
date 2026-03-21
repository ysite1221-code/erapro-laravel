@extends('layouts.admin')

@section('title', '管理者アカウント作成 - ERAPRO Admin')
@section('page-title', '管理者アカウント作成')

@push('styles')
<style>
    .form-card {
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e8eaf0;
        padding: 32px 36px;
        max-width: 520px;
    }
    .form-card .desc {
        font-size: 0.855rem;
        color: #6b7280;
        margin-bottom: 28px;
        line-height: 1.6;
        padding: 12px 14px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 6px;
    }
    .form-group { margin-bottom: 20px; }
    .form-label {
        display: block;
        font-size: 0.82rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 6px;
    }
    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.9rem;
        font-family: inherit;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .form-input:focus { outline: none; border-color: #6366f1; }
    .form-error { font-size: 0.78rem; color: #dc2626; margin-top: 4px; }
    .btn-submit {
        width: 100%;
        padding: 12px;
        background: #1a1f36;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: background 0.18s;
        margin-top: 8px;
    }
    .btn-submit:hover { background: #374151; }
</style>
@endpush

@section('content')

@if (session('status'))
<div class="alert-success">✅ {{ session('status') }}</div>
@endif

<div class="form-card">
    <div class="desc">
        ⚠️ このフォームは既存管理者のみアクセスできます。<br>
        旧PHP版 admin_register.php 相当の機能ですが、セキュリティのため認証必須で実装しています。
    </div>

    <form method="POST" action="{{ route('admin.admins.store') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="name">管理者名</label>
            <input type="text" id="name" name="name" class="form-input"
                   value="{{ old('name') }}" placeholder="例: 運営太郎" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">メールアドレス（ログインID）</label>
            <input type="email" id="email" name="email" class="form-input"
                   value="{{ old('email') }}" placeholder="admin@erapro.com" required>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">パスワード（8文字以上）</label>
            <input type="password" id="password" name="password" class="form-input"
                   placeholder="半角英数字8文字以上" required>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">パスワード（確認）</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-input" placeholder="もう一度入力" required>
        </div>

        <button type="submit" class="btn-submit">管理者アカウントを作成する</button>
    </form>
</div>

@endsection
