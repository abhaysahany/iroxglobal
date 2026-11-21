<?php

use App\Models\MemberDetail;
use App\Models\StackingIncome;
use App\Models\DirectIncome;
use App\Models\LevelIncome;
use App\Models\StakingDetail;
use App\Models\SetRate;
use App\Models\MonthlyIncome;
use App\Models\PackageDetail;
use App\Models\RankIncome;
use App\Models\RewardIncome;
use App\Models\WalletTransfer;

function walletTransfer($memberid, $amount, $type, $wallet, $walletType, $text)
{
    if ($type == 'debit') {
        $ins = new WalletTransfer();
        $ins->memberid = $memberid;
        $ins->walletType = $walletType;
        $ins->debit = $amount;
        $ins->balance = $wallet + $amount;
        $ins->particular = $text;
        $ins->save();
    } elseif ($type == 'credit') {
        $ins = new WalletTransfer();
        $ins->memberid = $memberid;
        $ins->walletType = $walletType;
        $ins->credit = $amount;
        $ins->balance = $wallet - $amount;
        $ins->particular = $text;
        $ins->save();
    }
}

function p2pwalletTransfer($memberid, $amount, $type, $p2pwallet, $walletType, $text, $date = null)
{
    if ($type == 'debit') {
        $ins = new WalletTransfer();
        $ins->memberid = $memberid;
        $ins->walletType = $walletType;
        $ins->debit = $amount;
        $ins->balance = $p2pwallet + $amount;
        $ins->particular = $text;
        $ins->created_at = $date ? $date : date('Y-m-d H:i:s');
        $ins->save();
    } elseif ($type == 'credit') {
        $ins = new WalletTransfer();
        $ins->memberid = $memberid;
        $ins->walletType = $walletType;
        $ins->credit = $amount;
        $ins->balance = $p2pwallet - $amount;
        $ins->particular = $text;
        $ins->created_at = $date ? $date : date('Y-m-d H:i:s');
        $ins->save();
    }
}

// function capping($memberid)
// {
//     $staking = StakingDetail::where([['memberid', $memberid], ['status', 'Active']])->orderBy('id', 'desc')->first();

//     if (!$staking) {
//         return 0;
//     }
//     $investment = $staking->invest_amount;
//     if ($investment <= 0) {
//         return 0;
//     }
//     if (!$staking->invest_date) {
//         return 0;
//     }
//     $startDate = strtotime($staking->invest_date);
//     $today = time();
//     $daysCompleted = floor(($today - $startDate) / 86400);

//     if ($daysCompleted < 365) {
//         $cappingYear = 1;
//         $multiplier = 3;
//     } elseif ($daysCompleted < 730) {
//         $cappingYear = 2;
//         $multiplier = 4;
//     } elseif ($daysCompleted < 1095) {
//         $cappingYear = 3;
//         $multiplier = 5;
//     } else {
//         return 0;
//     }

//     if (($staking->capping_year) != $cappingYear) {
//         $staking->capping_year = $cappingYear;
//         $staking->total_installments = 365;
//         $staking->save();
//     }

//     return $investment * $multiplier;
// }

function directIncome($sponsorid, $memberid, $name, $package, $type)
{
    if ($sponsorid != 'Root') {
        $var = MemberDetail::where('memberid', $sponsorid)->first();
        if ($var->status == 'Active') {

            $cAmount = $package * 5 / 100;
            $amount = $cAmount;
            if ($amount > 0) {

                $insert = new DirectIncome();
                $insert->memberid = $sponsorid;
                $insert->amount = $amount;
                $insert->activatingId = $memberid;
                $insert->package = $package;
                $insert->type = $type;
                $insert->name = $name;
                $insert->status = 'Paid';
                $insert->save();

                $wallet = $var->wallet;
                $var->wallet += $amount;
                $var->save();
                walletTransfer($sponsorid, $amount, 'debit', $wallet, 'Direct Referral Income', 'Direct Referral Income Amount Added into wallet.');
            }
        }
    }
}

function Income3xachieved($memberid)
{
    $var =  MemberDetail::where([['memberid', $memberid], ['status', '!=', 'Temp']])->first();
    if ($var) {

        $date = $var['activated_at'];
        $direct = DirectIncome::where([['status', 'Paid'], ['memberid', $memberid], ['created_at', '>=', $date]])->sum('amount');
        $rank = RankIncome::where([['status', 'Paid'], ['memberid', $memberid], ['created_at', '>=', $date]])->sum('amount');

        $totalAmount =  $direct + $rank;
    } else {
        $totalAmount = 0;
    }
    return $totalAmount;
}


function getmonthlyincome($upline)
{
    static $cache = [];

    if (empty($upline)) {
        return 0;
    }

    if (isset($cache[$upline])) {
        return $cache[$upline];
    }

    $start = now()->subMonth()->startOfMonth();
    $end   = now()->subMonth()->endOfMonth();

    $rankIncome = RankIncome::where('memberid', $upline)
        ->where('status', 'Paid')->whereBetween('created_at', [$start, $end])->sum('amount');

    $stackingIncome = StackingIncome::where('memberid', $upline)
        ->where('status', 'Paid')->whereBetween('created_at', [$start, $end])->sum('amount');

    return $cache[$upline] = ($rankIncome + $stackingIncome);
}

