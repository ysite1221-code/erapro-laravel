<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    public function run(): void
    {
        $diag  = User::where('email', 'user_diag@example.com')->firstOrFail();
        $msg   = User::where('email', 'user_msg@example.com')->firstOrFail();
        $hit   = Agent::where('email', 'agent_hit@example.com')->firstOrFail();
        $shiro = Agent::where('email', 'agent_shiro@example.com')->firstOrFail();
        $hanako = Agent::where('email', 'agent_hanako@example.com')->firstOrFail();
        $score10 = Agent::where('email', 'agent_score10@example.com')->firstOrFail();

        $inquiries = [
            // ── user_diag → agent_hit: 相談リクエスト送信済（status=1）──
            [
                'user_id'         => $diag->id,
                'agent_id'        => $hit->id,
                'status'          => 1,
                'purpose'         => '老後の資産形成について相談したい',
                'trigger'         => '定年後の生活が不安になってきた',
                'preferred_style' => 'オンライン面談を希望',
                'note'            => '具体的なシミュレーションを見せてもらえると助かります。',
                'completion_note' => null,
                'created_at'      => now()->subDays(3),
                'updated_at'      => now()->subDays(3),
            ],

            // ── user_msg → agent_shiro: 完了（status=4）+ completion_note ──
            [
                'user_id'         => $msg->id,
                'agent_id'        => $shiro->id,
                'status'          => 4,
                'purpose'         => '保険の見直しと投資信託の始め方',
                'trigger'         => '結婚を機に家計を整えたかった',
                'preferred_style' => '対面・名古屋市内希望',
                'note'            => '現在の保険証券を持参します。',
                'completion_note' => "2回の面談を経て終身保険を一部解約し、つみたてNISAへ移行する提案を採用いただきました。\n月2万円の積立を開始予定。次回フォローアップは3ヶ月後。",
                'created_at'      => now()->subDays(30),
                'updated_at'      => now()->subDays(5),
            ],

            // ── user_diag → agent_hanako: 日程調整中（status=2）──
            [
                'user_id'         => $diag->id,
                'agent_id'        => $hanako->id,
                'status'          => 2,
                'purpose'         => '子どもの教育費の準備方法を知りたい',
                'trigger'         => '第一子が生まれ、教育費の不安が出てきた',
                'preferred_style' => 'オンラインでも対面でも可',
                'note'            => '平日夜か土曜午前が都合よいです。',
                'completion_note' => null,
                'created_at'      => now()->subDays(10),
                'updated_at'      => now()->subDays(2),
            ],

            // ── user_msg → agent_hit: 面談完了・提案中（status=3）──
            [
                'user_id'         => $msg->id,
                'agent_id'        => $hit->id,
                'status'          => 3,
                'purpose'         => 'NISAとiDeCoの使い分けについて',
                'trigger'         => '会社の同僚が始めているのを聞いて興味を持った',
                'preferred_style' => 'オンライン面談',
                'note'            => '初心者なので基礎から教えてほしいです。',
                'completion_note' => null,
                'created_at'      => now()->subDays(14),
                'updated_at'      => now()->subDays(1),
            ],

            // ── user_diag → agent_score10: キャンセル（status=5）──
            [
                'user_id'         => $diag->id,
                'agent_id'        => $score10->id,
                'status'          => 5,
                'purpose'         => '積立投資の始め方を聞きたい',
                'trigger'         => 'SNSで積立投資の記事を読んで興味を持った',
                'preferred_style' => 'オンライン希望',
                'note'            => null,
                'completion_note' => null,
                'created_at'      => now()->subDays(20),
                'updated_at'      => now()->subDays(15),
            ],

            // ── user_msg → agent_hanako: 完了（status=4）──
            [
                'user_id'         => $msg->id,
                'agent_id'        => $hanako->id,
                'status'          => 4,
                'purpose'         => '家計改善と保険の整理',
                'trigger'         => '毎月赤字気味で将来が不安',
                'preferred_style' => '対面・大阪市内',
                'note'            => '家計簿を持参します。',
                'completion_note' => "固定費を月3万円削減するプランを策定。掛け捨て医療保険に切り替え、余剰分を貯蓄へ回す計画で合意。",
                'created_at'      => now()->subDays(45),
                'updated_at'      => now()->subDays(20),
            ],
        ];

        foreach ($inquiries as $data) {
            Inquiry::updateOrCreate(
                ['user_id' => $data['user_id'], 'agent_id' => $data['agent_id']],
                $data
            );
        }
    }
}
