<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activation;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminActivationController extends Controller
{
    public function index(): View
    {
        $title = 'Activations';
        $type = 'activations';
        $rows = $this->activationRows();

        return view('admin.activations.table', compact('title', 'rows', 'type'));
    }

    public function upgrades(): View
    {
        $title = 'Upgrades';
        $type = 'upgrades';
        $rows = $this->upgradeRows();

        return view('admin.activations.table', compact('title', 'rows', 'type'));
    }

    public function packageHistory(): View
    {
        $title = 'Package History';
        $type = 'package-history';
        $rows = $this->packageHistoryRows();

        return view('admin.activations.table', compact('title', 'rows', 'type'));
    }

    public function activate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'package_amount' => ['required', 'numeric', 'min:1'],
        ]);

        $member = Member::findOrFail($data['member_id']);
        $member->update(['status' => 'active', 'is_active' => true, 'activated_at' => now()]);

        Activation::create([
            'member_id' => $member->id,
            'package_amount' => $data['package_amount'],
            'activated_by' => $request->session()->get('admin_id'),
            'status' => 'Active',
            'activated_at' => now(),
        ]);

        return back()->with('success', 'Updated successfully.');
    }

    public function deactivate(Member $member): RedirectResponse
    {
        $member->update(['status' => 'inactive', 'is_active' => false]);

        return back()->with('success', 'Updated successfully.');
    }

    private function activationRows(): LengthAwarePaginator
    {
        return $this->baseRows('activations', [
            'package_name' => ['package_name', 'plan_name'],
            'package_amount' => ['package_amount', 'amount'],
            'status' => ['status'],
            'activated_at' => ['activated_at'],
            'created_at' => ['created_at'],
        ]);
    }

    private function upgradeRows(): LengthAwarePaginator
    {
        $table = $this->firstExistingTable(['upgrades', 'package_upgrades', 'upgrade_histories']);

        if (! $table) {
            return $this->activationRows();
        }

        return $this->baseRows($table, [
            'old_package' => ['old_package', 'old_package_name', 'previous_package', 'previous_package_name'],
            'new_package' => ['new_package', 'new_package_name', 'package_name', 'plan_name'],
            'upgrade_amount' => ['upgrade_amount', 'amount', 'package_amount'],
            'upgrade_status' => ['upgrade_status', 'status'],
            'upgraded_at' => ['upgraded_at', 'upgrade_date', 'created_at'],
        ]);
    }

    private function packageHistoryRows(): LengthAwarePaginator
    {
        $table = $this->firstExistingTable(['package_histories', 'package_history', 'member_packages']);

        if (! $table) {
            return $this->activationRows();
        }

        return $this->baseRows($table, [
            'package_name' => ['package_name', 'plan_name'],
            'package_amount' => ['package_amount', 'amount'],
            'status' => ['status'],
            'start_date' => ['start_date', 'activated_at'],
            'end_date' => ['end_date'],
            'created_at' => ['created_at'],
        ]);
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
