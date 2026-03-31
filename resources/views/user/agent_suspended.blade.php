@extends('layouts.app')

@section('title', 'Agentが活動を停止しています - ERAPRO')

@push('styles')
<style>
    body { background: #f5f5f5; }
    .wrap { max-width: 560px; margin: 80px auto; padding: 0 24px 80px; text-align: center; }
    .icon {
        width: 80px; height: 80px; background: #fff3f3; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.2rem; margin: 0 auto 24px;
    }
    h1 { font-size: 1.25rem; color: #1a1a1a; margin-bottom: 14px; }
    .sub { font-size: 0.92rem; color: #666; line-height: 1.8; margin-bottom: 28px; }
    .agent-name {
        display: inline-block; font-size: 1rem; font-weight: 700; color: #374151;
        background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;
        padding: 10px 24px; margin-bottom: 28px;
    }
    .btn-back {
        display: inline-block; padding: 12px 36px; background: #004e92; color: #fff;
        border-radius: 30px; font-size: 0.9rem; font-weight: bold; text-decoration: none;
        transition: background 0.3s;
    }
    .btn-back:hover { background: #003366; }
</style>
@endpush

@section('content')
<div class="wrap">
    <div class="icon">🚫</div>
    <h1>このAgentは現在活動を停止しています</h1>
    <div class="agent-name">{{ $agent->name }}</div>
    <p class="sub">
        申し訳ありませんが、このエージェントは現在ご利用いただけない状態です。<br>
        他のエージェントをお探しください。
    </p>
    <a href="{{ route('search') }}" class="btn-back">エージェントを探す</a>
</div>
@endsection
