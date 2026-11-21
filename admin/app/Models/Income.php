<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    protected $fillable = ['member_id', 'income_type', 'amount', 'description', 'status', 'payout_date'];

    protected $casts = ['amount' => 'decimal:2', 'payout_date' => 'date'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
