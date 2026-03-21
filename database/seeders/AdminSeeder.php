<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin_test@example.com'],
            [
                'name'      => '運営テスト管理者',
                'email'     => 'admin_test@example.com',
                'password'  => Hash::make('1111'),
                'kanri_flg' => 1,
                'life_flg'  => 0,
            ]
        );
    }
}
