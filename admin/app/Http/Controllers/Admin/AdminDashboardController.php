<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalNetworkNodes = $this->tableExists('members') ? DB::table('members')->count() : 0;
        $activeNodes = $this->activeNodesCount();
        $blockedNodes = $this->blockedNodesCount();

        $totalWalletBalance = $this->totalWalletBalance();
        $totalCredits = $this->totalCredits();
        $totalDebits = $this->totalDebits();
        $totalAdjustments = $this->tableExists('wallet_adjustments') ? DB::table('wallet_adjustments')->count() : 0;
        $totalTransactions = $this->walletTransactionCount();
        $todaysTransactions = $this->walletTransactionCount(today());
        $failedTransactions = $this->failedWalletTransactionCount();

        [$growthLabels, $growthData] = $this->onboardingGrowth();

        $liveLogs = $this->liveLogs();
        $recentMembers = $this->tableExists('members')
            ? DB::table('members')->latest()->limit(10)->get()
            : collect();

        return view('admin.dashboard', compact(
            'totalNetworkNodes',
            'activeNodes',
            'blockedNodes',
            'totalWalletBalance',
            'totalCredits',
            'totalDebits',
            'totalAdjustments',
            'totalTransactions',
            'todaysTransactions',
            'failedTransactions',
            'growthLabels',
            'growthData',
            'liveLogs',
            'recentMembers'
        ));
    }

    private function activeNodesCount(): int
    {
        if (! $this->tableExists('members')) {
            return 0;
        }

        return DB::table('members')
            ->where(function ($query) {
                if (Schema::hasColumn('members', 'status')) {
                    $query->whereRaw('LOWER(status) = ?', ['active']);
                }

                if (Schema::hasColumn('members', 'is_active')) {
                    $query->orWhere('is_active', 1);
                }
            })
            ->count();
    }

    private function blockedNodesCount(): int
    {
        if (! $this->tableExists('members')) {
            return 0;
        }

        return DB::table('members')
            ->where(function ($query) {
                if (Schema::hasColumn('members', 'status')) {
                    $query->whereRaw('LOWER(status) = ?', ['blocked']);
                }

                if (Schema::hasColumn('members', 'blocked')) {
                    $query->orWhere('blocked', 1);
                }
            })
            ->count();
    }

    private function totalWalletBalance(): float
    {
        if ($this->tableExists('wallets') && Schema::hasColumn('wallets', 'balance')) {
            return (float) DB::table('wallets')->sum('balance');
        }

        if ($this->tableExists('members') && Schema::hasColumn('members', 'wallet_balance')) {
            return (float) DB::table('members')->sum('wallet_balance');
        }

        return 0;
    }

    private function totalCredits(): float
    {
        if ($this->tableExists('wallet_transactions')) {
            if (Schema::hasColumn('wallet_transactions', 'credit')) {
                return (float) DB::table('wallet_transactions')->sum('credit');
            }

            if (Schema::hasColumn('wallet_transactions', 'amount') && Schema::hasColumn('wallet_transactions', 'type')) {
                return (float) DB::table('wallet_transactions')
                    ->whereRaw('LOWER(type) = ?', ['credit'])
                    ->sum('amount');
            }
        }

        if ($this->tableExists('p2p_transactions')) {
            return (float) DB::table('p2p_transactions')->sum('amount');
        }

        return 0;
    }

    private function totalDebits(): float
    {
        if ($this->tableExists('wallet_transactions')) {
            if (Schema::hasColumn('wallet_transactions', 'debit')) {
                return (float) DB::table('wallet_transactions')->sum('debit');
            }

            if (Schema::hasColumn('wallet_transactions', 'amount') && Schema::hasColumn('wallet_transactions', 'type')) {
                return (float) DB::table('wallet_transactions')
                    ->whereRaw('LOWER(type) = ?', ['debit'])
                    ->sum('amount');
            }
        }

        if ($this->tableExists('p2p_transactions')) {
            return (float) DB::table('p2p_transactions')->sum('amount');
        }

        return 0;
    }

    private function walletTransactionCount(?Carbon $date = null): int
    {
        $table = $this->tableExists('wallet_transactions') ? 'wallet_transactions' : 'p2p_transactions';

        if (! $this->tableExists($table)) {
            return 0;
        }

        $query = DB::table($table);

        if ($date && Schema::hasColumn($table, 'created_at')) {
            $query->whereDate('created_at', $date->toDateString());
        }

        return $query->count();
    }

    private function failedWalletTransactionCount(): int
    {
        $table = $this->tableExists('wallet_transactions') ? 'wallet_transactions' : 'p2p_transactions';

        if (! $this->tableExists($table) || ! Schema::hasColumn($table, 'status')) {
            return 0;
        }

        return DB::table($table)->whereRaw('LOWER(status) = ?', ['failed'])->count();
    }

    private function onboardingGrowth(): array
    {
        $labels = [];
        $data = [];

        for ($day = 6; $day >= 0; $day--) {
            $date = today()->subDays($day);
            $labels[] = $date->format('d M');

            $data[] = $this->tableExists('members') && Schema::hasColumn('members', 'created_at')
                ? DB::table('members')->whereDate('created_at', $date->toDateString())->count()
                : 0;
        }

        return [$labels, $data];
    }

    private function liveLogs(): Collection
    {
        return collect()
            ->merge($this->logsFromTable('incomes', 'ROI Payout', 'Income record created', 'amount'))
            ->merge($this->logsFromTable('members', 'Registration', 'New member joined', 'name'))
            ->merge($this->logsFromTable('deposits', 'Deposit Request', 'Deposit record created', 'amount'))
            ->merge($this->logsFromTable('withdrawals', 'Withdrawal Request', 'Withdrawal record created', 'amount'))
            ->merge($this->logsFromTable('p2p_transactions', 'P2P Transfer', 'P2P transaction created', 'amount'))
            ->sortByDesc('created_at')
            ->take(5)
            ->values();
    }

    private function logsFromTable(string $table, string $title, string $message, string $valueColumn): Collection
    {
        if (! $this->tableExists($table) || ! Schema::hasColumn($table, 'created_at')) {
            return collect();
        }

        $columns = ['created_at'];

        if (Schema::hasColumn($table, $valueColumn)) {
            $columns[] = $valueColumn;
        }

        return DB::table($table)
            ->latest()
            ->limit(5)
            ->get($columns)
            ->map(function ($row) use ($title, $message, $valueColumn) {
                $value = $row->{$valueColumn} ?? null;

                return (object) [
                    'title' => $title,
                    'message' => $value ? $message.': '.$value : $message,
                    'created_at' => $row->created_at,
                ];
            });
    }

    private function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }
}
