<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyCount extends Model
{
    protected $table = 'weekly_counts';

    protected $fillable = [
        'memberid',
        'prev_count',
        'total_count',
        'type',
        'downid',
    ];
}
