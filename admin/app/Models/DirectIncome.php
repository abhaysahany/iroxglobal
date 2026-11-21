<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DirectIncome extends Model
{
    protected $casts = [
        'amount' => 'decimal:2',
        'rate' => 'decimal:2',
        'pay_date' => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberDetail::class, 'memberid', 'memberid');
    }
}
