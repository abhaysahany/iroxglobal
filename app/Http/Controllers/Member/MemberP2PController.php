<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberP2PController extends Controller
{
    public function wallet(Request $request): View|RedirectResponse
    {
        $member = $request->session()->get('member');

        if (!$member) {
            return redirect()
                ->route('member.login')
                ->with('error', 'Please login to access this section.');
        }

        $p2pBalance = $request->session()->get('member_p2p_balance', 0);

        return view('member.p2p.wallet', [
            'member' => $member,
            'p2pBalance' => $p2pBalance,
        ]);
    }

    public function transfer(Request $request): RedirectResponse
    {
        $member = $request->session()->get('member');

        if (!$member) {
            return redirect()
                ->route('member.login')
                ->with('error', 'Please login to access this section.');
        }

        $validated = $request->validate([
            'member_id' => 'required|string|max:50',
            'amount' => 'required|numeric|min:1',
            'security_pin' => 'required|string|min:4|max:20',
        ]);

        $p2pBalance = $request->session()->get('member_p2p_balance', 0);
        $amount = (float)$validated['amount'];

        if ($p2pBalance < $amount) {
            return redirect()
                ->back()
                ->with('error', 'Insufficient P2P wallet balance.')
                ->withInput();
        }

        $newBalance = $p2pBalance - $amount;
        $request->session()->put('member_p2p_balance', $newBalance);

        $history = $request->session()->get('member_p2p_wallet_history', []);
        $history[] = [
            'date' => now()->format('Y-m-d H:i'),
            'member_id' => $validated['member_id'],
            'type' => 'Transfer',
            'debit' => $amount,
            'credit' => 0,
            'balance' => $newBalance,
            'particular' => 'P2P transfer to ' . $validated['member_id'],
        ];
        $request->session()->put('member_p2p_wallet_history', $history);

        return redirect()
            ->back()
            ->with('success', 'P2P transfer submitted successfully.');
    }

    public function history(Request $request): View|RedirectResponse
    {
        $member = $request->session()->get('member');

        if (!$member) {
            return redirect()
                ->route('member.login')
                ->with('error', 'Please login to access this section.');
        }

        $p2pHistory = $request->session()->get('member_p2p_wallet_history', []);

        return view('member.p2p.wallet-history', [
            'member' => $member,
            'p2pHistory' => $p2pHistory,
        ]);
    }
}
