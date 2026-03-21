<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = [
            [
                'name'                => '検索ヒット太郎',
                'email'               => 'agent_hit@example.com',
                'password'            => Hash::make('1111'),
                'title'               => 'ファイナンシャルプランナー / 資産形成の専門家',
                'story'               => '大手銀行に15年勤務後、独立。1,000件以上の相談実績を持ち、老後不安から解放されたいという方々に寄り添ってきました。',
                'philosophy'          => '「正しい情報と選択肢を届けること」が私の使命です。難しい金融の話をわかりやすく、あなたのペースで進めます。',
                'area'                => '東京都',
                'area_detail'         => '新宿区・渋谷区を中心に活動',
                'tags'                => '資産形成,老後設計,保険見直し,NISA',
                'avg_rating'          => 4.8,
                'diagnosis_type'      => 'support_seeker',
                'diagnosis_score'     => 25,
                'affiliation_url'     => 'https://example.com/profile/hit_taro',
                'verification_status' => 2,
                'plan_id'             => 0,
                'subscription_status' => 'free',
                'email_verified_at'   => now(),
            ],
            [
                'name'                => '寄り添い花子',
                'email'               => 'agent_hanako@example.com',
                'password'            => Hash::make('1111'),
                'title'               => '家計改善コンサルタント / ライフプランナー',
                'story'               => '子育て中のシングルマザーとして苦労した経験から、お金の不安を抱える方の気持ちに誰よりも共感できると思っています。',
                'philosophy'          => 'あなたの話をまずしっかり聞くことから始めます。焦らず、一緒に最善の道を見つけましょう。',
                'area'                => '大阪府',
                'area_detail'         => '大阪市・堺市',
                'tags'                => '家計改善,教育費,ライフプラン,女性向け',
                'avg_rating'          => 4.9,
                'diagnosis_type'      => 'support_seeker',
                'diagnosis_score'     => 15,
                'affiliation_url'     => 'https://example.com/profile/hanako',
                'verification_status' => 2,
                'plan_id'             => 0,
                'subscription_status' => 'free',
                'email_verified_at'   => now(),
            ],
            [
                'name'                => 'バランス設計四郎',
                'email'               => 'agent_shiro@example.com',
                'password'            => Hash::make('1111'),
                'title'               => '独立系FP / 中立的な立場の資産アドバイザー',
                'story'               => '保険会社・証券会社を経て独立。どの金融機関にも属さない中立的な立場から、本当に必要な商品だけを提案します。',
                'philosophy'          => '売り込みは一切しません。論理的に、数字で納得できる提案を心がけています。',
                'area'                => '愛知県',
                'area_detail'         => '名古屋市全域・オンライン対応可',
                'tags'                => '独立系FP,保険,投資信託,中立提案',
                'avg_rating'          => 4.6,
                'diagnosis_type'      => 'logic_seeker',
                'diagnosis_score'     => 85,
                'affiliation_url'     => 'https://example.com/profile/shiro',
                'verification_status' => 2,
                'plan_id'             => 0,
                'subscription_status' => 'free',
                'email_verified_at'   => now(),
            ],
            [
                'name'                => 'スコア10次郎',
                'email'               => 'agent_score10@example.com',
                'password'            => Hash::make('1111'),
                'title'               => '若手FP / 20〜30代向け資産形成サポーター',
                'story'               => '自身も20代で資産形成をゼロからスタート。同世代の悩みをリアルに理解した上で、等身大のアドバイスを提供します。',
                'philosophy'          => 'まずは少額から。継続できる仕組みづくりが長期的な資産形成の鍵だと信じています。',
                'area'                => '福岡県',
                'area_detail'         => '福岡市・北九州市・オンライン可',
                'tags'                => '20代,積立投資,iDeCo,NISA,初心者向け',
                'avg_rating'          => 4.3,
                'diagnosis_type'      => 'logic_seeker',
                'diagnosis_score'     => 72,
                'affiliation_url'     => 'https://example.com/profile/score10',
                'verification_status' => 2,
                'plan_id'             => 0,
                'subscription_status' => 'free',
                'email_verified_at'   => now(),
            ],
            [
                'name'                => '審査中エージェント五郎',
                'email'               => 'agent_pending@example.com',
                'password'            => Hash::make('1111'),
                'title'               => '元銀行員 / 住宅ローン専門アドバイザー',
                'story'               => '地方銀行で20年間、住宅ローン担当として勤務。退職後に独立し、金利・返済計画の相談を専門に行っています。',
                'philosophy'          => '住宅は人生最大の買い物。後悔しない借り方・返し方を一緒に考えます。',
                'area'                => '北海道',
                'area_detail'         => '札幌市',
                'tags'                => '住宅ローン,借り換え,繰上返済',
                'avg_rating'          => 0,
                'diagnosis_type'      => null,
                'diagnosis_score'     => 50,
                'affiliation_url'     => 'https://example.com/profile/goro',
                'verification_status' => 1, // 審査中
                'plan_id'             => 0,
                'subscription_status' => 'free',
                'email_verified_at'   => now(),
            ],
        ];

        foreach ($agents as $data) {
            Agent::updateOrCreate(['email' => $data['email']], $data);
        }
    }
}
