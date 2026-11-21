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
        Schema::create('package_details', function (Blueprint $table) {
            $table->id();
            $table->string('package_code')->nullable();
            $table->string('package_name');
            $table->string('year_label');
            $table->decimal('registration_amount', 12, 2);
            $table->integer('multiplier');
            $table->decimal('total_amount', 12, 2);
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_details');
    }
};
