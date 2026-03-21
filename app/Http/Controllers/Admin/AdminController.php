<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * 管理者アカウント管理
 *
 * 旧PHP版: admin_register.php + admin_register_act.php 相当
 * 旧版は無保護フォームだったが、Laravel版では既存Admin認証必須（auth:admin）にて安全に実装
 */
class AdminController extends Controller
{
    public function create(): View
    {
        return view('admin.admins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Admin::create([
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'password'  => Hash::make($request->input('password')),
            'kanri_flg' => 1,
            'life_flg'  => 0,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', '管理者アカウントを作成しました。');
    }
}
