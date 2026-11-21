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
        Schema::create('member_details', function (Blueprint $table) {
            $table->id();
            // Auth / Pre-existing fields
            $table->string('memberid')->nullable();
            $table->string('sponsorid')->nullable();
            $table->string('sponsor_name')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('country')->nullable();
            $table->string('mobile')->nullable();
            $table->string('password')->nullable();
            $table->string('txn_password')->nullable();
            $table->string('status')->default('Inactive');
            $table->decimal('p2p_wallet', 12, 2)->default(0.00);

            // Activation / Package page fields
            $table->string('member_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('member_name')->nullable();
            $table->unsignedBigInteger('package_detail_id')->nullable();
            $table->string('package_code')->nullable();
            $table->string('package_name')->nullable();
            $table->string('year_label')->nullable();
            $table->decimal('registration_amount', 12, 2)->nullable();
            $table->integer('multiplier')->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->string('activation_status')->default('inactive');
            $table->timestamp('activated_at')->nullable();
            $table->string('payment_status')->default('paid');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('package_detail_id')
                ->references('id')
                ->on('package_details')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_details');
    }
};
