<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fund_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->enum('type', ['Credit', 'Debit']);
            $table->decimal('amount', 14, 2);
            $table->string('wallet_type')->default('Main Wallet');
            $table->text('remark');
            $table->decimal('balance_before', 14, 2);
            $table->decimal('balance_after', 14, 2);
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'type']);
            $table->index('created_at');
            $table->unique(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_transactions');
    }
};
