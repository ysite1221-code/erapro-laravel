<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $diag  = User::where('email', 'user_diag@example.com')->firstOrFail();
        $msg   = User::where('email', 'user_msg@example.com')->firstOrFail();
        $hit   = Agent::where('email', 'agent_hit@example.com')->firstOrFail();
        $shiro = Agent::where('email', 'agent_shiro@example.com')->firstOrFail();
        $hanako = Agent::where('email', 'agent_hanako@example.com')->firstOrFail();

        $reviews = [
            // ── agent_hit へのクチコミ ──
            [
                'user_id'    => $diag->id,
                'agent_id'   => $hit->id,
                'rating'     => 5,
                'comment'    => '初めての相談でしたが、とても丁寧に対応していただきました。難しい保険の話をわかりやすく説明してくださり、安心して任せることができました。おすすめです！',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'user_id'    => $msg->id,
                'agent_id'   => $hit->id,
                'rating'     => 5,
                'comment'    => 'NISAとiDeCoの使い分けについて、具体的なシミュレーションを用いて説明してもらえました。押し売り感が一切なく、自分のペースで考えさせてもらえた点が特に良かったです。',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],

            // ── agent_shiro へのクチコミ ──
            [
                'user_id'    => $msg->id,
                'agent_id'   => $shiro->id,
                'rating'     => 5,
                'comment'    => '中立的な立場でアドバイスをくれるので、本当に信頼できました。複数の選択肢をメリット・デメリット込みで説明してくれて、納得して決断できました。また相談したいと思います。',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id'    => $diag->id,
                'agent_id'   => $shiro->id,
                'rating'     => 4,
                'comment'    => '論理的で分かりやすい説明が好印象でした。数字でしっかり根拠を示してくれるので、説得力があります。少し堅い印象はありますが、内容は確かです。',
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],

            // ── agent_hanako へのクチコミ ──
            [
                'user_id'    => $msg->id,
                'agent_id'   => $hanako->id,
                'rating'     => 5,
                'comment'    => '家計の悩みを親身に聞いてくれて、本当に助かりました。シングルマザーとしての経験から語られる言葉に共感できましたし、実践的なアドバイスをいただけました。',
                'created_at' => now()->subDays(18),
                'updated_at' => now()->subDays(18),
            ],
        ];

        foreach ($reviews as $data) {
            Review::updateOrCreate(
                ['user_id' => $data['user_id'], 'agent_id' => $data['agent_id']],
                $data
            );
        }

        // avg_rating を再計算して agents テーブルに反映
        foreach ([$hit, $shiro, $hanako] as $agent) {
            $avg = Review::where('agent_id', $agent->id)->avg('rating');
            $agent->update(['avg_rating' => round($avg, 2)]);
        }
    }
}
