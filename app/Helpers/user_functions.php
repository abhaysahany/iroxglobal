<?php

use App\Models\AdminLogDetail;
use App\Models\DirectIncome;
use App\Models\FundRequest;
use App\Models\GlobalPoolIncome;
use App\Models\ImportFund;
use App\Models\PoolUplineMember;
use App\Models\MemberDetail;
use App\Models\RankIncome;
use App\Models\WithdrawalRequest;
use App\Models\StackingIncome;
use App\Models\StakingDetail;
use App\Models\UplineMember;


function userId()
{
    $userid = "GFX" . rand(100000, 999999);
    $member = MemberDetail::where('memberid', $userid)->count();
    if ($member > 0) {
        userId();
    } else {
        return $userid;
    }
}

if (file_exists(dirname(__DIR__, 2) . '/helpers/user_function.php')) {
    require_once dirname(__DIR__, 2) . '/helpers/user_function.php';
}

if (!function_exists('generateRandomAddress')) {
    /**
     * Generate a unique random wallet address for user registration.
     * Follows the project's BEP20 format: 42 characters starting with '0x' followed by 40 hexadecimal characters.
     *
     * @return string
     */
    function generateRandomAddress(): string
    {
        do {
            $random_address = '0x' . bin2hex(random_bytes(20));
        } while (MemberDetail::where('random_address', $random_address)->exists());

        return $random_address;
    }
}

function getName($memberid)
{
    $var = MemberDetail::where('memberid', $memberid)->first();
    return $var->name;
}

function getAllData($memberid)
{
    $var = MemberDetail::where('memberid', $memberid)->first();
    return $var;
}
function stakingData($id)
{
    $var = StakingDetail::find($id);
    return $var;
}


function updateUpline($sponsorid, $memberid)
{
    if ($memberid != 'GFX123456') {
        $pool = UplineMember::where('memberid', $sponsorid)->first();
        $var = new UplineMember();
        $var->memberid = $memberid;
        $var->upline_1 = $pool->memberid;
        $var->upline_2 = $pool->upline_1;
        $var->upline_3 = $pool->upline_2;
        $var->upline_4 = $pool->upline_3;
        $var->upline_5 = $pool->upline_4;
        $var->upline_6 = $pool->upline_5;
        $var->upline_7 = $pool->upline_6;
        $var->upline_8 = $pool->upline_7;
        $var->upline_9 = $pool->upline_8;
        $var->upline_10 = $pool->upline_9;
        $var->upline_11 = $pool->upline_10;
        $var->upline_12 = $pool->upline_11;
        $var->upline_13 = $pool->upline_12;
        $var->upline_14 = $pool->upline_13;
        $var->upline_15 = $pool->upline_14;
        $var->upline_16 = $pool->upline_15;
        $var->upline_17 = $pool->upline_16;
        $var->upline_18 = $pool->upline_17;
        $var->upline_19 = $pool->upline_18;
        $var->upline_20 = $pool->upline_19;
        $var->upline_21 = $pool->upline_20;
        $var->save();
    }
}



function updateDownline($sponsorid)
{
    if ($sponsorid != 'Root') {
        $var = MemberDetail::where('memberid', $sponsorid)->first();
        $var->downline += 1;
        $var->save();
        $downline = $var->downline;
        $poolStatus = $var->pool_status;

        if ($downline == 2) {
            $var->pool_date = date('Y-m-d H:i:s');
            $var->save();
        }

        if ($downline == 4 && $poolStatus == 0) {
            $date = $var->pool_date;
            $validate = date('Y-m-d H:i:s', strtotime($date.' +10 days'));
            if (date('Y-m-d H:i:s') <= $validate) {

                $directs = MemberDetail::where([['sponsorid', $sponsorid], ['status', 'Active']])
                    ->orderBy('created_at', 'asc')
                    ->get();

                $upline_1 = $directs->pluck('memberid')->skip(2)->first();

                $upline_2 = $directs->pluck('memberid')->skip(3)->first();

                $pool = new PoolUplineMember;
                $pool->pool_no = 1;
                $pool->memberid = $var->memberid;
                $pool->upline_1 = $upline_1;
                $pool->upline_2 = $upline_2;
                $pool->save();

                $var->pool_status = 1;
                $var->pool_date = date('Y-m-d H:i:s');
                $var->save();
            }
        }

        if ($downline == 6 && $poolStatus == 1) {
            $date = $var->pool_date;
            $validate = date('Y-m-d H:i:s', strtotime($date.' +30 days'));
            if (date('Y-m-d H:i:s') <= $validate) {

                $directs = MemberDetail::where([['sponsorid', $sponsorid], ['status', 'Active']])
                    ->orderBy('team_biz', 'desc')
                    ->get();

                $upline_1 = $directs->pluck('memberid')->skip(4)->first();

                $upline_2 = $directs->pluck('memberid')->skip(5)->first();

                $pool = new PoolUplineMember;
                $pool->pool_no = 2;
                $pool->memberid = $var->memberid;
                $pool->upline_1 = $upline_1;
                $pool->upline_2 = $upline_2;
                $pool->save();

                $var->pool_status = 2;
                $var->save();
            }
        }
    }
}

function poolTimer($memberid)
{
    $timer = 'on';
    $memData = MemberDetail::where('memberid', $memberid)->first();
    if ($memData && $memData->status == 'Active' && $memData->pool_date != null) {
        $start = date('Y-m-d H:i:s', strtotime($memData->pool_date));
        if ($memData->pool_status == 0) {
            $end = date('Y-m-d H:i:s', strtotime($memData->pool_date.' +10 days'));
        } elseif ($memData->pool_status == 1) {
            $end = date('Y-m-d H:i:s', strtotime($memData->pool_date.' +30 days'));
        } else {
            return $timer;
        }

        $count = MemberDetail::where('sponsorid', $memberid)->whereBetween('activated_at', [$start, $end])->count();
        if ($count >= 4 && $memData->pool_status == 0) {
            $timer = 'off';
        } elseif ($count >= 6 && $memData->pool_status == 1) {
            $timer = 'off';
        }
    }

    return $timer;
}

function team_invest_update($sponsorid, $amount)
{

    $limit = 22;
    for ($i = 1; $i < $limit; $i++) {
        if ($sponsorid != 'Root') {
            $query = MemberDetail::where('memberid', $sponsorid)->first();
            $query->team_biz += $amount;
            $query->save();
            $sponsorid = $query->sponsorid;
        } else {
            break;
        }
    }
}

function team_count_update($sponsorid)
{

    $limit = 1000;
    for ($i = 1; $i < $limit; $i++) {
        if ($sponsorid != 'Root') {
            $query = MemberDetail::where('memberid', $sponsorid)->first();
            $query->team += 1;
            $query->save();
            $sponsorid = $query->sponsorid;
        } else {
            break;
        }
    }
}

// total incomes


function totalIncome($memberid)
{
    $total = totalDailyIncome($memberid) + totalReferralIncome($memberid) + totalRankIncome($memberid) + totalGlobalRacIncome($memberid) + totalGlobalRacStarIncome($memberid);
    return $total;
}

function totalDailyIncome($memberid)
{
    $var = StackingIncome::where([['status', 'Paid'], ['memberid', $memberid]])->sum('amount');
    return $var;
}

function totalReferralIncome($memberid)
{
    $var = DirectIncome::where([['status', 'Paid'], ['memberid', $memberid]])->sum('amount');
    return $var;
}

function totalRankIncome($memberid)
{
    $var = RankIncome::where([['status', 'Paid'], ['memberid', $memberid]])->sum('amount');
    return $var;
}

function totalGlobalRacIncome($memberid)
{
    $var = GlobalPoolIncome::where([['status', 'Paid'], ['pool_no', 1], ['memberid', $memberid]])->sum('amount');
    return $var;
}

function totalGlobalRacStarIncome($memberid)
{
    $var = GlobalPoolIncome::where([['status', 'Paid'], ['pool_no', 2], ['memberid', $memberid]])->sum('amount');
    return $var;
}

// todays incomes



function todayIncome($memberid)
{
    $total = todayDailyIncome($memberid) + todayReferralIncome($memberid) + todayRankIncome($memberid) + todayGlobalRacIncome($memberid) + todayGlobalRacStarIncome($memberid);
    return $total;
}

function todayDailyIncome($memberid)
{
    $var = StackingIncome::where([['status', 'Paid'], ['memberid', $memberid]])->whereDate('created_at', date('Y-m-d'))->sum('amount');
    return $var;
}

function todayReferralIncome($memberid)
{
    $var = DirectIncome::where([['status', 'Paid'], ['memberid', $memberid]])->whereDate('created_at', date('Y-m-d'))->sum('amount');
    return $var;
}

function todayRankIncome($memberid)
{
    $var = RankIncome::where([['status', 'Paid'], ['memberid', $memberid]])->whereDate('created_at', date('Y-m-d'))->sum('amount');
    return $var;
}

function todayGlobalRacIncome($memberid)
{
    $var = GlobalPoolIncome::where([['status', 'Paid'], ['memberid', $memberid], ['pool_no', 1]])->whereDate('created_at', date('Y-m-d'))->sum('amount');
    return $var;
}

function todayGlobalRacStarIncome($memberid)
{
    $var = GlobalPoolIncome::where([['status', 'Paid'], ['memberid', $memberid], ['pool_no', 2]])->whereDate('created_at', date('Y-m-d'))->sum('amount');
    return $var;
}

function checkStakingStatus($memberid)
{
    $count = StakingDetail::where([['status', 'Active'], ['memberid', $memberid]])->count();
    return $count;
}

function checkRoiStatus($memberid)
{
    $count = StackingIncome::where([['status', 'Unpaid'], ['memberid', $memberid]])->whereDate('created_at', date('Y-m-d'))->count();
    return $count;
}

function totalRewardIncome($memberid)
{
    $var = RewardIncome::where([['status', 'Paid'], ['memberid', $memberid]])->sum('amount');
    return $var;
}

function totalRoyaltyIncome($memberid)
{
    $var = SalaryIncome::where([['status', 'Paid'], ['memberid', $memberid]])->sum('amount');
    return $var;
}


function totalLevelMembers($memberid)
{
    $members = MemberDetail::where('sponsorid', $memberid)->pluck('memberid');
    $total = count($members);
    while (count($members) != 0) {
        $members = MemberDetail::whereIn('sponsorid', $members)->pluck('memberid');
        $total += count($members);
    }

    return $total;
}

function levelMembersCount($memberid, $level)
{
    $i = 2;
    $total = 0;
    if ($level == 1) {
        $members = MemberDetail::where('sponsorid', $memberid)->pluck('memberid');
        $total = count($members);
    } else {
        $members = MemberDetail::where('sponsorid', $memberid)->pluck('memberid');
        while (count($members) != 0) {
            $members = MemberDetail::whereIn('sponsorid', $members)->pluck('memberid');
            $total = count($members);
            if ($level == $i) {
                break;
            }
            $i++;
        }
    }

    return $total;
}

function totalDownlines($memberid)
{
    $members = MemberDetail::where('sponsorid', $memberid)->count();
    return $members;
}

function todayWithdrawals($memberid)
{
    $var = WithdrawalRequest::where([['memberid', $memberid], ['status', 'Approved']])->whereDate('created_at', date('Y-m-d'))->sum('gross_amount');
    return $var;
}

function totalWithdrawals($memberid)
{
    $var = WithdrawalRequest::where([['memberid', $memberid], ['status', 'Approved']])->sum('gross_amount');
    return $var;
}

function totalsingleLevel($memberid, $level)
{
    $upline = 'upline_' . $level;
    $var = UplineMember::where($upline, $memberid)->count();
    return $var;
}

function getSponsorid($memberid)
{
    $var = MemberDetail::where('memberid', $memberid)->first();
    return $var['sponsorid'];
}

function getLvlUsers($memberid)
{
    $count = MemberDetail::where([['sponsorid', $memberid], ['investment', '>=', 100]])->count();
    return $count;
}

function totalDeposit($memberid)
{
    $var = ImportFund::where([['memberid', $memberid], ['status', 'Approved']])->sum('amount');
    return $var;
}

function todayDeposit($memberid)
{
    $var = ImportFund::where([['memberid', $memberid], ['status', 'Approved']])->whereDate('created_at', date('Y-m-d'))->sum('amount');
    return $var;
}


function totalImportFund($memberid)
{
    $var = ImportFund::where([['memberid', $memberid], ['status', 'Approved']])->sum('amount');
    return $var;
}

function todaysPackage($memberid)
{
    $var = StakingDetail::where('memberid', $memberid)->whereDate('created_at', date('Y-m-d'))->sum('invest_amount');
    return $var;
}

function totalPackage($memberid)
{
    $var = StakingDetail::where('memberid', $memberid)->whereDate('created_at', date('Y-m-d'))->sum('invest_amount');
    return $var;
}

function directBusiness($memberid)
{
    $first = MemberDetail::where([['sponsorid', $memberid], ['status', 'Active']])->sum('investment');
    return $first;
}

function teamBusiness($memberid)
{
    $first = MemberDetail::where([['sponsorid', $memberid], ['status', 'Active']])->sum('investment');
    $teamBiz = MemberDetail::where([['sponsorid', $memberid], ['status', 'Active']])->sum('team_biz') - $first;
    return $teamBiz;
}

function totalAddFund($memberid)
{
    $sum = FundRequest::where([['memberid', $memberid], ['status', 'Accepted']])->sum('amount');
    return $sum;
}

function selfBiz($memberid)
{
    $sum = StakingDetail::where([['memberid', $memberid], ['status', 'Active']])->sum('package');
    return $sum;
}

function monthlySelfBiz($memberid)
{
    $sum = StakingDetail::where([['memberid', $memberid], ['status', 'Active']])->whereMonth('created_at', date('m'))->sum('package');
    return $sum;
}

function checkUserStatus($memberid)
{
    $var = MemberDetail::where('memberid', $memberid)->first();
    return $var->status;
}

function getDateAfterBusinessDays(int $businessDays, array $holidays = [], string $format = 'Y-m-d'): string
{
    $date = new DateTime();
    $daysAdded = 0;

    while ($daysAdded < $businessDays) {
        // Move to the next day
        $date->modify('+1 day');

        $dayOfWeek = (int)$date->format('N'); // 1 (Mon) through 7 (Sun)
        $currentString = $date->format('Y-m-d');

        // Check if the day is a weekend (6 or 7) OR exists in the holiday array
        if ($dayOfWeek >= 6 || in_array($currentString, $holidays)) {
            // Skip this day; do not increment $daysAdded
            continue;
        }

        $daysAdded++;
    }

    return $date->format($format);
}

function createByID($memberid)
{
    $var = AdminLogDetail::where('updated_id', $memberid)->whereIn('task', ['Back Date Registration', 'Dummy Registration'])->get();
    if (isset($var)) {
        foreach ($var as $value) {
            return $adminId = $value->updated_by;
        }
    } else {
        return $adminId = $memberid;
    }
}

function paidSatkingIncome($staking_id)
{
    $sum = StackingIncome::where('staking_id', $staking_id)->sum('amount');
    return $sum;
}

function myDirectBiz($memberid)
{
    $sum = MemberDetail::where('sponsorid', $memberid)->sum('self_biz');
    return $sum;
}

function getTotalLvllBiz($memberid, $level)
{
    $members[] = $memberid;
    $total = 0;
    for ($i = 1; $i < 6; $i++) {

        if ($level == $i) {
            $total = MemberDetail::whereIn('sponsorid', $members)->sum('self_biz');
            break;
        }
        $members = MemberDetail::whereIn('sponsorid', $members)->pluck('memberid');
    }
    return $total;
}

function getMonthlyLvllBiz($memberid, $level)
{
    $members[] = $memberid;
    $total = 0;
    for ($i = 1; $i < 8; $i++) {

        if ($level >= $i) {
            $total += MemberDetail::whereIn('sponsorid', $members)->sum('self_biz');
            $members = MemberDetail::whereIn('sponsorid', $members)->pluck('memberid');
        } else {
            break;
        }
    }
    return $total;
}
// function liftInvestmenT($memberid, $level)
// {
//     $members[] = $memberid;
//     $total = 0;
//     for ($i = 1; $i <= 5; $i++) {

//         if ( $level == $i) {
//             $total += MemberDetail::whereIn('sponsorid', $members)->sum('self_biz');

//         } else {
//             break;
//         }
//         $members = MemberDetail::whereIn('sponsorid', $members)->pluck('memberid');
//     }
//     return  $total;
// }

// function liftInvestment($memberid , $level)
// {
//     $level = 1;
//     $members = [$memberid];
//     $total_investment = 0;

//     for ($i = 1; $i <= 5; $i++) {

//         if ($level == $i) {
//             $level_total = MemberDetail::whereIn('sponsorid', $members)->sum('self_biz');

//             $total_investment += $level_total;

//             $members = MemberDetail::whereIn('sponsorid', $members)->pluck('memberid');
//         } else {
//             break;
//         }
//     }

//     return $total_investment;
// }
function liftInvestment($memberid, $level)
{
    $members[] = $memberid;
    $total = 0;
    for ($i = 1; $i <= 5; $i++) {
        $members = MemberDetail::whereIn('sponsorid', $members)->pluck('memberid');
        if ($i == $level) {
            $total += MemberDetail::whereIn('memberid', $members)->sum('self_biz');
            break;
        }
    }
    return $total;
}
