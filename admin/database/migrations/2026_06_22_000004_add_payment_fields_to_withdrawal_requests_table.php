<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('withdrawal_requests')) {
            return;
        }

        Schema::table('withdrawal_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('withdrawal_requests', 'utr')) {
                $table->string('utr')->nullable()->after('txnid');
            }

            if (! Schema::hasColumn('withdrawal_requests', 'other_detail')) {
                $table->text('other_detail')->nullable()->after('utr');
            }

            if (! Schema::hasColumn('withdrawal_requests', 'image')) {
                $table->string('image')->nullable()->after('other_detail');
            }

            if (! Schema::hasColumn('withdrawal_requests', 'reason')) {
                $table->text('reason')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('withdrawal_requests')) {
            return;
        }

        $columns = collect(['utr', 'other_detail', 'image', 'reason'])
            ->filter(fn (string $column) => Schema::hasColumn('withdrawal_requests', $column))
            ->all();

        if ($columns) {
            Schema::table('withdrawal_requests', fn (Blueprint $table) => $table->dropColumn($columns));
        }
    }
};
