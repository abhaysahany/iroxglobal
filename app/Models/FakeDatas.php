<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FakeDatas extends Model
{
    protected $table = 'fake_datas';

    protected $casts = [
        'my_direct' => 'integer',
        'total_team' => 'integer',
        'global_members' => 'integer',
        'total_withdrawal' => 'decimal:2',
        'p2p_wallet' => 'decimal:2',
        'rank_income' => 'decimal:2',
        'daily_income' => 'decimal:2',
        'referral_income' => 'decimal:2',
        'global_rec_income' => 'decimal:2',
        'global_rec_star_income' => 'decimal:2',
    ];
}
