<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\StakingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

require_once app_path('Helpers/payment_functions.php');
use Illuminate\Support\Facades\Log;

class ActivationController extends Controller
{
    public function index()
    {
        $memberid = session('MEMBER_ID');

        if (!$memberid) {
            return redirect()->route('member.login');
        }

        $memberDetail = MemberDetail::where('memberid', $memberid)->first();

        return view('member.activation.index', [
            'isActive' => strtolower((string) ($memberDetail?->activation_status ?? '')) === 'active'
                || strtolower((string) ($memberDetail?->status ?? '')) === 'active',
            'data' => $memberDetail,
        ]);
    }

    public function buy(Request $request)
    {
        $request->validate([
            'package' => 'required',
            'txn_password' => 'required'
        ]);
        $memberid = session('MEMBER_ID');
        $txnid = $request->post('txn_password');
        $package = $request->post('package');
        $type = 'Activation';
        
        $package_amount = 70;
        $rate = 0;
        $day = 365;

        $check = MemberDetail::where('memberid', $memberid)->first();
            
        if ($check) {
            $sponsorid = $check->sponsorid;
            $name = $check->name;
            if ($check->status == 'Active') {
                session()->flash('failedMsg', 'Your account is already activated.');
                return redirect()->back();
            }
            if ($package < 0) {
                session()->flash('failedMsg', 'Negative value is not allowed.');
                return redirect()->back();
            }
            if ($package < $package_amount) {
                session()->flash('failedMsg', 'Error: Package must be equal or greater than ' . $package_amount);
                return redirect()->back();
            }
            if (!Hash::check($txnid, $check->txn_password)) {
                session()->flash('failedMsg', 'Entered security pin does not match.');
                return redirect()->back();
            }
            if ($package > $check->p2p_wallet) {
                session()->flash('failedMsg', 'Not Enough balance in your Fund wallet.');
                return redirect()->back();
            }
            $sponsor = MemberDetail::where('memberid', $sponsorid)->first();
            $sponsorLevelDownline = $sponsor ? (int) ($sponsor->level_downline ?? 0) : 0;

            $p2p_wallet = $check->p2p_wallet;
            $check->p2p_wallet -= $package;
            $check->status = 'Active';
            $check->package = $package;
            $check->self_biz += $package;
            $check->team_biz += $package;

            if ($sponsorLevelDownline >= 2) {
                $check->direct_status = 1;
                $check->direct_type = 'Pool';
            } else {
                $check->direct_status = 0;
                $check->direct_type = null;
            }

            $check->activated_at = date('Y-m-d H:i:s');
            $check->save();

            walletTransfer($memberid, $package, 'credit', $p2p_wallet, 'Account Activation', 'Member ' . $memberid . ' Account Activation ' . $package . ' $ deducted from P2P wallet');

            $new = new StakingDetail();
            $new->memberid = $memberid;
            $new->invest_date = date('Y-m-d H:i:s');
            $new->invest_amount = $package;
            $new->package = $package;
            $new->paid_amount = 0;
            $new->renew_date = date('Y-m-d', strtotime('+'.$day.' days'));
            $new->capping = 3;
            $new->capping_year = 1;
            $new->status = 'Active';
            $new->save();

            updateUpline($sponsorid, $memberid);
            if ($sponsorLevelDownline >= 2) {
                updateDownline($sponsorid);
            }
            directIncome($sponsorid, $memberid, $name, $package, $type);
            team_count_update($sponsorid);

            session()->flash('successMsg', 'Your activation request has been created successfully.! and wait for superadmin approval to activate!');
            return redirect()->back();
        }
    }
    
}
