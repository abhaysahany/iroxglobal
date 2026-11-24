<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberDetail;
use App\Models\SetRate;
use App\Models\Setting;
use App\Models\Withdrawal;
use App\Models\WithdrawalRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Throwable;

class AdminWithdrawalController extends Controller
{
    public function settings(): View
    {
        $title = 'Withdrawal Settings';
        $setting = Setting::first();
        if (!$setting) {
            $setting = Setting::create([
                'min_withdrawal' => 10.00,
                'max_withdrawal' => 5000.00,
                'withdrawal_fee_percent' => 5.00,
                'daily_max_withdrawal' => 10000.00,
                'withdrawal_status' => 'enabled',
                'withdrawal_notice' => 'Withdrawals are processed within 24 to 48 hours.',
            ]);
        }

        return view('admin.withdrawals.settings', compact('title', 'setting'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'min_withdrawal' => ['required', 'numeric', 'min:0'],
            'max_withdrawal' => ['required', 'numeric', 'gte:min_withdrawal'],
            'withdrawal_fee_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'daily_max_withdrawal' => ['nullable', 'numeric', 'min:0'],
            'withdrawal_status' => ['required', 'in:enabled,disabled'],
            'withdrawal_notice' => ['nullable', 'string', 'max:500'],
        ]);

        $setting = Setting::firstOrNew();
        $setting->fill($validated);
        $setting->save();

        try {
            $setRate = SetRate::find(1);
            if ($setRate) {
                $setRate->rate = $validated['withdrawal_fee_percent'];
                $setRate->save();
            }
        } catch (\Throwable $e) {
            // graceful fallback
        }

        return back()->with('success', 'Withdrawal settings updated successfully.');
    }

    public function index(): View
    {
        $withdrawals = Withdrawal::with('member')->latest()->paginate(10);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function requests(): View
    {
        $title = 'Withdrawal Requests';
        $type = 'requests';
        $rows = $this->withdrawalRequestRows(['Pending']);

        return view('admin.withdrawals.table', compact('title', 'rows', 'type'));
    }

    public function approved(): View
    {
        $title = 'Approved Withdrawals';
        $type = 'approved';
        $rows = $this->withdrawalRequestRows(['Approved'], false);

        return view('admin.withdrawals.table', compact('title', 'rows', 'type'));
    }

    public function cancelled(): View
    {
        $title = 'Cancelled Withdrawals';
        $type = 'cancelled';
        $rows = $this->withdrawalRequestRows(['Cancelled', 'Canceled', 'Rejected'], false);

        return view('admin.withdrawals.table', compact('title', 'rows', 'type'));
    }

    public function reports(): View
    {
        $title = 'Withdrawal Reports';
        $type = 'reports';
        $rows = $this->reportRows();

        return view('admin.withdrawals.table', compact('title', 'rows', 'type'));
    }

    public function privateKeys(): View
    {
        $title = 'Private Key Management';
        $type = 'private-keys';
        $rows = $this->privateKeyRows();

        return view('admin.withdrawals.table', compact('title', 'rows', 'type'));
    }

    public function approve(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        $request->validate(['admin_remark' => ['nullable', 'string']]);

        if ($withdrawal->status === 'Approved') {
            return back()->with('error', 'Already approved.');
        }

        if ($withdrawal->status !== 'Pending') {
            return back()->with('error', 'Invalid request.');
        }

        try {
            DB::transaction(function () use ($withdrawal, $request) {
                $member = $withdrawal->member()->lockForUpdate()->first();

                if ($member->wallet_balance < $withdrawal->amount) {
                    throw new \RuntimeException('Insufficient wallet balance.');
                }

                $member->decrement('wallet_balance', $withdrawal->amount);
                $withdrawal->update(['status' => 'Approved', 'admin_remark' => $request->admin_remark]);
            });
        } catch (\RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Approved successfully.');
    }

    public function reject(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        $request->validate(['admin_remark' => ['nullable', 'string']]);

        if ($withdrawal->status !== 'Pending') {
            return back()->with('error', 'Invalid request.');
        }

        $withdrawal->update(['status' => 'Rejected', 'admin_remark' => $request->admin_remark]);

        return back()->with('success', 'Rejected successfully.');
    }

     public function quickAccept(Request $request): RedirectResponse
    {
        $request->validate([
            'wid' => ['required', 'integer'],
        ]);

        try {
            $result = DB::transaction(function () use ($request) {
                $withdrawal = WithdrawalRequest::query()->lockForUpdate()->find($request->integer('wid'));

                if (! $withdrawal) {
                    return ['status' => 'not_found'];
                }

                if ($withdrawal->status === 'Approved') {
                    return ['status' => 'already_approved'];
                }

                if ($withdrawal->status !== 'Pending') {
                    return ['status' => 'invalid_status'];
                }

                $withdrawal->update([
                    'status' => 'Approved',
                    'payment_date' => now(),
                ]);

                return [
                    'status' => 'success',
                    'withdrawal' => $withdrawal->fresh(),
                ];
            });
        } catch (Throwable $exception) {
            report($exception);

            return $this->paymentFailure('Unable to accept this payment request.');
        }

        if (($result['status'] ?? null) !== 'success') {
            return match ($result['status'] ?? null) {
                'already_approved' => $this->paymentFailure('This withdrawal request is already approved'),
                'invalid_status' => $this->paymentFailure('Only pending withdrawal requests can be accepted'),
                default => $this->paymentFailure('Withdrawal request not found'),
            };
        }

        $mailSent = $this->sendWithdrawalApprovedMail($result['withdrawal']);

        $successMessage = $mailSent
            ? 'Withdrawal request has been accepted successfully! Mail has been sent to member.'
            : 'Withdrawal request has been accepted successfully! Mail could not be sent.';

        return back()
                ->with('successMsg', 'Withdrawal request has been accepted successfully!')
                ->with('success', $successMessage);
    }

    private function sendWithdrawalApprovedMail(WithdrawalRequest $withdrawal): bool
    {
        $member = MemberDetail::query()
            ->where('memberid', $withdrawal->memberid)
            ->first();

        $toEmail = $member?->email;

        if (! $toEmail) {
            return false;
        }

        $mailData = [
            'name' => $member->name ?? $withdrawal->name ?? 'Member',
            'memberid' => $withdrawal->memberid,
            'request_id' => $withdrawal->request_id,
            'gross_amount' => $withdrawal->gross_amount,
            'service_charge' => $withdrawal->service_charge,
            'net_amount' => $withdrawal->net_amount,
            'payment_method' => $withdrawal->type,
            'wallet_address' => $withdrawal->wallet_address,
            'payment_date' => $withdrawal->payment_date,
        ];

        try {
            Mail::send('admin.mails.withdrawal-approved', $mailData, function ($message) use ($toEmail) {
                $message->to($toEmail);
                $message->subject('Withdrawal Request Approved');
            });
        } catch (Throwable $exception) {
            report($exception);

            return false;
        }

        return true;
    }

    public function wAccept(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'wid' => ['required', 'integer'],
            'utr' => ['required', 'string', 'max:255'],
            'otherdetail' => ['nullable', 'string'],
            'image' => ['required', 'file'],
        ]);

        if ($validator->fails()) {
            return $this->paymentFailure('Please enter UTR and payment proof image.', $validator->errors()->toArray());
        }

        $image = $request->file('image');
        $extension = strtolower($image->getClientOriginalExtension());

        if (! in_array($extension, ['png', 'jpg', 'jpeg'], true)) {
            return $this->paymentFailure('Allowed image type is jpg, jpeg, png. Please change image type');
        }

        $filename = 'txn'.time().'.'.$extension;
        $uploadedPath = public_path('uploads/'.$filename);

        try {
            $result = DB::transaction(function () use ($request, $image, $filename) {
                $withdrawal = WithdrawalRequest::query()->lockForUpdate()->find($request->integer('wid'));

                if (! $withdrawal) {
                    return 'not_found';
                }

                if ($withdrawal->status === 'Approved') {
                    return 'already_approved';
                }

                File::ensureDirectoryExists(public_path('uploads'));
                $image->move(public_path('uploads'), $filename);

                $withdrawal->update([
                    'status' => 'Approved',
                    'utr' => $request->input('utr'),
                    'other_detail' => $request->input('otherdetail'),
                    'image' => $filename,
                    'payment_date' => now(),
                ]);

                return 'success';
            });
        } catch (Throwable $exception) {
            if (is_file($uploadedPath)) {
                File::delete($uploadedPath);
            }

            report($exception);

            return $this->paymentFailure('Unable to accept this payment request.');
        }

        if ($result === 'not_found') {
            return $this->paymentFailure('Payment request not found');
        }

        if ($result === 'already_approved') {
            return $this->paymentFailure('This payment request is already approved');
        }

        return back()
            ->with('successMsg', 'Payment has been accepted successfully!')
            ->with('success', 'Payment has been accepted successfully!');
    }

    public function wCancel(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'rid' => ['required', 'integer'],
            'reason' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->paymentFailure('Cancellation reason is required.', $validator->errors()->toArray());
        }

        try {
            $result = DB::transaction(function () use ($request) {
                $withdrawal = WithdrawalRequest::query()->lockForUpdate()->find($request->integer('rid'));

                if (! $withdrawal) {
                    return 'not_found';
                }

                if ($withdrawal->status === 'Cancelled') {
                    return 'already_cancelled';
                }

                if ($withdrawal->status === 'Approved') {
                    return 'already_approved';
                }

                $member = MemberDetail::query()
                    ->where('memberid', $withdrawal->memberid)
                    ->lockForUpdate()
                    ->first();

                if (! $member) {
                    return 'member_not_found';
                }

                $amount = (float) $withdrawal->gross_amount;
                $oldWallet = (float) $member->wallet;

                $withdrawal->update([
                    'reason' => $request->input('reason'),
                    'status' => 'Cancelled',
                ]);

                $member->increment('wallet', $amount);

                walletTransfer(
                    $member->memberid,
                    $amount,
                    'debit',
                    $oldWallet,
                    'Withdrawal Request Cancelled',
                    ' $ '.$amount.' have been added to Member wallet due to withdrawal request cancellation'
                );

                return 'success';
            });
        } catch (Throwable $exception) {
            report($exception);

            return $this->paymentFailure('Unable to cancel this payment request.');
        }

        return match ($result) {
            'success' => back()
                ->with('successMsg', 'Payment has been cancelled successfully!')
                ->with('success', 'Payment has been cancelled successfully!'),
            'already_cancelled' => $this->paymentFailure('This payment request is already cancelled'),
            'already_approved' => $this->paymentFailure('Approved payment request cannot be cancelled'),
            'member_not_found' => $this->paymentFailure('Member is not available for this payment request'),
            default => $this->paymentFailure('Payment request not found'),
        };
    }

    private function paymentFailure(string $message, array $errors = []): RedirectResponse
    {
        $response = back()
            ->withInput()
            ->with('failedMsg', $message)
            ->with('error', $message);

        return $errors ? $response->withErrors($errors) : $response;
    }

    private function withdrawalRows(array $statuses): LengthAwarePaginator
    {
        $query = DB::table('withdrawals')
            ->leftJoin('members', 'withdrawals.member_id', '=', 'members.id')
            ->select([
                'members.member_id as member_code',
                'members.name as member_name',
                'members.email as member_email',
                'withdrawals.amount as amount',
                'withdrawals.payment_method as payment_method',
                'withdrawals.account_details as account_details',
                DB::raw('NULL as transaction_id'),
                'withdrawals.admin_remark as admin_remark',
                'withdrawals.status as status',
                'withdrawals.created_at as request_date',
                'withdrawals.updated_at as updated_date',
            ]);

        if (Schema::hasColumn('withdrawals', 'status')) {
            $query->whereIn('withdrawals.status', $statuses);
        }

        return $query->orderByDesc('withdrawals.created_at')->paginate(10);
    }

     private function withdrawalRequestRows(array $statuses, bool $paginate = true): LengthAwarePaginator|Collection
    {
        $query = DB::table('withdrawal_requests')
            ->leftJoin('member_details', 'withdrawal_requests.memberid', '=', 'member_details.memberid')
            ->select([
                'withdrawal_requests.id as id',
                'withdrawal_requests.memberid as member_code',
                DB::raw('COALESCE(member_details.name, withdrawal_requests.name) as member_name'),
                'member_details.email as member_email',
                'withdrawal_requests.gross_amount as gross_amount',
                'withdrawal_requests.service_charge as service_charge',
                'withdrawal_requests.net_amount as net_amount',
                'withdrawal_requests.type as payment_method',
                'withdrawal_requests.wallet_address as account_details',
                'withdrawal_requests.txnid as transaction_id',
                'withdrawal_requests.reason as admin_remark',
                'withdrawal_requests.status as status',
                DB::raw('COALESCE(withdrawal_requests.request_date, withdrawal_requests.created_at) as request_date'),
                'withdrawal_requests.updated_at as updated_date',
            ])
            ->whereIn('withdrawal_requests.status', $statuses)
            ->orderBy('withdrawal_requests.created_at', 'DESC');

        return $paginate ? $query->paginate(10) : $query->get();
    }
    private function reportRows(): LengthAwarePaginator
    {
        return DB::table('withdrawals')
            ->leftJoin('members', 'withdrawals.member_id', '=', 'members.id')
            ->select([
                'members.member_id as member_code',
                'members.name as member_name',
                DB::raw('SUM(withdrawals.amount) as total_withdrawal'),
                DB::raw("SUM(CASE WHEN withdrawals.status = 'Approved' THEN withdrawals.amount ELSE 0 END) as approved_withdrawal"),
                DB::raw("SUM(CASE WHEN withdrawals.status = 'Pending' THEN withdrawals.amount ELSE 0 END) as pending_withdrawal"),
                DB::raw("SUM(CASE WHEN withdrawals.status IN ('Cancelled', 'Canceled', 'Rejected') THEN withdrawals.amount ELSE 0 END) as cancelled_withdrawal"),
                DB::raw('MAX(withdrawals.created_at) as last_withdrawal_date'),
            ])
            ->groupBy('members.member_id', 'members.name', 'withdrawals.member_id')
            ->orderByDesc('last_withdrawal_date')
            ->paginate(10);
    }

    private function privateKeyRows(): LengthAwarePaginator
    {
        $table = $this->firstExistingTable(['private_keys', 'member_private_keys', 'wallet_private_keys', 'wallets', 'members']);
        $memberColumn = $table === 'members' ? 'id' : 'member_id';
        $query = DB::table($table);
        $selects = [];

        if ($table === 'members') {
            $selects[] = 'members.member_id as member_code';
            $selects[] = 'members.name as member_name';
        } elseif (Schema::hasColumn($table, $memberColumn)) {
            $query->leftJoin('members', $table.'.'.$memberColumn, '=', 'members.id');
            $selects[] = 'members.member_id as member_code';
            $selects[] = 'members.name as member_name';
        } else {
            $selects[] = DB::raw('NULL as member_code');
            $selects[] = DB::raw('NULL as member_name');
        }

        $selects[] = $this->selectExistingColumn($table, ['wallet_address', 'address'], 'wallet_address');
        $selects[] = $this->selectExistingColumn($table, ['network', 'chain'], 'network');
        $selects[] = $this->selectExistingColumn($table, ['key_status', 'status'], 'key_status');
        $selects[] = $this->selectExistingColumn($table, ['private_key'], 'private_key_value');
        $selects[] = $this->selectExistingColumn($table, ['updated_at', 'created_at'], 'updated_date');

        $query->select($selects);

        if (Schema::hasColumn($table, 'updated_at')) {
            $query->orderByDesc($table.'.updated_at');
        } elseif (Schema::hasColumn($table, 'created_at')) {
            $query->orderByDesc($table.'.created_at');
        } elseif (Schema::hasColumn($table, 'id')) {
            $query->orderByDesc($table.'.id');
        }

        return $query->paginate(10);
    }

    private function selectExistingColumn(string $table, array $columns, string $alias): mixed
    {
        foreach ($columns as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $table.'.'.$column.' as '.$alias;
            }
        }

        return DB::raw('NULL as '.$alias);
    }

    private function firstExistingTable(array $tables): string
    {
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return 'members';
    }
}
