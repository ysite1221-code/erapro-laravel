<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inquiry extends Model
{
    protected $fillable = [
        'user_id',
        'agent_id',
        'status',
        'purpose',
        'trigger',
        'preferred_style',
        'note',
        'completion_note',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(InquiryMessage::class)->orderBy('created_at');
    }

    public function latestMessage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(InquiryMessage::class)->latestOfMany();
    }
}
