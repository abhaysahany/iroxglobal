<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activation extends Model
{
    protected $fillable = ['member_id', 'package_amount', 'activated_by', 'status', 'activated_at'];

    protected $casts = ['package_amount' => 'decimal:2', 'activated_at' => 'datetime'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
