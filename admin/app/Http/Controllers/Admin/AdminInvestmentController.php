<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Member;
use App\Models\StakingDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminInvestmentController extends Controller
{
    public function index(): View
    {
        $stakingDetails = StakingDetail::orderby('created_at', 'desc')->get();
        return view('admin.investments.table', compact('stakingDetails'));
    }

    public function roiDistribution(): View
    {
        $title = 'ROI Distribution';
        $type = 'roi-distribution';
        $rows = $this->roiDistributionRows();

        return view('admin.investments.table', compact('title', 'rows', 'type'));
    }

    public function roiSchedule(): View
    {
        $title = 'ROI Schedule';
        $type = 'roi-schedule';
        $rows = $this->roiScheduleRows();

        return view('admin.investments.table', compact('title', 'rows', 'type'));
    }

    public function reports(): View
    {
        $title = 'Investment Reports';
        $type = 'reports';
        $rows = $this->reportRows();

        return view('admin.investments.table', compact('title', 'rows', 'type'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'plan_name' => ['required', 'string', 'max:255'],
            'roi_percentage' => ['nullable', 'numeric', 'min:0'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:Active,Completed,Cancelled'],
        ]);

        Investment::create($data);

        return back()->with('success', 'Created successfully.');
    }

    public function updateStatus(Request $request, Investment $investment): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:Active,Completed,Cancelled']]);
        $investment->update($data);

        return back()->with('success', 'Updated successfully.');
    }

    private function investmentRows(): LengthAwarePaginator
    {
        return $this->baseRows('investments', [
            'plan_name' => ['plan_name'],
            'investment_amount' => ['investment_amount', 'amount'],
            'roi_percentage' => ['roi_percentage'],
            'start_date' => ['start_date'],
            'end_date' => ['end_date'],
            'status' => ['status'],
            'created_at' => ['created_at'],
        ]);
    }

    private function roiDistributionRows(): LengthAwarePaginator
    {
        $roiTable = $this->firstExistingTable(['roi_distributions', 'roi_distribution', 'roi_histories', 'roi_history']);

        if ($roiTable) {
            return $this->baseRows($roiTable, [
                'investment_amount' => ['investment_amount', 'investment', 'principal_amount'],
                'roi_amount' => ['roi_amount', 'amount'],
                'roi_percentage' => ['roi_percentage'],
                'distribution_status' => ['distribution_status', 'status'],
                'distribution_date' => ['distribution_date', 'distributed_at', 'payout_date', 'created_at'],
                'created_at' => ['created_at'],
            ]);
        }

        if (Schema::hasTable('incomes')) {
            $query = DB::table('incomes')
                ->leftJoin('members', 'incomes.member_id', '=', 'members.id')
                ->select([
                    'members.member_id as member_code',
                    'members.name as member_name',
                    'members.email as member_email',
                    DB::raw('NULL as investment_amount'),
                    'incomes.amount as roi_amount',
                    DB::raw('NULL as roi_percentage'),
                    'incomes.status as distribution_status',
                    'incomes.payout_date as distribution_date',
                    'incomes.created_at as created_at',
                ]);

            if (Schema::hasColumn('incomes', 'income_type')) {
                $query->where('incomes.income_type', 'like', '%ROI%');
            }

            return $query->orderByDesc('incomes.created_at')->paginate(10);
        }

        return $this->baseRows('investments', [
            'investment_amount' => ['amount'],
            'roi_amount' => [],
            'roi_percentage' => ['roi_percentage'],
            'distribution_status' => ['status'],
            'distribution_date' => ['created_at'],
            'created_at' => ['created_at'],
        ]);
    }

    private function roiScheduleRows(): LengthAwarePaginator
    {
        $table = $this->firstExistingTable(['roi_schedules', 'roi_schedule', 'investment_schedules']);

        if (! $table) {
            return $this->baseRows('investments', [
                'plan_name' => ['plan_name'],
                'scheduled_roi_amount' => [],
                'due_date' => ['end_date'],
                'paid_date' => [],
                'status' => ['status'],
                'created_at' => ['created_at'],
            ]);
        }

        return $this->baseRows($table, [
            'plan_name' => ['plan_name'],
            'scheduled_roi_amount' => ['scheduled_roi_amount', 'roi_amount', 'amount'],
            'due_date' => ['due_date', 'schedule_date'],
            'paid_date' => ['paid_date', 'paid_at'],
            'status' => ['status'],
            'created_at' => ['created_at'],
        ]);
    }

    private function reportRows(): LengthAwarePaginator
    {
        $query = DB::table('investments')
            ->leftJoin('members', 'investments.member_id', '=', 'members.id')
            ->select([
                'members.member_id as member_code',
                'members.name as member_name',
                DB::raw('SUM(investments.amount) as total_investment'),
                DB::raw("SUM(CASE WHEN investments.status = 'Active' THEN investments.amount ELSE 0 END) as active_investment"),
                DB::raw("SUM(CASE WHEN investments.status = 'Completed' THEN investments.amount ELSE 0 END) as completed_investment"),
                DB::raw("SUM(CASE WHEN investments.status = 'Cancelled' THEN investments.amount ELSE 0 END) as cancelled_investment"),
                DB::raw('NULL as total_roi'),
                DB::raw('MAX(investments.created_at) as last_investment_date'),
            ])
            ->groupBy('members.member_id', 'members.name', 'investments.member_id')
            ->orderByDesc('last_investment_date');

        return $query->paginate(10);
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
