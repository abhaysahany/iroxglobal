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
        Schema::create('upline_members', function (Blueprint $table) {
            $table->id();
            $table->string('memberid');
            for ($i = 1; $i <= 21; $i++) {
                $table->string('upline_' . $i)->nullable();
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upline_members');
    }
};
