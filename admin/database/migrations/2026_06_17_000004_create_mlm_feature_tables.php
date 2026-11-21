<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('members')) {
            Schema::create('members', function (Blueprint $table) {
                $table->id();
                $table->string('member_id')->unique();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('password');
                $table->string('sponsor_id')->nullable();
                $table->string('status')->default('inactive');
                $table->decimal('wallet_balance', 14, 2)->default(0);
                $table->boolean('is_active')->default(false);
                $table->timestamp('activated_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('message');
                $table->string('status')->default('Active');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('deposits')) {
            Schema::create('deposits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
                $table->decimal('amount', 14, 2);
                $table->string('payment_mode');
                $table->string('transaction_id')->nullable();
                $table->string('screenshot')->nullable();
                $table->string('status')->default('Pending');
                $table->text('admin_remark')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('activations')) {
            Schema::create('activations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
                $table->decimal('package_amount', 14, 2);
                $table->unsignedBigInteger('activated_by')->nullable();
                $table->string('status')->default('Active');
                $table->timestamp('activated_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('investments')) {
            Schema::create('investments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
                $table->decimal('amount', 14, 2);
                $table->string('plan_name');
                $table->decimal('roi_percentage', 8, 2)->nullable();
                $table->integer('duration_days')->nullable();
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->string('status')->default('Active');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('incomes')) {
            Schema::create('incomes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
                $table->string('income_type');
                $table->decimal('amount', 14, 2);
                $table->text('description')->nullable();
                $table->string('status')->default('Pending');
                $table->date('payout_date')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('p2p_transactions')) {
            Schema::create('p2p_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sender_member_id')->constrained('members')->cascadeOnDelete();
                $table->foreignId('receiver_member_id')->constrained('members')->cascadeOnDelete();
                $table->decimal('amount', 14, 2);
                $table->text('transaction_note')->nullable();
                $table->string('status')->default('Success');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('withdrawals')) {
            Schema::create('withdrawals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
                $table->decimal('amount', 14, 2);
                $table->string('payment_method');
                $table->text('account_details')->nullable();
                $table->string('status')->default('Pending');
                $table->text('admin_remark')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('support_tickets')) {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
                $table->string('subject');
                $table->text('message');
                $table->string('status')->default('Open');
                $table->string('priority')->default('Medium');
                $table->text('admin_reply')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('p2p_transactions');
        Schema::dropIfExists('incomes');
        Schema::dropIfExists('investments');
        Schema::dropIfExists('activations');
        Schema::dropIfExists('deposits');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('members');
    }
};
