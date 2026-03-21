<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['user_id', 'agent_id', 'reason', 'detail', 'status', 'reporter_type'];
}
