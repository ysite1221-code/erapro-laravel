<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Agent extends Authenticatable
{
    use HasFactory, Notifiable;

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
