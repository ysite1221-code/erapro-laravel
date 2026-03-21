<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'               => '診断済ユーザー',
                'email'              => 'user_diag@example.com',
                'password'           => Hash::make('1111'),
                'diagnosis_type'     => 'support_seeker',
                'diagnosis_score'    => 30,
                'email_verified_at'  => now(),
            ],
            [
                'name'               => 'メッセージ送信ユーザー',
                'email'              => 'user_msg@example.com',
                'password'           => Hash::make('1111'),
                'diagnosis_type'     => 'logic_seeker',
                'diagnosis_score'    => 80,
                'email_verified_at'  => now(),
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(['email' => $data['email']], $data);
        }
    }
}
