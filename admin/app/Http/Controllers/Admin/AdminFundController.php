<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\FundTransaction;
use App\Models\ImportFund;
use App\Models\Member;
use App\Models\MemberDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class AdminFundController extends Controller
{
    public function addFundsPage(): View
    {
        return view('admin.funds.add', [
            'walletTypes' => [
                'wallet' => 'Main Wallet',
                'p2p_wallet' => 'Fund Wallet',
            ],
        ]);
    }
    
     public function ImportFundDetails ()
    {
        $fund = ImportFund::where('added_by', 'User')->orderby('created_at', 'desc')->get();
        return view('admin.funds.import-fund-details', compact('fund'));
    }

    public function addMemFunds(Request $request): RedirectResponse
    {
        $data = $this->validateFundRequest($request);

        if ($data instanceof RedirectResponse) {
            return $data;
        }

        try {
            $result = DB::transaction(function () use ($data) {
                $member = MemberDetail::query()
                    ->where('memberid', $data['memberid'])
                    ->lockForUpdate()
                    ->first();

                if (! $member) {
                    return 'member_not_found';
                }

                $amount = (float) $data['amount'];
                $balanceBefore = (float) $member->{$data['wallet_type']};
                $walletLabel = $this->walletLabel($data['wallet_type']);
                $transferTitle = $this->walletTransferTitle($data['wallet_type']);

                $member->increment($data['wallet_type'], $amount);

                $this->storeWalletTransfer(
                    $data['wallet_type'],
                    $member->memberid,
                    $amount,
                    'debit',
                    $balanceBefore,
                    $transferTitle,
                    '₹ '.$amount.' has been transferred to your '.$this->walletDescription($data['wallet_type']).' by Admin'
                );

                $this->storeImportFund($member->memberid, $amount, 'Add', $walletLabel);

                return 'success';
            });
        } catch (Throwable $exception) {
            report($exception);

            return $this->fundFailure('Invalid Input Entered');
        }

        if ($result === 'member_not_found') {
            return $this->fundFailure('Entered Memberid is not Available. Please change memberid');
        }

        $message = $data['wallet_type'] === 'p2p_wallet'
            ? 'Amount has been added successfully to Member P2P Wallet'
            : 'Amount has been added successfully to Member Wallet';

        return back()->with('success', $message);
    }

    public function deductFundsPage(): View
    {
        return view('admin.funds.deduct', [
            'walletTypes' => [
                'wallet' => 'Main Wallet',
                'p2p_wallet' => 'Fund Wallet',
            ],
        ]);
    }

    public function deductMemFunds(Request $request): RedirectResponse
    {
        $data = $this->validateFundRequest($request);

        if ($data instanceof RedirectResponse) {
            return $data;
        }

        try {
            $result = DB::transaction(function () use ($data) {
                $member = MemberDetail::query()
                    ->where('memberid', $data['memberid'])
                    ->lockForUpdate()
                    ->first();

                if (! $member) {
                    return 'member_not_found';
                }

                $balanceBefore = (float) $member->{$data['wallet_type']};
                $amount = (float) $data['amount'];

                if ($balanceBefore < $amount) {
                    return 'insufficient_balance';
                }

                $walletLabel = $this->walletLabel($data['wallet_type']);
                $transferTitle = $this->walletTransferTitle($data['wallet_type']);
                $member->decrement($data['wallet_type'], $amount);

                $this->storeWalletTransfer(
                    $data['wallet_type'],
                    $member->memberid,
                    $amount,
                    'credit',
                    $balanceBefore,
                    $transferTitle,
                    '₹ '.$amount.' has been deducted from your '.$this->walletDescription($data['wallet_type']).' by Admin'
                );

                $this->storeImportFund($member->memberid, $amount, 'Deduct', $walletLabel);

                return 'success';
            });
        } catch (Throwable $exception) {
            report($exception);

            return $this->fundFailure('Invalid Input Entered');
        }

        if ($result === 'member_not_found') {
            return $this->fundFailure('Entered Memberid is not Available. Please change memberid');
        }

        if ($result === 'insufficient_balance') {
            return $this->fundFailure('This user has less balance to requested amount');
        }

        $message = $data['wallet_type'] === 'p2p_wallet'
            ? 'Amount has been deducted successfully from Member P2P Wallet'
            : 'Amount has been deducted successfully from Member Wallet';

        return back()->with('success', $message);
    }

    public function fundsDetails(Request $request)
    {
        $transactions = ImportFund::where('added_by', 'Admin')->latest()->paginate(15);

        return view('admin.funds.details', compact('transactions'));
    }

    public function newFundRequests(): View
    {
        $requests = ImportFund::with('member')
            ->whereIn('status', ['Requested', 'Pending'])
            ->latest()
            ->paginate(15);

        return view('admin.funds.new-requests', compact('requests'));
    }

    public function fundRequestDetails(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['Pending', 'Approved', 'Rejected'])],
            'member_id' => ['nullable', 'integer', 'exists:members,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'request_id' => ['nullable', 'integer', 'exists:deposits,id'],
        ]);

        $requests = Deposit::with('member')
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['member_id'] ?? null, fn (Builder $query, int $memberId) => $query->where('member_id', $memberId))
            ->when($filters['date_from'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '<=', $date))
            ->when($filters['request_id'] ?? null, fn (Builder $query, int $requestId) => $query->whereKey($requestId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.funds.requests', [
            'title' => 'Fund Requests Details',
            'pendingOnly' => false,
            'requests' => $requests,
            'members' => $this->members(),
        ]);
    }

    public function approveFundRequest(Request $request, Deposit $deposit): RedirectResponse
    {
        $request->validate(['admin_remark' => ['nullable', 'string', 'max:1000']]);

        try {
            DB::transaction(function () use ($request, $deposit) {
                $lockedDeposit = Deposit::query()->lockForUpdate()->findOrFail($deposit->id);

                if ($lockedDeposit->status !== 'Pending') {
                    throw new RuntimeException('This fund request has already been processed.');
                }

                $member = Member::query()->lockForUpdate()->findOrFail($lockedDeposit->member_id);
                $balanceBefore = (float) $member->wallet_balance;
                $member->increment('wallet_balance', $lockedDeposit->amount);
                $member->refresh();

                FundTransaction::create([
                    'member_id' => $member->id,
                    'admin_id' => $request->session()->get('admin_id'),
                    'type' => 'Credit',
                    'amount' => $lockedDeposit->amount,
                    'wallet_type' => 'Main Wallet',
                    'remark' => $request->input('admin_remark') ?: 'Fund request #'.$lockedDeposit->id.' approved',
                    'balance_before' => $balanceBefore,
                    'balance_after' => $member->wallet_balance,
                    'source_type' => 'deposit',
                    'source_id' => $lockedDeposit->id,
                ]);

                $lockedDeposit->update([
                    'status' => 'Approved',
                    'admin_remark' => $request->input('admin_remark'),
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Fund request approved and wallet credited successfully.');
    }

    public function acceptFundRequest(int $id): RedirectResponse
    {
        try {
            $result = DB::transaction(function () use ($id) {
                $fundRequest = ImportFund::query()->lockForUpdate()->find($id);

                if (! $fundRequest) {
                    return 'not_found';
                }

                if ($fundRequest->status === 'Approved') {
                    return 'already_approved';
                }

                if (! in_array($fundRequest->status, ['Requested', 'Pending'], true)) {
                    return 'already_processed';
                }

                $walletType = $this->requestWalletType($fundRequest->wallet_type);

                if (! $walletType || (float) $fundRequest->amount <= 0) {
                    return 'invalid_request';
                }

                $member = MemberDetail::query()
                    ->where('memberid', $fundRequest->memberid)
                    ->lockForUpdate()
                    ->first();

                if (! $member) {
                    return 'member_not_found';
                }

                $amount = (float) $fundRequest->amount;
                $oldBalance = (float) $member->{$walletType};
                $member->increment($walletType, $amount);

                $this->storeWalletTransfer(
                    $walletType,
                    $member->memberid,
                    $amount,
                    'debit',
                    $oldBalance,
                    $this->walletTransferTitle($walletType),
                    '₹ '.$amount.' has been transferred to your '.$this->walletDescription($walletType).' by Admin'
                );

                $fundRequest->update([
                    'status' => 'Approved',
                    'added_by' => 'Admin',
                ]);

                return 'success';
            });
        } catch (Throwable $exception) {
            report($exception);

            return $this->fundFailure('Invalid Input Entered');
        }

        if ($result === 'member_not_found') {
            return $this->fundFailure('Entered Memberid is not Available. Please change memberid');
        }

        if ($result !== 'success') {
            return $this->fundFailure('This fund request has already been processed or is invalid');
        }

        return back()->with('success', 'Fund request approved successfully');
    }

    public function rejectFundRequest(int $id): RedirectResponse
    {
        try {
            $result = DB::transaction(function () use ($id) {
                $fundRequest = ImportFund::query()->lockForUpdate()->find($id);

                if (! $fundRequest) {
                    return 'not_found';
                }

                if ($fundRequest->status === 'Approved') {
                    return 'already_approved';
                }

                if (! in_array($fundRequest->status, ['Requested', 'Pending'], true)) {
                    return 'already_processed';
                }

                $fundRequest->update([
                    'status' => 'Rejected',
                    'added_by' => 'Admin',
                ]);

                return 'success';
            });
        } catch (Throwable $exception) {
            report($exception);

            return $this->fundFailure('Invalid Input Entered');
        }

        if ($result !== 'success') {
            return $this->fundFailure('This fund request has already been processed or is invalid');
        }

        return back()->with('success', 'Fund request rejected successfully');
    }

    private function validateFundRequest(Request $request): array|RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'memberid' => ['required', 'string', 'max:255'],
            'wallet_type' => ['required', Rule::in(['wallet', 'p2p_wallet'])],
            'amount' => ['required', 'numeric', 'gt:0', 'decimal:0,4', 'max:999999.9999'],
        ]);

        if ($validator->fails()) {
            return $this->fundFailure('Invalid Input Entered', $validator->errors()->toArray());
        }

        return $validator->validated();
    }

    private function walletLabel(string $walletType): string
    {
        return $walletType === 'p2p_wallet' ? 'P2P Wallet' : 'Wallet';
    }

    private function walletTransferTitle(string $walletType): string
    {
        return $walletType === 'p2p_wallet'
            ? 'Purchase Wallet Fund Transfer'
            : 'Cash Wallet Fund Transfer';
    }

    private function walletDescription(string $walletType): string
    {
        return $walletType === 'p2p_wallet' ? 'purchase wallet' : 'cash wallet';
    }

    private function requestWalletType(?string $walletType): ?string
    {
        $normalized = strtolower(trim((string) $walletType));

        return match ($normalized) {
            'p2p wallet', 'p2p_wallet', 'fund wallet' => 'p2p_wallet',
            'wallet', 'main wallet', 'cash wallet' => 'wallet',
            default => null,
        };
    }

    private function storeWalletTransfer(
        string $walletType,
        string $memberid,
        float $amount,
        string $type,
        float $balance,
        string $walletTransferTitle,
        string $particular
    ): void {
        if ($walletType === 'p2p_wallet') {
            p2pwalletTransfer($memberid, $amount, $type, $balance, $walletTransferTitle, $particular);

            return;
        }

        walletTransfer($memberid, $amount, $type, $balance, $walletTransferTitle, $particular);
    }

    private function storeImportFund(string $memberid, float $amount, string $type, string $walletLabel): void
    {
        ImportFund::create([
            'memberid' => $memberid,
            'amount' => $amount,
            'orderid' => (string) random_int(10000, 99999),
            'txnid' => 'NA',
            'type' => $type,
            'wallet_type' => $walletLabel,
            'status' => 'Approved',
            'mode' => 'Mannual',
            'added_by' => 'Admin',
        ]);
    }

    private function fundFailure(string $message, array $errors = []): RedirectResponse
    {
        $response = back()
            ->withInput()
            ->with('failed', $message)
            ->with('error', $message);

        return $errors ? $response->withErrors($errors) : $response;
    }

    private function adjustmentPage(string $type): View
    {
        return view('admin.funds.adjust', [
            'type' => $type,
            'members' => $this->members(),
        ]);
    }

    private function adjustBalance(Request $request, string $type): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => ['required', 'integer', 'exists:members,id'],
            'amount' => ['required', 'numeric', 'gt:0', 'decimal:0,2', 'max:999999999999.99'],
            'remark' => ['required', 'string', 'max:1000'],
        ]);

        try {
            DB::transaction(function () use ($request, $data, $type) {
                $member = Member::query()->lockForUpdate()->findOrFail($data['member_id']);
                $balanceBefore = (float) $member->wallet_balance;
                $amount = (float) $data['amount'];

                if ($type === 'Debit' && $balanceBefore < $amount) {
                    throw new RuntimeException('Insufficient wallet balance for this deduction.');
                }

                $type === 'Credit'
                    ? $member->increment('wallet_balance', $amount)
                    : $member->decrement('wallet_balance', $amount);

                $member->refresh();

                FundTransaction::create([
                    'member_id' => $member->id,
                    'admin_id' => $request->session()->get('admin_id'),
                    'type' => $type,
                    'amount' => $amount,
                    'wallet_type' => 'Main Wallet',
                    'remark' => $data['remark'],
                    'balance_before' => $balanceBefore,
                    'balance_after' => $member->wallet_balance,
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        $verb = $type === 'Credit' ? 'added to' : 'deducted from';

        return back()->with('success', 'Funds '.$verb.' the member wallet successfully.');
    }

    private function members()
    {
        return Member::query()->orderBy('member_id')->get(['id', 'member_id', 'name', 'wallet_balance']);
    }
}
