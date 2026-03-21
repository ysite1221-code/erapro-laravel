<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\ProfileView;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ProfileViewSeeder extends Seeder
{
    public function run(): void
    {
        $diag   = User::where('email', 'user_diag@example.com')->firstOrFail();
        $msg    = User::where('email', 'user_msg@example.com')->firstOrFail();
        $hit    = Agent::where('email', 'agent_hit@example.com')->firstOrFail();
        $shiro  = Agent::where('email', 'agent_shiro@example.com')->firstOrFail();
        $hanako = Agent::where('email', 'agent_hanako@example.com')->firstOrFail();
        $score10 = Agent::where('email', 'agent_score10@example.com')->firstOrFail();

        // 閲覧データ定義: [agent, user|null, daysAgo, viewer_ip]
        // 直近30日内に分散させ、レポート画面の「今月の閲覧数」グラフに反映されるよう設定
        $viewPatterns = [
            // ── agent_hit: 今月に集中した多めの閲覧（人気エージェント再現）──
            [$hit,    $diag,   0,  '203.0.113.10'],
            [$hit,    $diag,   1,  '203.0.113.10'],
            [$hit,    $msg,    1,  '198.51.100.22'],
            [$hit,    null,    2,  '192.0.2.55'],
            [$hit,    $msg,    3,  '198.51.100.22'],
            [$hit,    null,    3,  '192.0.2.56'],
            [$hit,    $diag,   5,  '203.0.113.10'],
            [$hit,    null,    5,  '192.0.2.57'],
            [$hit,    null,    6,  '192.0.2.58'],
            [$hit,    $msg,    7,  '198.51.100.22'],
            [$hit,    null,    8,  '192.0.2.59'],
            [$hit,    null,    9,  '192.0.2.60'],
            [$hit,    $diag,  10,  '203.0.113.10'],
            [$hit,    null,   11,  '192.0.2.61'],
            [$hit,    null,   12,  '192.0.2.62'],
            [$hit,    $msg,   13,  '198.51.100.23'],
            [$hit,    null,   14,  '192.0.2.63'],
            [$hit,    null,   15,  '192.0.2.64'],
            [$hit,    null,   16,  '192.0.2.65'],
            [$hit,    null,   18,  '192.0.2.66'],
            [$hit,    null,   20,  '192.0.2.67'],
            [$hit,    null,   22,  '192.0.2.68'],
            [$hit,    null,   25,  '192.0.2.69'],
            [$hit,    null,   28,  '192.0.2.70'],

            // ── agent_shiro: 今月に安定した閲覧 ──
            [$shiro,  $msg,   0,  '198.51.100.24'],
            [$shiro,  $diag,  2,  '203.0.113.11'],
            [$shiro,  null,   3,  '192.0.2.80'],
            [$shiro,  $msg,   5,  '198.51.100.24'],
            [$shiro,  null,   6,  '192.0.2.81'],
            [$shiro,  null,   8,  '192.0.2.82'],
            [$shiro,  $diag, 10,  '203.0.113.11'],
            [$shiro,  null,  12,  '192.0.2.83'],
            [$shiro,  null,  15,  '192.0.2.84'],
            [$shiro,  $msg,  18,  '198.51.100.24'],
            [$shiro,  null,  21,  '192.0.2.85'],
            [$shiro,  null,  25,  '192.0.2.86'],

            // ── agent_hanako: 今月に数件 ──
            [$hanako, $diag,  1,  '203.0.113.12'],
            [$hanako, $msg,   4,  '198.51.100.25'],
            [$hanako, null,   7,  '192.0.2.90'],
            [$hanako, $diag, 11,  '203.0.113.12'],
            [$hanako, null,  16,  '192.0.2.91'],
            [$hanako, null,  22,  '192.0.2.92'],
            [$hanako, $msg,  27,  '198.51.100.25'],

            // ── agent_score10: 少数の閲覧 ──
            [$score10, $diag, 2,  '203.0.113.13'],
            [$score10, $msg,  6,  '198.51.100.26'],
            [$score10, null,  9,  '192.0.2.95'],
            [$score10, null, 14,  '192.0.2.96'],
            [$score10, $diag,20,  '203.0.113.13'],
        ];

        foreach ($viewPatterns as [$agent, $user, $daysAgo, $ip]) {
            // 同日内でもランダムな時刻にばらつかせる
            $viewedAt = Carbon::now()
                ->subDays($daysAgo)
                ->setHour(rand(8, 22))
                ->setMinute(rand(0, 59))
                ->setSecond(rand(0, 59));

            ProfileView::create([
                'agent_id'  => $agent->id,
                'user_id'   => $user?->id,
                'viewer_ip' => $ip,
                'viewed_at' => $viewedAt,
            ]);
        }
    }
}
