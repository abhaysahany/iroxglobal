<?php

use App\Models\DirectIncome;
use App\Models\GlobalPoolIncome;
use App\Models\ImportFund;
use App\Models\MemberDetail;
use App\Models\RankIncome;
use App\Models\StackingIncome;
use App\Models\SupportTicket;
use App\Models\TotalMonthlyIncome;
use App\Models\WithdrawalRequest;

function totalMembersadmin()
{
    $count = MemberDetail::all();
    return count($count);
}

function totalActiveMembers()
{
    $count = MemberDetail::where('status', 'Active')->get();
    return count($count);
}

function totalTempMembers()
{
    $count = MemberDetail::where('status', 'Temp')->count();
    return $count;
}

function totalblockedMembers()
{
    $count = MemberDetail::where('status', 'Blocked')->count();
    return $count;
}

function todaysWithdrawal()
{
    $sum = WithdrawalRequest::where('status', 'Approved')->whereDate('payment_date', date('Y-m-d'))->sum('gross_amount');
    return $sum;
}

function totalWithdrawal()
{
    $sum = WithdrawalRequest::where('status', 'Approved')->sum('gross_amount');
    return $sum;
}

function totalDeposit()
{
    $sum = ImportFund::where('status', 'Approved')->sum('amount');
    return $sum;
}

function todayDeposit()
{
    $sum = ImportFund::where('status', 'Approved')->whereDate('created_at', date('Y-m-d'))->sum('amount');
    return $sum;
}


function totalP2PWallet()
{
    $sum = MemberDetail::where('status', 'Active')->sum('p2p_wallet');
    return $sum;
}

function totalWallet()
{
    $sum = MemberDetail::where('status', 'Active')->sum('wallet');
    return $sum;
}


// total income 

function totalIncomes()
{
    $total = totalReferralIncome() + totalDailyIncome() + totalGlobalRacIncome() + totalGlobalRacStarIncome() + totalRankIncome();
    return $total;
}

// total incomes 
function totalReferralIncome()
{
    $sum = DirectIncome::where('status', 'Paid')->sum('amount');
    return $sum;
}

function totalDailyIncome()
{
    $sum = StackingIncome::where('status', 'Paid')->sum('amount');
    return $sum;
}

function totalGlobalRacIncome()
{
    $sum = GlobalPoolIncome::where([['status', 'Paid'], ['pool_no', 1]])->sum('amount');
    return $sum;
}

function totalGlobalRacStarIncome()
{
    $sum = GlobalPoolIncome::where([['status', 'Paid'], ['pool_no', 2]])->sum('amount');
    return $sum;
}

function totalRankIncome()
{
    $sum = RankIncome::where('status', 'Paid')->sum('amount');
    return $sum;
}

function totalgrossAmount()
{
    $count = WithdrawalRequest::where('status', 'Approved')->sum('gross_amount');
    return $count;
}

function totalnetAmount()
{
    $count = WithdrawalRequest::where('status', 'Approved')->sum('net_amount');
    return $count;
}

function totaldeductions()
{
    $count = WithdrawalRequest::where('status', 'Approved')->sum('service_charge');
    return $count;
}

function newWithdrawalRequest()
{
    $sum = WithdrawalRequest::where('status', 'Pending')->count();

    return $sum;
}

function newSupportTicket()
{
    $count = SupportTicket::where('support_status', 'New')->count();

    return $count;
}

function newKycRequests()
{
    $count = MemberDetail::where('kyc_status', 'Submitted')->count();

    return $count;
}
