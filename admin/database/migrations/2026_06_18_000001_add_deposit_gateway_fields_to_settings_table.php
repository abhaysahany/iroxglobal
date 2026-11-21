<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'company_bsc_wallet_address')) {
                $table->string('company_bsc_wallet_address')->nullable()->after('address');
            }

            if (! Schema::hasColumn('settings', 'minimum_deposit_bnb')) {
                $table->decimal('minimum_deposit_bnb', 18, 8)->nullable()->after('company_bsc_wallet_address');
            }

            if (! Schema::hasColumn('settings', 'maximum_deposit_bnb')) {
                $table->decimal('maximum_deposit_bnb', 18, 8)->nullable()->after('minimum_deposit_bnb');
            }

            if (! Schema::hasColumn('settings', 'deposit_gateway_state')) {
                $table->string('deposit_gateway_state')->default('enabled')->after('maximum_deposit_bnb');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'deposit_gateway_state')) {
                $table->dropColumn('deposit_gateway_state');
            }

            if (Schema::hasColumn('settings', 'maximum_deposit_bnb')) {
                $table->dropColumn('maximum_deposit_bnb');
            }

            if (Schema::hasColumn('settings', 'minimum_deposit_bnb')) {
                $table->dropColumn('minimum_deposit_bnb');
            }

            if (Schema::hasColumn('settings', 'company_bsc_wallet_address')) {
                $table->dropColumn('company_bsc_wallet_address');
            }
        });
    }
};
