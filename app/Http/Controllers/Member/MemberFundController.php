<?php

namespace App\Http\Controllers\Member;

require_once app_path('Helpers/payment_functions.php');

use App\Http\Controllers\Controller;
use App\Models\ImportFund;
use App\Models\MemberDetail;
use App\Models\SetRate;
use App\Models\PaymentDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberFundController extends Controller
{
    public function importFund()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['rate'] = SetRate::find(1);
        $result['usdt_data'] = PaymentDetail::where([['address', '!=', NULL], ['status', 'Live']])->first();
        return view('member.fund.add-fund')->with($result);
    }

    public function addFund(Request $request)
    {

        $memberid = $request->post('memberid');
        $txnid = $request->post('txnid');
        $amount = $request->post('amount');
        if(ImportFund::where('txnid',$txnid)->first()){
            return response()->json([
                      'code'=>0
                   ]);
        }
        $data = MemberDetail::where('memberid', $memberid)->first();

        $var = new ImportFund();
        $var->memberid = $memberid;
        $var->orderid = "OD" . time();
        $var->amount = $amount;
        $var->txnid = $txnid;
        $var->type = 'Add';
        $var->mode = 'Online';
        $var->added_by = 'User';
        $var->wallet_type = 'P2P Wallet';
        $var->save();

        $wallet = $data->p2p_wallet;
        $data->p2p_wallet += $amount;
        $data->save();
        p2pwalletTransfer($memberid, $amount, 'debit', $wallet, 'Fund Added', '' . $amount . ' USDT added to fund wallet');
        return response()->json([
            'code' => 1
        ]);
    }


    public function importFundHis(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['addfundData'] = ImportFund::where([['memberid', $memberid], ['added_by', 'User']])->orderBy('created_at', 'desc')->get();
        return view('member.fund.import-fund-history')->with($result);
    }
}
