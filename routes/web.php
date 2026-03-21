<?php

use App\Http\Controllers\Admin\AgentController as AdminAgentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KycController as AdminKycController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\InquiryController as AgentInquiryController;
use App\Http\Controllers\Agent\ProfileController as AgentProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\AgentSearchController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\InquiryController;
use App\Http\Controllers\User\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 一般公開ルート（ログイン不要）
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// 検索・プロフィール（未ログインでもアクセス可）
Route::get('/search', [AgentSearchController::class, 'index'])->name('search');
Route::get('/agent-profile/{id}', [AgentSearchController::class, 'show'])->name('agent.profile');

// 診断
Route::get('/diagnosis', [DiagnosisController::class, 'index'])->name('diagnosis');
Route::post('/diagnosis', [DiagnosisController::class, 'store'])->name('diagnosis.store');
Route::get('/diagnosis/result', [DiagnosisController::class, 'result'])->name('diagnosis.result');

/*
|--------------------------------------------------------------------------
| User 認証ルート (guard: user)
|--------------------------------------------------------------------------
*/
Route::get('/login',  [LoginController::class, 'showUserLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'userLogin']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// User 新規登録
Route::get('/register',  [RegisterController::class, 'showUserRegisterForm'])->name('user.register');
Route::post('/register', [RegisterController::class, 'storeUser'])->name('user.register.store');
Route::get('/verify-notice', fn() => view('auth.verify_notice', [
    'resendRoute' => route('user.verify.resend'),
    'loginRoute'  => route('login'),
]))->name('user.verify.notice');
Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verifyUserEmail'])
    ->middleware('signed')
    ->name('user.email.verify');
Route::post('/email/verify/resend', [RegisterController::class, 'resendUserVerification'])->name('user.verify.resend');

// User パスワードリセット
Route::get('/forgot-password',         [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password',        [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}',  [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password',         [PasswordResetController::class, 'resetPassword'])->name('password.update');

// User 認証必須
Route::middleware('auth:user')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/user/interests/toggle', [UserDashboardController::class, 'toggleInterest'])->name('user.interests.toggle');
    Route::post('/user/withdraw', [UserDashboardController::class, 'withdraw'])->name('user.withdraw');
    Route::post('/favorite/toggle', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
    Route::get('/inquiry/{agentId}',   [InquiryController::class, 'create'])->name('inquiry.create');
    Route::post('/inquiry',            [InquiryController::class, 'store'])->name('inquiry.store');
    Route::get('/review/{agentId}',    [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review',             [ReviewController::class, 'store'])->name('review.store');
    Route::get('/inquiries',           [InquiryController::class, 'index'])->name('user.inquiries.index');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('user.inquiries.show');
});

/*
|--------------------------------------------------------------------------
| Agent 認証ルート (guard: agent, prefix: /agent)
|--------------------------------------------------------------------------
*/
Route::prefix('agent')->name('agent.')->group(function () {
    Route::get('/login',  [LoginController::class, 'showAgentLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'agentLogin']);

    // Agent 新規登録
    Route::get('/register',  [RegisterController::class, 'showAgentRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'storeAgent'])->name('register.store');
    Route::get('/verify-notice', fn() => view('auth.verify_notice', [
        'resendRoute' => route('agent.verify.resend'),
        'loginRoute'  => route('agent.login'),
    ]))->name('verify.notice');
    Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verifyAgentEmail'])
        ->middleware('signed')
        ->name('email.verify');
    Route::post('/email/verify/resend', [RegisterController::class, 'resendAgentVerification'])->name('verify.resend');

    // Agent パスワードリセット
    Route::get('/forgot-password',        [PasswordResetController::class, 'showAgentForgotForm'])->name('password.request');
    Route::post('/forgot-password',       [PasswordResetController::class, 'sendAgentResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showAgentResetForm'])->name('password.reset');
    Route::post('/reset-password',        [PasswordResetController::class, 'resetAgentPassword'])->name('password.update');

    // Agent 認証必須
    Route::middleware('auth:agent')->group(function () {
        Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
        Route::post('/withdraw', [AgentDashboardController::class, 'withdraw'])->name('withdraw');
        Route::get('/profile/edit',    [AgentProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update',  [AgentProfileController::class, 'update'])->name('profile.update');
        Route::get('/kyc',  [AgentProfileController::class, 'showKycForm'])->name('kyc.form');
        Route::post('/kyc', [AgentProfileController::class, 'submitKyc'])->name('kyc.submit');
        Route::get('/inquiries',                   [AgentInquiryController::class, 'index'])->name('inquiries.index');
        Route::get('/inquiries/{inquiry}',         [AgentInquiryController::class, 'show'])->name('inquiries.show');
        Route::patch('/inquiries/{inquiry}/status',[AgentInquiryController::class, 'updateStatus'])->name('inquiries.update_status');
    });
});

/*
|--------------------------------------------------------------------------
| Admin 認証ルート (guard: admin, prefix: /admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [LoginController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'adminLogin'])->name('login.post');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/kyc/{agent}',    [AdminKycController::class, 'show'])->name('kyc.show');
        Route::patch('/kyc/{agent}',  [AdminKycController::class, 'update'])->name('kyc.update');
        Route::get('/agents',         [AdminAgentController::class, 'index'])->name('agents.index');
        Route::patch('/agents/{agent}/toggle-status', [AdminAgentController::class, 'toggleStatus'])->name('agents.toggle_status');
        Route::get('/users',          [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle_status');
    });
});
