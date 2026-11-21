<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\P2PTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminP2PController extends Controller
{
    public function index(Request $request): View
    {
        $members = Member::latest()->get();
        $transactions = P2PTransaction::with(['sender', 'receiver'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('sender', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('member_id', 'like', "%{$search}%"))
                    ->orWhereHas('receiver', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('member_id', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.p2p.index', compact('members', 'transactions'));
    }

    public function memberWallets(): View
    {
        $title = 'Member Wallets';
        $type = 'member-wallets';
        $rows = $this->memberWalletRows();

        return view('admin.p2p.table', compact('title', 'rows', 'type'));
    }

    public function walletLedger(): View
    {
        $title = 'Wallet Ledger';
        $type = 'wallet-ledger';
        $rows = $this->walletLedgerRows();

        return view('admin.p2p.table', compact('title', 'rows', 'type'));
    }

    public function walletAdjustments(): View
    {
        $title = 'Wallet Adjustments';
        $type = 'wallet-adjustments';
        $rows = $this->walletAdjustmentRows();

        return view('admin.p2p.table', compact('title', 'rows', 'type'));
    }

    public function walletReports(): View
    {
        $title = 'Wallet Reports';
        $type = 'wallet-reports';
        $rows = $this->walletReportRows();

        return view('admin.p2p.table', compact('title', 'rows', 'type'));
    }

    public function walletAuditLogs(): View
    {
        $title = 'Wallet Audit Logs';
        $type = 'wallet-audit-logs';
        $rows = $this->walletAuditRows();

        return view('admin.p2p.table', compact('title', 'rows', 'type'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sender_member_id' => ['required', 'exists:members,id', 'different:receiver_member_id'],
            'receiver_member_id' => ['required', 'exists:members,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'transaction_note' => ['nullable', 'string'],
        ]);

        try {
            DB::transaction(function () use ($data) {
                $sender = Member::where('id', $data['sender_member_id'])->lockForUpdate()->firstOrFail();
                $receiver = Member::where('id', $data['receiver_member_id'])->lockForUpdate()->firstOrFail();

                if ($sender->wallet_balance < $data['amount']) {
                    throw new \RuntimeException('Insufficient wallet balance.');
                }

                $sender->decrement('wallet_balance', $data['amount']);
                $receiver->increment('wallet_balance', $data['amount']);

                P2PTransaction::create(array_merge($data, ['status' => 'Success']));
            });
        } catch (\RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Created successfully.');
    }

    private function memberWalletRows(): LengthAwarePaginator
    {
        return DB::table('members')
            ->select([
                'members.member_id as member_code',
                'members.name as member_name',
                'members.email as member_email',
                'members.wallet_balance as wallet_balance',
                DB::raw('NULL as total_credit'),
                DB::raw('NULL as total_debit'),
                'members.status as wallet_status',
                'members.updated_at as updated_date',
            ])
            ->orderByDesc('members.updated_at')
            ->paginate(10);
    }

    private function walletLedgerRows(): LengthAwarePaginator
    {
        $table = $this->firstExistingTable(['wallet_ledgers', 'wallet_ledger', 'wallet_transactions']);

        if ($table) {
            return $this->baseRows($table, [
                'transaction_type' => ['transaction_type', 'type'],
                'credit' => ['credit', 'credit_amount'],
                'debit' => ['debit', 'debit_amount'],
                'balance' => ['balance', 'closing_balance'],
                'description' => ['description', 'transaction_note', 'remarks'],
                'transaction_date' => ['transaction_date', 'created_at'],
            ]);
        }

        return DB::table('p2p_transactions')
            ->leftJoin('members as sender', 'p2p_transactions.sender_member_id', '=', 'sender.id')
            ->select([
                'sender.member_id as member_code',
                'sender.name as member_name',
                DB::raw("'P2P Debit' as transaction_type"),
                DB::raw('NULL as credit'),
                'p2p_transactions.amount as debit',
                DB::raw('NULL as balance'),
                'p2p_transactions.transaction_note as description',
                'p2p_transactions.created_at as transaction_date',
            ])
            ->orderByDesc('p2p_transactions.created_at')
            ->paginate(10);
    }

    private function walletAdjustmentRows(): LengthAwarePaginator
    {
        $table = $this->firstExistingTable(['wallet_adjustments', 'wallet_adjustment']);

        if (! $table) {
            return $this->emptyMemberRows([
                'adjustment_type',
                'amount',
                'reason',
                'adjusted_by',
                'status',
                'created_at',
            ]);
        }

        return $this->baseRows($table, [
            'adjustment_type' => ['adjustment_type', 'type'],
            'amount' => ['amount'],
            'reason' => ['reason', 'description'],
            'adjusted_by' => ['adjusted_by', 'admin_id', 'user_id'],
            'status' => ['status'],
            'created_at' => ['created_at'],
        ]);
    }

    private function walletReportRows(): LengthAwarePaginator
    {
        return DB::table('members')
            ->select([
                'members.member_id as member_code',
                'members.name as member_name',
                DB::raw('NULL as opening_balance'),
                DB::raw('NULL as total_credit'),
                DB::raw('NULL as total_debit'),
                'members.wallet_balance as closing_balance',
                DB::raw('(select max(created_at) from p2p_transactions where p2p_transactions.sender_member_id = members.id or p2p_transactions.receiver_member_id = members.id) as last_transaction_date'),
            ])
            ->orderByDesc('members.updated_at')
            ->paginate(10);
    }

    private function walletAuditRows(): LengthAwarePaginator
    {
        $table = $this->firstExistingTable(['wallet_audit_logs', 'wallet_audits', 'audit_logs']);

        if ($table) {
            return $this->baseRows($table, [
                'admin_user' => ['admin_user', 'admin_name', 'user_name', 'user_id', 'admin_id'],
                'action' => ['action'],
                'old_balance' => ['old_balance'],
                'new_balance' => ['new_balance'],
                'ip_address' => ['ip_address'],
                'created_at' => ['created_at'],
            ]);
        }

        return DB::table('p2p_transactions')
            ->leftJoin('members as sender', 'p2p_transactions.sender_member_id', '=', 'sender.id')
            ->select([
                DB::raw('NULL as admin_user'),
                'sender.member_id as member_code',
                'sender.name as member_name',
                DB::raw("'P2P Transfer' as action"),
                DB::raw('NULL as old_balance'),
                DB::raw('NULL as new_balance'),
                DB::raw('NULL as ip_address'),
                'p2p_transactions.created_at as created_at',
            ])
            ->orderByDesc('p2p_transactions.created_at')
            ->paginate(10);
    }

    private function baseRows(string $table, array $fieldMap): LengthAwarePaginator
    {
        $query = DB::table($table);
        $selects = [];

        if (Schema::hasTable('members') && Schema::hasColumn($table, 'member_id')) {
            $query->leftJoin('members', $table.'.member_id', '=', 'members.id');
            $selects[] = 'members.member_id as member_code';
            $selects[] = 'members.name as member_name';
            $selects[] = 'members.email as member_email';
        } else {
            $selects[] = DB::raw('NULL as member_code');
            $selects[] = DB::raw('NULL as member_name');
            $selects[] = DB::raw('NULL as member_email');
        }

        foreach ($fieldMap as $alias => $columns) {
            $selects[] = $this->selectExistingColumn($table, $columns, $alias);
        }

        $query->select($selects);

        if (Schema::hasColumn($table, 'created_at')) {
            $query->orderByDesc($table.'.created_at');
        } elseif (Schema::hasColumn($table, 'id')) {
            $query->orderByDesc($table.'.id');
        }

        return $query->paginate(10);
    }

    private function emptyMemberRows(array $aliases): LengthAwarePaginator
    {
        $selects = [
            'members.member_id as member_code',
            'members.name as member_name',
            'members.email as member_email',
        ];

        foreach ($aliases as $alias) {
            $selects[] = DB::raw('NULL as '.$alias);
        }

        return DB::table('members')
            ->select($selects)
            ->whereRaw('1 = 0')
            ->paginate(10);
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

    private function firstExistingTable(array $tables): ?string
    {
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }
}
