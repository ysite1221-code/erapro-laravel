<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminCreatedNotification;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminManagementController extends Controller
{
    public function index(): View
    {
        abort_unless(Gate::check('view-sensitive-data'), 403, 'この操作は特権管理者のみ実行できます。');

        $admins = Admin::latest()->paginate(20);

        return view('admin.admins.index', compact('admins'));
    }

    public function create(): View
    {
        abort_unless(Gate::check('view-sensitive-data'), 403, 'この操作は特権管理者のみ実行できます。');

        return view('admin.admins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Gate::check('view-sensitive-data'), 403, 'この操作は特権管理者のみ実行できます。');

        $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
        ]);

        // パスワード自動生成（平文はメール送信用に保持）
        $plainPassword = Str::random(12);

        $admin = Admin::create([
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'password'  => Hash::make($plainPassword),
            'kanri_flg' => 1,
            'life_flg'  => 0,
        ]);

        // 通知メール送信
        try {
            Mail::to($admin->email)->send(new AdminCreatedNotification($admin, $plainPassword));
        } catch (\Throwable $e) {
            \Log::warning('AdminCreatedNotification mail failed: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.admins.index')
            ->with('status', "{$admin->name} の管理者アカウントを作成し、ログイン情報をメールで送信しました。");
    }
}
