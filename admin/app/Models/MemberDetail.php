<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDetail extends Model
{
    protected $table = 'member_details';

    protected $fillable = [
        'registration_mode',
        'type_id',
        'memberid',
        'sponsorid',
        'sponsor_name',
        'name',
        'mobile',
        'email',
        'password',
        'txn_password',
        'father_name',
        'gender',
        'occupation',
        'nominee',
        'relation',
        'dob',
        'address',
        'city',
        'state',
        'country',
        'location',
        'wallet_address',
        'profile_image',
        'wallet',
        'p2p_wallet',
        'downline',
        'package',
        'package_name',
        'investment',
        'team',
        'self_biz',
        'team_biz',
        'monthly_biz',
        'rank',
        'reward_status',
        'reward_rank',
        'salary_status',
        'salary_date',
        'kyc_status',
        'status',
        'comment',
        'back_joining_date',
        'activated_at',
        'activated_by',
    ];
}
