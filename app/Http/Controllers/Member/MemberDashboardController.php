<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberDetail;
use App\Models\DashImage;
use App\Models\Announcement;
use App\Models\FakeDatas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberDashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['dashMsg'] = Announcement::orderby('title', 'asc')->get();

        if (!$result['data'] && !empty($memberid) && is_numeric($memberid)) {
            $result['data'] = MemberDetail::find($memberid);
            if ($result['data']) {
                $request->session()->put('MEMBER_ID', $result['data']->memberid);
            }
        }

        if (!$result['data']) {
            $request->session()->forget('MEMBER_LOGIN');
            $request->session()->forget('MEMBER_ID');
            return redirect('/member')->with('failedMsg', 'Member session not found. Please login again.');
        }

        $memberid = $result['data']->memberid;

        // Optional admin-entered dashboard adjustments for this member.
        // A missing row is intentionally left as null and treated as zero in the view.
        $result['addonData'] = FakeDatas::where('memberid', $memberid)->first();

        // Dashboard data calculations
        $result['myReferrals'] = MemberDetail::where('sponsorid', $memberid)->count();
        $result['totalTeam'] = $result['data']->team ?? 0;
        $result['myInvestment'] = $result['data']->package ?? 0;
        $result['referralBusiness'] = $result['data']->self_biz ?? 0;
        $result['teamBusiness'] = $result['data']->team_biz ?? 0;
        $result['importFund'] = \Illuminate\Support\Facades\DB::table('import_funds')->where('memberid', $memberid)->sum('amount');

        $result['totalWithdrawal'] = \Illuminate\Support\Facades\DB::table('withdrawal_requests')->where('memberid', $memberid)->sum('gross_amount');
        $result['todaysWithdrawal'] = \Illuminate\Support\Facades\DB::table('withdrawal_requests')->where('memberid', $memberid)->whereDate('created_at', date('Y-m-d'))->sum('gross_amount');

        $result['totalDailyIncome'] = \Illuminate\Support\Facades\DB::table('direct_incomes')->where('memberid', $memberid)->sum('amount');
        $result['totalReferralIncome'] = \Illuminate\Support\Facades\DB::table('referral_incomes')->where('memberid', $memberid)->sum('amount');
        $result['totalRankIncome'] = \Illuminate\Support\Facades\DB::table('rank_incomes')->where('memberid', $memberid)->sum('amount');
        $result['totalLevelIncome'] = 0;
        $result['totalIncome'] = $result['totalDailyIncome'] + $result['totalReferralIncome'] + $result['totalRankIncome'] + $result['totalLevelIncome'];

        $result['todaysDailyIncome'] = \Illuminate\Support\Facades\DB::table('direct_incomes')->where('memberid', $memberid)->whereDate('created_at', date('Y-m-d'))->sum('amount');
        $result['todaysReferralIncome'] = \Illuminate\Support\Facades\DB::table('referral_incomes')->where('memberid', $memberid)->whereDate('created_at', date('Y-m-d'))->sum('amount');
        $result['todaysRankIncome'] = \Illuminate\Support\Facades\DB::table('rank_incomes')->where('memberid', $memberid)->whereDate('created_at', date('Y-m-d'))->sum('amount');
        $result['todaysLevelIncome'] = 0;
        $result['todaysIncome'] = $result['todaysDailyIncome'] + $result['todaysReferralIncome'] + $result['todaysRankIncome'] + $result['todaysLevelIncome'];

         $result['imgData'] = DashImage::where('status', 'Live')->latest()->first();
         
        return view('member.dashboard.index')->with($result);
    }
}
