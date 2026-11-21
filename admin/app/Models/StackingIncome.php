<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StackingIncome extends Model
{
    protected $casts = [
        'date' => 'date',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberDetail::class, 'memberid', 'memberid');
    }
}
