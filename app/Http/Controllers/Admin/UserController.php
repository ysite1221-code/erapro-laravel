<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->input('q', '');

        $query = User::query()->withCount(['favorites', 'inquiries', 'reviews']);

        if (!empty($q)) {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->latest()->paginate(30)->withQueryString();

        return view('admin.users.index', compact('users', 'q'));
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update(['life_flg' => $user->life_flg ? 0 : 1]);

        $msg = $user->life_flg
            ? "{$user->name} のアカウントを停止しました。"
            : "{$user->name} のアカウントを有効化しました。";

        return redirect()
            ->route('admin.users.index')
            ->with('status', $msg);
    }
}
