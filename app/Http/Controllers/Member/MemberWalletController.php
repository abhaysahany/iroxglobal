<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\FundRequest;
use App\Models\Deduction;
use App\Models\ImportFund;
use App\Models\MemberDetail;
use App\Models\NowpaymentsPayment;
use App\Models\PaymentData;
use App\Models\PaymentDetail;
use App\Models\WalletTransfer;
use App\Models\SuperAdmin;
use App\Models\SetRate;
use App\Models\Setting;
use App\Models\StakingDetail;
use Illuminate\Support\Str;
use DateTime;
use App\Models\TmcCoin;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\View\View;

class MemberWalletController extends Controller
{
   public function mainWallets()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $currentWeek = (int)date('W');

        if ($currentWeek % 2 == 0) {

            $date = new DateTime();
            $date->setISODate(date('Y'), $currentWeek ? $currentWeek : $currentWeek + 1);
            $newDate = $date->format('Y-m-d');

            $result['check'] = date('Y-m-d', strtotime($newDate . '+5 days'));
        }
        $result['setting'] = Setting::first();
        return view('member.wallet.income-wallet')->with($result);
    }

    public function initWithdrawal(Request $request)
    {
        if ($request->isMethod('get')) {
            $memberid = session('MEMBER_ID');
            $result['data'] = MemberDetail::where('memberid', $memberid)->first();
            
            $requests = WithdrawalRequest::where('memberid', $memberid)->orderBy('created_at', 'desc')->get();
            $withdrawalHistory = [];
            foreach ($requests as $r) {
                $withdrawalHistory[] = [
                    'request_date' => $r->request_date,
                    'request_id' => $r->request_id,
                    'payment_date' => $r->payment_date ?? '-',
                    'member_id' => $r->memberid,
                    'member_name' => $r->name,
                    'contact_no' => $result['data']->mobile ?? '',
                    'email' => $result['data']->email ?? '',
                    'location' => $result['data']->location ?? '',
                    'sponsor_id' => $result['data']->sponsorid ?? '',
                    'sponsor_name' => $result['data']->sponsor_name ?? '',
                    'withdrawn_amount' => $r->gross_amount,
                    'admin_charges' => $r->service_charge,
                    'final_payout' => $r->net_amount,
                    'wallet_address' => $r->wallet_address,
                    'reason' => $r->reason ?? $r->remarks ?? '-',
                    'amount_disbursed_mode' => $r->amount_disbursed_mode ?? $r->type ?? '-',
                    'status' => $r->status ?? 'Pending',
                ];
            }
            $result['withdrawalHistory'] = $withdrawalHistory;
            return view('member.wallet.withdrawal-history')->with($result);
        }

        $request->validate([
            'amount' => 'required',
            'txn_password' => 'required',
        ]);

        
        $txnPass = $request->post('txn_password');
        $memberid = $request->post('memberid');
        $amount = $request->post('amount');

        // if (date('d') == '01') {
        //     $date = date('Y-m-01');
        //     $deductAmount = withdrawalTodayIncome($memberid, $date);
        // } elseif (date('d') == '16') {
        //     $date = date('Y-m-16');
        //     $deductAmount = withdrawalTodayIncome($memberid, $date);
        // } else {
        //     $deductAmount = 0;
        // }

        $setting = Setting::first();
        $withdrawalStatus = $setting?->withdrawal_status ?? 'enabled';
        if ($withdrawalStatus === 'disabled') {
            $notice = $setting?->withdrawal_notice ?: 'Withdrawal service is temporarily paused. Please try again later.';
            session()->flash('failedMsg', $notice);
            return redirect()->back();
        }

        $minWithdrawal = (float) ($setting?->min_withdrawal ?? 5.00);
        $maxWithdrawal = $setting?->max_withdrawal ? (float) $setting->max_withdrawal : null;
        $dailyLimit = $setting?->daily_max_withdrawal ? (float) $setting->daily_max_withdrawal : null;

        $rate = 5.00;
        if ($setting && $setting->withdrawal_fee_percent !== null) {
            $rate = (float) $setting->withdrawal_fee_percent;
        } else {
            try {
                $rates = Deduction::find(1);
                if ($rates && isset($rates->rate)) {
                    $rate = (float) $rates->rate;
                }
            } catch (\Throwable $e) {}
        }

        $service = $amount / 100 * $rate;
        $netAmount = $amount - $service;
        // $totalAmount = $amount;
        $date = date('Y-m-d H:i:s');
        $requestid = "RQ" . time();

        $mem = MemberDetail::where('memberid', $memberid)->first();
        // $total_wallet = $mem->wallet;
        $type = $mem->registration_mode;
        // $available = $total_wallet - $deductAmount;

        if ($mem['kyc_status'] != 'Verified') {
            session()->flash('failedMsg', 'Your kyc details are not verified. After kyc verification you will eligible for withdrawl');
            return redirect()->back();
        }

        $stack = StakingDetail::where([['memberid', $memberid], ['status', 'Active']])->count();
        if ($stack == 0) {
            session()->flash('failedMsg', 'You must have at least one active investment to initiate a withdrawal.');
            return redirect()->back();
        }

        $tPassword = $mem['txn_password'];
        if ($mem['wallet_address'] == '' || $mem['wallet_address'] == null) {
            session()->flash('failedMsg', 'Your wallet address is not completed. Plase fill your wallet address before withdrawal');
            return redirect()->back();
        }

        //$count = WithdrawalRequest::where('memberid', $memberid)->whereDate('created_at', date('Y-m-d'))->count();
        // if ($count > 0) {
        //     session()->flash('failedMsg', 'Only one withdrawal request is allowed per day.');
        //     return redirect()->back();
        // }

        // $check = WithdrawalRequest::where([['memberid', $memberid], ['status', 'Pending']])->count();
        // if ($check > 0) {
        //     session()->flash('failedMsg', 'Your Last Withdrawal Still Pending. Please Wait');
        //     return redirect()->back();
        // }

        if ($mem['status'] != 'Active') {
            session()->flash('failedMsg', 'Your account is not active. Withdrawal request can be initiated by active members only');
            return redirect()->back();
        }

        if ($amount <= 0 || $amount < $minWithdrawal) {
            session()->flash('failedMsg', 'Invalid Amount Entered. Amount must be at least ' . $minWithdrawal . '$');
            return redirect()->back();
        }

        if ($maxWithdrawal && $amount > $maxWithdrawal) {
            session()->flash('failedMsg', 'Withdrawal amount exceeds maximum limit of ' . $maxWithdrawal . '$ per request.');
            return redirect()->back();
        }

        if ($dailyLimit && $dailyLimit > 0) {
            $todayWithdrawn = WithdrawalRequest::where('memberid', $memberid)
                ->whereDate('created_at', date('Y-m-d'))
                ->whereNotIn('status', ['Cancelled', 'Rejected'])
                ->sum('gross_amount');
            if (($todayWithdrawn + $amount) > $dailyLimit) {
                $remaining = max(0, $dailyLimit - $todayWithdrawn);
                session()->flash('failedMsg', 'Daily withdrawal limit of ' . $dailyLimit . '$ exceeded. Remaining limit today: ' . $remaining . '$.');
                return redirect()->back();
            }
        }
        
        if ($amount > $mem->wallet) {
            session()->flash('failedMsg', 'You do not have enough balance to withdrawal this amount');
            return redirect()->back();
        }

        if (!Hash::check($txnPass, $tPassword)) {
            session()->flash('failedMsg', 'Enter Transaction password do not match. Please check and try again');
            return redirect()->back();
        }

        $mem->wallet -= $amount;
        $wallet = $mem->wallet;
        $mem->save();

        $var = new WithdrawalRequest();
        $var->request_date = $date;
        $var->request_id = $requestid;
        $var->memberid = $memberid;
        $var->name = $mem->name;
        $var->type = 'Online';
        // $var->id_type = $type;
        $var->wallet_address = $mem->wallet_address;
        $var->gross_amount = $amount;
        $var->service_charge = $service;
        $var->net_amount = $netAmount;
        $var->save();

        walletTransfer($memberid, $amount, 'credit', $wallet, 'Withdrawal', ' $ ' . $amount . ' have been withdrawl by Member from wallet. Payment Id-' . $var->request_id);

        // $mailData = [
        //     'request_id' => $requestid,
        //     'email' => $mem->email,
        //     'memberid' => $memberid,
        //     'gross' => $var->gross_amount,
        //     'service' => $var->service_charge,
        //     'social' => $var->social_service,
        //     'support' => $var->support,
        //     'net' => $var->net_amount,
        //     'date' => date('Y-m-d H:i:s'),
        //     'name' => $mem->name,
        //     'wallet' => $mem->wallet_address,
        //     'type' => 'Wallet'
        // ];

        // $user['to'] = 'solspeed@solspeeds.com';
        // Mail::send('member.mails.withdrawal-request', $mailData, function ($message) use ($user) {

        //     $message->to($user['to']);
        //     $message->subject('Sol Speed Member Withdrawal Request');
        // });

        session()->flash('successMsg', 'Withdrawal request has been created successfully. Amount will be transfered into wallet after transaction validation.');
        return redirect()->back();
    }

    public function payoutDate(Request $request): View|RedirectResponse
    {
        $memberid = session('MEMBER_ID');
        $member = MemberDetail::where('memberid', $memberid)->first();

        if (!$member) {
            return redirect()
                ->route('member.login')
                ->with('error', 'Please login to access this section.');
        }

        $payoutDates = $request->session()->get('member_payout_date_rows', []);

        $result = [
            'member' => $member,
            'payoutDates' => $payoutDates,
        ];

        return view('member.wallet.payout-data')->with($result);
    }

    public function allTransactions(Request $request): View|RedirectResponse
    {
        $memberid = session('MEMBER_ID');
        $member = MemberDetail::where('memberid', $memberid)->first();

        if (!$member) {
            return redirect()
                ->route('member.login')
                ->with('error', 'Please login to access this section.');
        }

        $allTransactions = $request->session()->get('member_wallet_transaction_rows');
        if (empty($allTransactions)) {
            $trans = WalletTransfer::where('memberid', $memberid)->orderBy('created_at', 'desc')->get();
            $allTransactions = [];
            foreach ($trans as $t) {
                $allTransactions[] = [
                    'date' => $t->created_at->format('Y-m-d H:i:s'),
                    'type' => $t->walletType,
                    'amount_add' => $t->debit,
                    'amount_deduct' => $t->credit,
                    'balance' => $t->balance,
                    'particular' => $t->particular,
                ];
            }
        }

        $result = [
            'member' => $member,
            'allTransactions' => $allTransactions,
        ];

        return view('member.wallet.all-transactions')->with($result);
    }

     public function walletToP2P(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['passdata'] = WalletTransfer::where([['memberid', $result['data']['memberid']], ['walletType', 'P2P Fund Transfer']])->orderBy('created_at', 'desc')->get();
        return view('member.wallet.wallet-to-p2p')->with($result);
    }

    public function walletP2Phistory()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['passdata'] = WalletTransfer::where([['memberid', $result['data']['memberid']], ['walletType', 'Wallet to p2p Transfer']])->orderBy('created_at', 'desc')->get();
        return view('member.wallet.wallet-to-p2p-history')->with($result);
    }

    public function walletP2pTransfer(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'txn_password' => 'required'
        ]);

        $memberid = strtoupper($request->post('sender'));
        // $senderid = $request->post('sender');
        $var = MemberDetail::where('memberid', $memberid)->first();
        $balance = $var->wallet;
        $txn_password = $request->post('txn_password');
        $amount = $request->post('amount');
        $txnpassword = $var->txn_password;

        $rates = Deduction::find(1);
        $rate = $rates->walletToP2P;

        $service = $amount / 100 * $rate;
        $netAmount = $amount - $service;

        if ($amount > $balance) {
            session()->flash('failedMsg', 'You do not have enough balance to transfer this amount ');
            return redirect()->back();
        } elseif ($amount < 0) {
            session()->flash('failedMsg', 'Negative value is not allowed');
            return redirect()->back();
        }
        if (!Hash::check($txn_password, $txnpassword)) {
            session()->flash('failedMsg', 'Entered Transaction Password donot match');
            return redirect()->back();
        }

        $p2p_wallet = $var->p2p_wallet;
        $var->p2p_wallet += $netAmount;
        $wallet = $var->wallet;
        $var->wallet -= $amount;
        $var->save();

        p2pwalletTransfer($memberid, $netAmount, 'debit', $p2p_wallet, 'Wallet to p2p Transfer', '$ ' . $netAmount . ' has been transferred to your P2P wallet');
        walletTransfer($memberid, $amount, 'credit', $wallet, 'Wallet to p2p Transfer', '$ ' . $amount . ' has been deducted from your wallet');

        session()->flash('successMsg', 'Amount has been transferred successfully');
        return redirect()->back();
    }

    public function fundTrans(Request $request)
    {
        if ($request->isMethod('get')) {
            $memberid = session('MEMBER_ID');
            $result['data'] = MemberDetail::where('memberid', $memberid)->first();
            return view('member.p2p.wallet')->with($result);
        }

        $request->validate([
            'txn_password' => 'required',
            'amount' => 'required|numeric',
            'memberid' => 'required'
        ]);

        $memberid = strtoupper($request->post('memberid'));
        $senderid = session('MEMBER_ID');
        $txn_password = $request->post('txn_password');
        $amount = $request->post('amount');
        if ($amount < 0) {
            session()->flash('failedMsg', 'Invalid Amount Input');
            return redirect()->back();
        }
        if ($amount < 1) {
            session()->flash('failedMsg', 'Invalid Amount Entered. Amount must be grater than 1$');
            return redirect()->back();
        }
        $check = MemberDetail::where('memberid', $memberid)->first();
        if (!$check) {
            session()->flash('failedMsg', 'Entered Memberid is not registered with us');
            return redirect()->back();
        }
        // $check = MemberDetail::where([['memberid', $memberid], ['status', 'Temp']])->first();
        // if (!$check) {
        //     session()->flash('failedMsg', 'Your id is not Active');
        //     return redirect()->back();
        // }
        if ($memberid == $senderid) {
            session()->flash('failedMsg', 'You can not transfer money to your own account');
            return redirect()->back();
        }
        $var = MemberDetail::where('memberid', $senderid)->first();
        if ($amount > $var->p2p_wallet) {
            session()->flash('failedMsg', 'You do not have enough balance to transfer');
            return redirect()->back();
        }
        if (!Hash::check($txn_password, $var->txn_password)) {
            session()->flash('failedMsg', 'Enter Transaction password do not match. Please check and try again');
            return redirect()->back();
        }
        $qry = MemberDetail::where('memberid', $memberid)->first();
        $p2p_wallet = $qry->p2p_wallet;
        $qry->p2p_wallet += $amount;
        $qry->save();
        p2pwalletTransfer($memberid, $amount, 'debit', $p2p_wallet, 'P2P Fund Transfer', '$ ' . $amount . ' has been transferred to your Fund wallet by ' . $senderid . '');

        $senderwallet = $var->p2p_wallet;
        $var->p2p_wallet -= $amount;
        $var->save();

        p2pwalletTransfer($senderid, $amount, 'credit', $senderwallet, 'P2P Fund Transfer', '$ ' . $amount . ' has been transferred by you to ' . $memberid . ' Fund wallet');

        session()->flash('successMsg', 'Amount has been transferred successfully');
        return redirect()->back();
    }

    public function WalletHistory()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['passdata'] = WalletTransfer::where([['memberid', $result['data']['memberid']], ['walletType', 'P2P Fund Transfer']])->orderBy('created_at', 'desc')->get();
        return view('member.p2p.wallet-history')->with($result);
    }
}
