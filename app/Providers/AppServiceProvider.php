<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 特権管理者（kanri_flg = 1）のみ、機密情報の閲覧・操作を許可する Gate。
        // nullable 型ヒントを使うことで、デフォルトガードが未認証でも closure が呼ばれる。
        Gate::define('view-sensitive-data', function (?Authenticatable $user) {
            $admin = Auth::guard('admin')->user();
            return $admin !== null && $admin->kanri_flg == 1;
        });
    }
}
