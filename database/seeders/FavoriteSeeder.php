<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $diag   = User::where('email', 'user_diag@example.com')->firstOrFail();
        $msg    = User::where('email', 'user_msg@example.com')->firstOrFail();
        $hit    = Agent::where('email', 'agent_hit@example.com')->firstOrFail();
        $hanako = Agent::where('email', 'agent_hanako@example.com')->firstOrFail();
        $shiro  = Agent::where('email', 'agent_shiro@example.com')->firstOrFail();
        $score10 = Agent::where('email', 'agent_score10@example.com')->firstOrFail();

        $favorites = [
            // ── user_diag のお気に入り・My Agent ──
            [
                'user_id'    => $diag->id,
                'agent_id'   => $hit->id,
                'status'     => 1, // お気に入り
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'user_id'    => $diag->id,
                'agent_id'   => $hanako->id,
                'status'     => 2, // My Agent
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],

            // ── user_msg のお気に入り・My Agent ──
            [
                'user_id'    => $msg->id,
                'agent_id'   => $shiro->id,
                'status'     => 2, // My Agent（完了済み案件なので格上げ）
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(4),
            ],
            [
                'user_id'    => $msg->id,
                'agent_id'   => $hit->id,
                'status'     => 1, // お気に入り
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            [
                'user_id'    => $msg->id,
                'agent_id'   => $score10->id,
                'status'     => 1, // お気に入り
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
        ];

        foreach ($favorites as $data) {
            Favorite::updateOrCreate(
                ['user_id' => $data['user_id'], 'agent_id' => $data['agent_id']],
                $data
            );
        }
    }
}
