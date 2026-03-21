<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // マスターデータ（外部キーの参照元を先に実行）
            UserSeeder::class,
            AgentSeeder::class,
            AdminSeeder::class,
            // トランザクションデータ（依存関係順）
            FavoriteSeeder::class,
            InquirySeeder::class,
            ReviewSeeder::class,    // avg_rating の再計算も内包
            ProfileViewSeeder::class,
        ]);
    }
}
