<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'min_withdrawal')) {
                $table->decimal('min_withdrawal', 18, 2)->default(10.00)->after('deposit_gateway_state');
            }

            if (! Schema::hasColumn('settings', 'max_withdrawal')) {
                $table->decimal('max_withdrawal', 18, 2)->default(5000.00)->after('min_withdrawal');
            }

            if (! Schema::hasColumn('settings', 'withdrawal_fee_percent')) {
                $table->decimal('withdrawal_fee_percent', 8, 2)->default(5.00)->after('max_withdrawal');
            }

            if (! Schema::hasColumn('settings', 'daily_max_withdrawal')) {
                $table->decimal('daily_max_withdrawal', 18, 2)->default(10000.00)->after('withdrawal_fee_percent');
            }

            if (! Schema::hasColumn('settings', 'withdrawal_status')) {
                $table->string('withdrawal_status', 50)->default('enabled')->after('daily_max_withdrawal');
            }

            if (! Schema::hasColumn('settings', 'withdrawal_notice')) {
                $table->text('withdrawal_notice')->nullable()->after('withdrawal_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $columns = [
                'withdrawal_notice',
                'withdrawal_status',
                'daily_max_withdrawal',
                'withdrawal_fee_percent',
                'max_withdrawal',
                'min_withdrawal',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
