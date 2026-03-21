<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InquiryMessage extends Model
{
    protected $fillable = ['inquiry_id', 'sender_type', 'message', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }
}
