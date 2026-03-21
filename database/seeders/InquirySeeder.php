<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Inquiry;
use App\Models\InquiryMessage;
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

        $createdInquiries = [];
        foreach ($inquiries as $data) {
            $createdInquiries[] = Inquiry::updateOrCreate(
                ['user_id' => $data['user_id'], 'agent_id' => $data['agent_id']],
                $data
            );
        }

        // ── サンプルメッセージを追加 ──
        // $createdInquiries[0] = user_diag → agent_hit (status=1)
        $inq0 = $createdInquiries[0];
        InquiryMessage::create([
            'inquiry_id'  => $inq0->id,
            'sender_type' => 'agent',
            'message'     => "はじめまして、{$hit->name} です。\nご相談リクエストありがとうございます。老後の資産形成についてお手伝いできればと思います。\nまずはご都合の良い日時を教えていただけますか？",
            'is_read'     => false,
            'created_at'  => now()->subDays(2)->setTime(10, 15),
            'updated_at'  => now()->subDays(2)->setTime(10, 15),
        ]);

        // $createdInquiries[1] = user_msg → agent_shiro (status=4, 完了)
        $inq1 = $createdInquiries[1];
        InquiryMessage::create([
            'inquiry_id'  => $inq1->id,
            'sender_type' => 'agent',
            'message'     => "ご相談ありがとうございます！\n保険の見直しとNISAについて、具体的なシミュレーションを準備しました。\n来週の土曜日はいかがでしょうか？",
            'is_read'     => true,
            'created_at'  => now()->subDays(28)->setTime(14, 0),
            'updated_at'  => now()->subDays(28)->setTime(14, 0),
        ]);
        InquiryMessage::create([
            'inquiry_id'  => $inq1->id,
            'sender_type' => 'user',
            'message'     => "ありがとうございます！来週土曜日の午前10時はいかがでしょうか？",
            'is_read'     => true,
            'created_at'  => now()->subDays(27)->setTime(9, 30),
            'updated_at'  => now()->subDays(27)->setTime(9, 30),
        ]);
        InquiryMessage::create([
            'inquiry_id'  => $inq1->id,
            'sender_type' => 'agent',
            'message'     => "承知しました。では土曜午前10時にオンラインでお話ししましょう。\nZoomのURLをメールでお送りします。",
            'is_read'     => true,
            'created_at'  => now()->subDays(27)->setTime(11, 0),
            'updated_at'  => now()->subDays(27)->setTime(11, 0),
        ]);
        InquiryMessage::create([
            'inquiry_id'  => $inq1->id,
            'sender_type' => 'agent',
            'message'     => "本日はお時間いただきありがとうございました！\n提案内容をまとめた資料をお送りします。ご確認ください。",
            'is_read'     => true,
            'created_at'  => now()->subDays(10)->setTime(16, 0),
            'updated_at'  => now()->subDays(10)->setTime(16, 0),
        ]);

        // $createdInquiries[2] = user_diag → agent_hanako (status=2, 日程調整中)
        $inq2 = $createdInquiries[2];
        InquiryMessage::create([
            'inquiry_id'  => $inq2->id,
            'sender_type' => 'agent',
            'message'     => "ご相談リクエストありがとうございます！\n教育費の準備はとても大切ですね。平日夜か土曜午前ということで、来週の土曜日10〜12時の間はいかがでしょうか？",
            'is_read'     => true,
            'created_at'  => now()->subDays(9)->setTime(19, 0),
            'updated_at'  => now()->subDays(9)->setTime(19, 0),
        ]);
        InquiryMessage::create([
            'inquiry_id'  => $inq2->id,
            'sender_type' => 'user',
            'message'     => "来週の土曜10時でお願いします！よろしくお願いします。",
            'is_read'     => true,
            'created_at'  => now()->subDays(8)->setTime(21, 30),
            'updated_at'  => now()->subDays(8)->setTime(21, 30),
        ]);
        InquiryMessage::create([
            'inquiry_id'  => $inq2->id,
            'sender_type' => 'agent',
            'message'     => "ありがとうございます。では来週土曜10時に確定しました！\n当日はZoomでお繋ぎします。",
            'is_read'     => false,  // ← 未読（user_diag がまだ見ていない）
            'created_at'  => now()->subDays(7)->setTime(9, 0),
            'updated_at'  => now()->subDays(7)->setTime(9, 0),
        ]);

        // $createdInquiries[3] = user_msg → agent_hit (status=3, 面談完了・提案中)
        $inq3 = $createdInquiries[3];
        InquiryMessage::create([
            'inquiry_id'  => $inq3->id,
            'sender_type' => 'agent',
            'message'     => "NISAとiDeCoについてのご相談、ありがとうございます！\n初めての方にもわかりやすくご説明しますね。まずはオンライン面談を設定しましょう。",
            'is_read'     => true,
            'created_at'  => now()->subDays(13)->setTime(11, 0),
            'updated_at'  => now()->subDays(13)->setTime(11, 0),
        ]);
        InquiryMessage::create([
            'inquiry_id'  => $inq3->id,
            'sender_type' => 'user',
            'message'     => "よろしくお願いします。今週金曜の夜21時以降は可能でしょうか？",
            'is_read'     => true,
            'created_at'  => now()->subDays(12)->setTime(20, 0),
            'updated_at'  => now()->subDays(12)->setTime(20, 0),
        ]);
        InquiryMessage::create([
            'inquiry_id'  => $inq3->id,
            'sender_type' => 'agent',
            'message'     => "面談お疲れ様でした！\nNISAとiDeCoの比較資料をお送りします。来月から積立を始める場合のシミュレーションも添付しました。\nご確認の上、ご質問があればお気軽にどうぞ。",
            'is_read'     => false,  // ← 未読
            'created_at'  => now()->subDays(1)->setTime(15, 30),
            'updated_at'  => now()->subDays(1)->setTime(15, 30),
        ]);
    }
}
