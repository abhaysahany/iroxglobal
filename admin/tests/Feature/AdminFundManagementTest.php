<?php

use App\Models\Admin;
use App\Models\Deposit;
use App\Models\ImportFund;
use App\Models\Member;
use App\Models\MemberDetail;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

beforeEach(function () {
    if (! Schema::hasTable('member_details')) {
        Schema::create('member_details', function (Blueprint $table) {
            $table->id();
            $table->string('memberid')->unique();
            $table->string('name')->nullable();
            $table->decimal('wallet', 14, 4)->default(0);
            $table->decimal('p2p_wallet', 14, 4)->default(0);
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('import_funds')) {
        Schema::create('import_funds', function (Blueprint $table) {
            $table->id();
            $table->string('memberid');
            $table->string('txnid')->nullable();
            $table->string('orderid')->nullable();
            $table->decimal('amount', 10, 4)->default(0);
            $table->enum('type', ['Add', 'Deduct'])->default('Add');
            $table->string('wallet_type')->nullable();
            $table->string('added_by')->nullable();
            $table->enum('status', ['Approved', 'Pending', 'Requested', 'Rejected', 'Cancelled'])->default('Approved');
            $table->string('mode')->nullable();
            $table->text('image')->nullable();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('wallet_transfers')) {
        Schema::create('wallet_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('memberid');
            $table->string('walletType')->nullable();
            $table->decimal('debit', 14, 4)->nullable();
            $table->decimal('credit', 14, 4)->nullable();
            $table->decimal('balance', 14, 4)->default(0);
            $table->text('particular')->nullable();
            $table->timestamps();
        });
    }

    $this->admin = Admin::create([
        'name' => 'Test Admin',
        'email' => 'admin@example.test',
        'password' => Hash::make('password'),
    ]);

    $this->member = Member::create([
        'member_id' => 'MEM001',
        'name' => 'Test Member',
        'email' => 'member@example.test',
        'password' => Hash::make('password'),
        'wallet_balance' => 100,
    ]);

    $this->legacyMember = MemberDetail::create([
        'memberid' => 'LEG001',
        'name' => 'Legacy Member',
        'wallet' => 100,
        'p2p_wallet' => 50,
    ]);
});

test('admin can add funds and an audit transaction is stored', function () {
    $response = $this->withSession(['admin_id' => $this->admin->id])
        ->post(route('admin.funds.add.submit'), [
            'memberid' => $this->legacyMember->memberid,
            'wallet_type' => 'wallet',
            'amount' => 25.50,
        ]);

    $response->assertSessionHas('success', 'Amount has been added successfully to Member Wallet');
    expect((float) $this->legacyMember->fresh()->wallet)->toBe(125.50);
    $this->assertDatabaseHas('import_funds', [
        'memberid' => $this->legacyMember->memberid,
        'type' => 'Add',
        'wallet_type' => 'Wallet',
        'amount' => 25.50,
        'status' => 'Approved',
        'mode' => 'Mannual',
        'added_by' => 'Admin',
    ]);
    $this->assertDatabaseHas('wallet_transfers', [
        'memberid' => $this->legacyMember->memberid,
        'walletType' => 'Cash Wallet Fund Transfer',
        'debit' => 25.50,
        'balance' => 125.50,
        'particular' => '₹ 25.5 has been transferred to your cash wallet by Admin',
    ]);
});

test('admin cannot deduct more than the available balance', function () {
    $response = $this->withSession(['admin_id' => $this->admin->id])
        ->post(route('admin.funds.deduct.submit'), [
            'memberid' => $this->legacyMember->memberid,
            'wallet_type' => 'wallet',
            'amount' => 101,
        ]);

    $response->assertSessionHas('failed', 'This user has less balance to requested amount');
    expect((float) $this->legacyMember->fresh()->wallet)->toBe(100.0);
    $this->assertDatabaseCount('import_funds', 0);
});

test('admin can deduct funds from only the selected wallet', function () {
    $this->withSession(['admin_id' => $this->admin->id])
        ->post(route('admin.funds.deduct.submit'), [
            'memberid' => $this->legacyMember->memberid,
            'wallet_type' => 'p2p_wallet',
            'amount' => 20,
        ])
        ->assertSessionHas('success', 'Amount has been deducted successfully from Member P2P Wallet');

    $member = $this->legacyMember->fresh();

    expect((float) $member->wallet)->toBe(100.0)
        ->and((float) $member->p2p_wallet)->toBe(30.0);

    $this->assertDatabaseHas('import_funds', [
        'memberid' => $member->memberid,
        'type' => 'Deduct',
        'wallet_type' => 'P2P Wallet',
        'amount' => 20,
    ]);
    $this->assertDatabaseHas('wallet_transfers', [
        'memberid' => $member->memberid,
        'walletType' => 'Purchase Wallet Fund Transfer',
        'credit' => 20,
        'balance' => 30,
        'particular' => '₹ 20 has been deducted from your purchase wallet by Admin',
    ]);
});

test('admin can add funds to the fund wallet', function () {
    $this->withSession(['admin_id' => $this->admin->id])
        ->post(route('admin.funds.add.submit'), [
            'memberid' => $this->legacyMember->memberid,
            'wallet_type' => 'p2p_wallet',
            'amount' => 30,
        ])
        ->assertSessionHas('success', 'Amount has been added successfully to Member P2P Wallet');

    $member = $this->legacyMember->fresh();

    expect((float) $member->wallet)->toBe(100.0)
        ->and((float) $member->p2p_wallet)->toBe(80.0);

    $this->assertDatabaseHas('import_funds', [
        'memberid' => $member->memberid,
        'wallet_type' => 'P2P Wallet',
        'amount' => 30,
    ]);
});

test('unknown memberid returns the required failure message', function () {
    $this->withSession(['admin_id' => $this->admin->id])
        ->post(route('admin.funds.add.submit'), [
            'memberid' => 'UNKNOWN',
            'wallet_type' => 'wallet',
            'amount' => 10,
        ])
        ->assertSessionHas('failed', 'Entered Memberid is not Available. Please change memberid');

    $this->assertDatabaseCount('import_funds', 0);
    $this->assertDatabaseCount('wallet_transfers', 0);
});

test('invalid fund input is rejected', function () {
    $this->withSession(['admin_id' => $this->admin->id])
        ->post(route('admin.funds.deduct.submit'), [
            'memberid' => $this->legacyMember->memberid,
            'wallet_type' => 'invalid_wallet',
            'amount' => 0,
        ])
        ->assertSessionHas('failed', 'Invalid Input Entered')
        ->assertSessionHasErrors(['wallet_type', 'amount']);

    expect((float) $this->legacyMember->fresh()->wallet)->toBe(100.0)
        ->and((float) $this->legacyMember->fresh()->p2p_wallet)->toBe(50.0);
});

test('admin can accept a pending import fund request only once', function () {
    $fundRequest = ImportFund::create([
        'memberid' => $this->legacyMember->memberid,
        'amount' => 25,
        'orderid' => '12345',
        'txnid' => 'TXN-100',
        'type' => 'Add',
        'wallet_type' => 'P2P Wallet',
        'status' => 'Pending',
        'mode' => 'Online',
    ]);

    $this->withSession(['admin_id' => $this->admin->id])
        ->post(route('admin.funds.requests.accept', $fundRequest->id))
        ->assertSessionHas('success', 'Fund request approved successfully');

    $this->withSession(['admin_id' => $this->admin->id])
        ->post(route('admin.funds.requests.accept', $fundRequest->id))
        ->assertSessionHas('failed');

    expect((float) $this->legacyMember->fresh()->p2p_wallet)->toBe(75.0);
    $this->assertDatabaseHas('import_funds', [
        'id' => $fundRequest->id,
        'status' => 'Approved',
        'added_by' => 'Admin',
    ]);
    $this->assertDatabaseCount('wallet_transfers', 1);
});

test('admin can reject a requested import fund request without crediting wallet', function () {
    $fundRequest = ImportFund::create([
        'memberid' => $this->legacyMember->memberid,
        'amount' => 25,
        'orderid' => '54321',
        'txnid' => 'TXN-200',
        'type' => 'Add',
        'wallet_type' => 'Wallet',
        'status' => 'Requested',
        'mode' => 'Online',
    ]);

    $this->withSession(['admin_id' => $this->admin->id])
        ->post(route('admin.funds.requests.reject', $fundRequest->id))
        ->assertSessionHas('success', 'Fund request rejected successfully');

    expect((float) $this->legacyMember->fresh()->wallet)->toBe(100.0);
    $this->assertDatabaseHas('import_funds', [
        'id' => $fundRequest->id,
        'status' => 'Rejected',
    ]);
    $this->assertDatabaseCount('wallet_transfers', 0);
});

test('new funds requests page shows only requested and pending import funds', function () {
    foreach ([
        ['txnid' => 'PENDING-TXN', 'status' => 'Pending'],
        ['txnid' => 'REQUESTED-TXN', 'status' => 'Requested'],
        ['txnid' => 'APPROVED-TXN', 'status' => 'Approved'],
    ] as $index => $requestData) {
        ImportFund::create([
            'memberid' => $this->legacyMember->memberid,
            'amount' => 10 + $index,
            'orderid' => (string) (10000 + $index),
            'txnid' => $requestData['txnid'],
            'type' => 'Add',
            'wallet_type' => 'Wallet',
            'status' => $requestData['status'],
            'mode' => 'Online',
        ]);
    }

    $this->withSession(['admin_id' => $this->admin->id])
        ->get(route('admin.funds.requests.new'))
        ->assertOk()
        ->assertSee('New Funds Requests')
        ->assertSee('PENDING-TXN')
        ->assertSee('REQUESTED-TXN')
        ->assertDontSee('APPROVED-TXN');
});

test('pending request is credited only once', function () {
    $deposit = Deposit::create([
        'member_id' => $this->member->id,
        'amount' => 40,
        'payment_mode' => 'Bank Transfer',
        'status' => 'Pending',
    ]);

    $this->withSession(['admin_id' => $this->admin->id])
        ->post(route('admin.funds.requests.approve', $deposit))
        ->assertSessionHas('success');

    $this->withSession(['admin_id' => $this->admin->id])
        ->post(route('admin.funds.requests.approve', $deposit))
        ->assertSessionHas('error');

    expect((float) $this->member->fresh()->wallet_balance)->toBe(140.0);
    $this->assertDatabaseCount('fund_transactions', 1);
});

test('fund routes require admin authentication', function () {
    $this->get(route('admin.funds.add'))->assertRedirect(route('home'));
    $this->get(route('admin.funds.details'))->assertRedirect(route('home'));
    $this->get(route('admin.funds.requests.new'))->assertRedirect(route('home'));
});

test('admin can open every funds management page', function () {
    $session = ['admin_id' => $this->admin->id, 'admin_name' => $this->admin->name];

    foreach ([
        'admin.funds.add',
        'admin.funds.deduct',
        'admin.funds.details',
        'admin.funds.requests.new',
        'admin.funds.requests.details',
    ] as $routeName) {
        $this->withSession($session)->get(route($routeName))->assertOk();
    }
});
