<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * These fields can be saved from the settings form.
     */
    protected $fillable = [
        'website_name',
        'website_email',
        'website_phone',
        'website_logo',
        'website_logo_width',
        'website_logo_height',
        'currency',
        'set_weekly_count',
        'address',
        'company_bsc_wallet_address',
        'minimum_deposit_bnb',
        'maximum_deposit_bnb',
        'deposit_gateway_state',
    ];

    protected $casts = [
        'website_logo_width' => 'integer',
        'website_logo_height' => 'integer',
        'set_weekly_count' => 'integer',
        'minimum_deposit_bnb' => 'decimal:8',
        'maximum_deposit_bnb' => 'decimal:8',
    ];
}
