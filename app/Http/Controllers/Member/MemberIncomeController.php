<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\DirectIncome;
use App\Models\GlobalPoolIncome;
use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\RankIncome;
use App\Models\StackingIncome;

class MemberIncomeController extends Controller
{
    public function daily()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['dData'] = StackingIncome::where('memberid', $memberid)->get();
        return view('member.incomes.daily-income')->with($result);
    }

    public function referral()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['rData'] = DirectIncome::where('memberid', $memberid)->get();
        return view('member.incomes.referral-income')->with($result);
    }

    public function level()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['rData'] = LevelIncome::where('memberid', $memberid)->latest()->get();
        return view('member.incomes.level-income')->with($result);
    }

    public function rank()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['rData'] = RankIncome::where('memberid', $memberid)->get();
        return view('member.incomes.rank-income')->with($result);
    }

    public function globalRec()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = GlobalPoolIncome::where('memberid', $memberid)->where('pool_no', 1)->get();
        return view('member.incomes.globalrec-income')->with($result);
    }

    public function globalRecStar()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = GlobalPoolIncome::where('memberid', $memberid)->where('pool_no', 2)->get();
        return view('member.incomes.globalrecstar-income')->with($result);
    }
}
