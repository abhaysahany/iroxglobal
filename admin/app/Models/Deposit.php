<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    protected $fillable = ['member_id', 'amount', 'payment_mode', 'transaction_id', 'screenshot', 'status', 'admin_remark'];

    protected $casts = ['amount' => 'decimal:2'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
