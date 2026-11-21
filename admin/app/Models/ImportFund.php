<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportFund extends Model
{
    protected $table = 'import_funds';

    protected $fillable = [
        'memberid',
        'amount',
        'orderid',
        'txnid',
        'type',
        'wallet_type',
        'status',
        'mode',
        'added_by',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberDetail::class, 'memberid', 'memberid');
    }
}
