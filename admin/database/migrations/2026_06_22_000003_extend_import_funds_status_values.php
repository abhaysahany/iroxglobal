<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('import_funds') && DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE import_funds MODIFY status ENUM('Approved','Pending','Requested','Rejected','Cancelled') NOT NULL DEFAULT 'Approved'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('import_funds') && DB::getDriverName() === 'mysql') {
            DB::table('import_funds')->where('status', 'Requested')->update(['status' => 'Pending']);
            DB::table('import_funds')->where('status', 'Rejected')->update(['status' => 'Cancelled']);
            DB::statement("ALTER TABLE import_funds MODIFY status ENUM('Approved','Pending','Cancelled') NOT NULL DEFAULT 'Approved'");
        }
    }
};
