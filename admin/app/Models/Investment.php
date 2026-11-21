<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends Model
{
    protected $fillable = ['member_id', 'amount', 'plan_name', 'roi_percentage', 'duration_days', 'start_date', 'end_date', 'status'];

    protected $casts = ['amount' => 'decimal:2', 'roi_percentage' => 'decimal:2', 'start_date' => 'date', 'end_date' => 'date'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
