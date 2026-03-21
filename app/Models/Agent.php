<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class Agent extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    public function sendPasswordResetNotification($token): void
    {
        $url = route('agent.password.reset', ['token' => $token, 'email' => $this->email]);
        Mail::raw(
            "ERAPROパスワードリセットのご案内\n\n"
            . "以下のリンクからパスワードをリセットしてください（有効期限: 60分）。\n\n"
            . "{$url}\n\n"
            . "※ 心当たりのない場合は無視してください。",
            fn($m) => $m->to($this->email)->subject('【ERAPRO募集人】パスワードリセット')
        );
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'title',
        'story',
        'philosophy',
        'profile_img',
        'area',
        'area_detail',
        'tags',
        'avg_rating',
        'diagnosis_type',
        'diagnosis_score',
        'type_id',
        'affiliation_url',
        'verification_status',
        'plan_id',
        'stripe_customer_id',
        'subscription_status',
        'email_notification_flg',
        'life_flg',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function profileViews(): HasMany
    {
        return $this->hasMany(ProfileView::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
