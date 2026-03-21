@extends('layouts.agent')

@section('title', 'レポート - ERAPRO Agent')

@push('styles')
<style>
    .chart-card {
        background:#fff; border-radius:8px; box-shadow:0 2px 12px rgba(0,0,0,0.07);
        padding:28px 28px 24px; margin-bottom:28px;
    }
    .chart-card h3 { font-size:1rem; font-weight:700; color:#111; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .compare-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:28px; }
    .compare-card { background:#fff; border-radius:8px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:24px 24px 20px; }
    .compare-card h4 { font-size:0.95rem; font-weight:700; color:#111; margin-bottom:16px; }
    .compare-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #f0f0f0; }
    .compare-row:last-child { border-bottom:none; }
    .compare-label { font-size:0.85rem; color:#6b7280; }
    .compare-value { font-size:0.95rem; font-weight:700; color:#111; }
    .diff-up   { color:#2e7d32; font-weight:700; }
    .diff-down { color:#c62828; font-weight:700; }
    .diff-zero { color:#999; }
    .inq-stat-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
    .inq-stat-card { background:#f8f9ff; border-radius:8px; padding:20px 16px; text-align:center; }
    @media (max-width: 700px) {
        .compare-grid { grid-template-columns:1fr; }
        .inq-stat-grid { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')
<div class="dashboard">

    <x-agent-sidebar :agent="$agent" active="report" />

    <main class="main-content">

        <h2>📈 アクティビティレポート</h2>

        {{-- KPIグリッド --}}
        <div class="kpi-grid" style="margin-bottom:28px;">
            <div class="kpi-card">
                <div class="kpi-label">📊 今月の閲覧数</div>
                <div class="kpi-value">{{ number_format($monthlyViews) }}<span class="kpi-unit"> PV</span></div>
            </div>
            <div class="kpi-card today">
                <div class="kpi-label">🔍 本日の閲覧数</div>
                <div class="kpi-value">{{ number_format($todayViews) }}<span class="kpi-unit"> PV</span></div>
            </div>
            <div class="kpi-card fav">
                <div class="kpi-label">❤️ お気に入り登録</div>
                <div class="kpi-value">{{ number_format($favCount) }}<span class="kpi-unit"> 人</span></div>
            </div>
            <div class="kpi-card myagent">
                <div class="kpi-label">⭐ My Agent 登録</div>
                <div class="kpi-value">{{ number_format($myAgentCount) }}<span class="kpi-unit"> 人</span></div>
            </div>
        </div>

        {{-- 過去30日間の閲覧数グラフ --}}
        <div class="chart-card">
            <h3>
                <span class="material-icons-outlined" style="color:#004e92;font-size:1.2rem;">bar_chart</span>
                過去30日間のプロフィール閲覧数
            </h3>
            <canvas id="viewsChart" height="90"></canvas>
        </div>

        {{-- お気に入り・My Agent 推移比較 --}}
        <div class="compare-grid">
            <div class="compare-card">
                <h4>❤️ お気に入り推移（先月 vs 今月）</h4>
                <div class="compare-row">
                    <span class="compare-label">先月の新規登録</span>
                    <span class="compare-value">{{ $lastFav }} 人</span>
                </div>
                <div class="compare-row">
                    <span class="compare-label">今月の新規登録</span>
                    <span class="compare-value">{{ $thisFav }} 人</span>
                </div>
                <div class="compare-row">
                    <span class="compare-label">増減</span>
                    <span class="compare-value">
                        @if ($favDiff > 0)
                            <span class="diff-up">+{{ $favDiff }}</span>
                        @elseif ($favDiff < 0)
                            <span class="diff-down">{{ $favDiff }}</span>
                        @else
                            <span class="diff-zero">±0</span>
                        @endif
                    </span>
                </div>
            </div>
            <div class="compare-card">
                <h4>⭐ My Agent 推移（先月 vs 今月）</h4>
                <div class="compare-row">
                    <span class="compare-label">先月の新規登録</span>
                    <span class="compare-value">{{ $lastMyAgent }} 人</span>
                </div>
                <div class="compare-row">
                    <span class="compare-label">今月の新規登録</span>
                    <span class="compare-value">{{ $thisMyAgent }} 人</span>
                </div>
                <div class="compare-row">
                    <span class="compare-label">増減</span>
                    <span class="compare-value">
                        @if ($myAgentDiff > 0)
                            <span class="diff-up">+{{ $myAgentDiff }}</span>
                        @elseif ($myAgentDiff < 0)
                            <span class="diff-down">{{ $myAgentDiff }}</span>
                        @else
                            <span class="diff-zero">±0</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- 問い合わせ統計 --}}
        <div class="chart-card">
            <h3>
                <span class="material-icons-outlined" style="color:#004e92;font-size:1.2rem;">chat</span>
                問い合わせ統計
            </h3>
            <div class="inq-stat-grid">
                <div class="inq-stat-card">
                    <div class="kpi-label">📨 総問い合わせ数</div>
                    <div class="kpi-value" style="font-size:1.8rem;">{{ number_format($totalInquiries) }}<span class="kpi-unit" style="font-size:0.9rem;"> 件</span></div>
                </div>
                <div class="inq-stat-card" style="background:#fff8e1;">
                    <div class="kpi-label">🔔 未対応</div>
                    <div class="kpi-value" style="font-size:1.8rem;color:#e65100;">{{ number_format($newInquiries) }}<span class="kpi-unit" style="font-size:0.9rem;"> 件</span></div>
                    @if ($newInquiries > 0)
                    <div style="margin-top:8px;">
                        <a href="{{ route('agent.inquiries.index') }}" style="font-size:0.82rem;color:#004e92;font-weight:bold;">→ 確認する</a>
                    </div>
                    @endif
                </div>
                <div class="inq-stat-card" style="background:#e8f5e9;">
                    <div class="kpi-label">🔄 対応中</div>
                    <div class="kpi-value" style="font-size:1.8rem;color:#2e7d32;">{{ number_format($activeInquiries) }}<span class="kpi-unit" style="font-size:0.9rem;"> 件</span></div>
                </div>
            </div>
        </div>

    </main>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($chartLabels);
const data   = @json($chartData);

const ctx = document.getElementById('viewsChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'プロフィール閲覧数',
            data: data,
            backgroundColor: 'rgba(0, 78, 146, 0.18)',
            borderColor: '#004e92',
            borderWidth: 2,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.parsed.y + ' PV'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision:0, color:'#888', font:{size:11} },
                grid: { color:'#f0f0f0' }
            },
            x: {
                ticks: { color:'#888', font:{size:10}, maxTicksLimit:10 },
                grid: { display:false }
            }
        }
    }
});
</script>
@endpush
