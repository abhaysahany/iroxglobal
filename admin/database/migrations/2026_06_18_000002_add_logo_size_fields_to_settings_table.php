<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'website_logo_width')) {
                $table->unsignedSmallInteger('website_logo_width')->nullable()->after('website_logo');
            }

            if (! Schema::hasColumn('settings', 'website_logo_height')) {
                $table->unsignedSmallInteger('website_logo_height')->nullable()->after('website_logo_width');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'website_logo_height')) {
                $table->dropColumn('website_logo_height');
            }

            if (Schema::hasColumn('settings', 'website_logo_width')) {
                $table->dropColumn('website_logo_width');
            }
        });
    }
};
