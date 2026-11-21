<?php

use App\Models\Admin;
use App\Models\MemberDetail;
use App\Models\WithdrawalRequest;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
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

    if (! Schema::hasTable('withdrawal_requests')) {
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->dateTime('request_date');
            $table->dateTime('payment_date')->nullable();
            $table->string('request_id');
            $table->string('batch_withdrawal_id')->nullable();
            $table->string('txnid')->nullable();
            $table->string('utr')->nullable();
            $table->text('other_detail')->nullable();
            $table->string('image')->nullable();
            $table->string('memberid');
            $table->string('masterid')->nullable();
            $table->string('name')->nullable();
            $table->string('wallet_address')->nullable();
            $table->string('type');
            $table->decimal('gross_amount', 10, 4)->default(0);
            $table->decimal('service_charge', 10, 4)->default(0);
            $table->decimal('net_amount', 10, 4)->default(0);
            $table->string('status')->nullable()->default('Pending');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    $this->admin = Admin::create([
        'name' => 'Payment Admin',
        'email' => 'payments@example.test',
        'password' => Hash::make('password'),
    ]);

    $this->member = MemberDetail::create([
        'memberid' => 'PAY001',
        'name' => 'Payment Member',
        'wallet' => 100,
        'p2p_wallet' => 0,
    ]);

    $this->session = [
        'admin_id' => $this->admin->id,
        'admin_name' => $this->admin->name,
    ];
});

test('payment management pages require admin authentication', function () {
    $this->get(route('admin.payment.requests.new'))->assertRedirect(route('home'));
    $this->get(route('admin.payment.requests.cancelled'))->assertRedirect(route('home'));
    $this->get(route('admin.payment.history'))->assertRedirect(route('home'));
});

test('new withdrawal page shows pending legacy withdrawal requests and modal fields', function () {
    createLegacyWithdrawalRequest($this->member, 'Pending', 20);
    createLegacyWithdrawalRequest($this->member, 'Approved', 40);

    $this->withSession($this->session)
        ->get(route('admin.payment.requests.new'))
        ->assertOk()
        ->assertSee('20.00')
        ->assertDontSee('40.00')
        ->assertSee('name="wid"', false)
        ->assertSee('name="utr"', false)
        ->assertSee('name="otherdetail"', false)
        ->assertSee('name="image"', false)
        ->assertSee('name="rid"', false)
        ->assertSee('name="reason"', false);
});

test('wAccept stores utr details and uploaded payment proof', function () {
    $withdrawal = createLegacyWithdrawalRequest($this->member, 'Pending', 25);

    $this->withSession($this->session)
        ->post(route('admin.withdrawal.accept'), [
            'wid' => $withdrawal->id,
            'utr' => 'UTR-12345',
            'otherdetail' => 'Paid through bank',
            'image' => UploadedFile::fake()->create('proof.jpg', 10, 'image/jpeg'),
        ])
        ->assertSessionHas('successMsg', 'Payment has been accepted successfully!');

    $withdrawal->refresh();

    expect($withdrawal->status)->toBe('Approved')
        ->and($withdrawal->utr)->toBe('UTR-12345')
        ->and($withdrawal->other_detail)->toBe('Paid through bank')
        ->and($withdrawal->image)->toStartWith('txn')
        ->and($withdrawal->image)->toEndWith('.jpg')
        ->and(is_file(public_path('uploads/'.$withdrawal->image)))->toBeTrue();

    File::delete(public_path('uploads/'.$withdrawal->image));
});

test('wAccept blocks invalid image types and duplicate approval', function () {
    $pending = createLegacyWithdrawalRequest($this->member, 'Pending', 25);

    $this->withSession($this->session)
        ->post(route('admin.withdrawal.accept'), [
            'wid' => $pending->id,
            'utr' => 'UTR-INVALID',
            'image' => UploadedFile::fake()->create('proof.gif', 10, 'image/gif'),
        ])
        ->assertSessionHas('failedMsg', 'Allowed image type is jpg, jpeg, png. Please change image type');

    expect($pending->fresh()->status)->toBe('Pending');

    $approved = createLegacyWithdrawalRequest($this->member, 'Approved', 25);

    $this->withSession($this->session)
        ->post(route('admin.withdrawal.accept'), [
            'wid' => $approved->id,
            'utr' => 'UTR-DUPLICATE',
            'image' => UploadedFile::fake()->create('proof.png', 10, 'image/png'),
        ])
        ->assertSessionHas('failedMsg', 'This payment request is already approved');
});

test('wCancel cancels request and refunds only the member wallet', function () {
    $withdrawal = createLegacyWithdrawalRequest($this->member, 'Pending', 35);

    $this->withSession($this->session)
        ->post(route('admin.withdrawal.cancel'), [
            'rid' => $withdrawal->id,
            'reason' => 'Incorrect wallet address',
        ])
        ->assertSessionHas('successMsg', 'Payment has been cancelled successfully!');

    expect($withdrawal->fresh()->status)->toBe('Cancelled')
        ->and($withdrawal->fresh()->reason)->toBe('Incorrect wallet address')
        ->and((float) $this->member->fresh()->wallet)->toBe(135.0);

    $this->assertDatabaseHas('wallet_transfers', [
        'memberid' => $this->member->memberid,
        'walletType' => 'Withdrawal Request Cancelled',
        'debit' => 35,
        'balance' => 135,
        'particular' => ' $ 35 have been added to Member wallet due to withdrawal request cancellation',
    ]);
});

test('wCancel blocks cancelled and approved requests', function () {
    $cancelled = createLegacyWithdrawalRequest($this->member, 'Cancelled', 20);
    $approved = createLegacyWithdrawalRequest($this->member, 'Approved', 20);

    $this->withSession($this->session)
        ->post(route('admin.withdrawal.cancel'), ['rid' => $cancelled->id, 'reason' => 'Again'])
        ->assertSessionHas('failedMsg', 'This payment request is already cancelled');

    $this->withSession($this->session)
        ->post(route('admin.withdrawal.cancel'), ['rid' => $approved->id, 'reason' => 'Not allowed'])
        ->assertSessionHas('failedMsg', 'Approved payment request cannot be cancelled');

    expect((float) $this->member->fresh()->wallet)->toBe(100.0);
    $this->assertDatabaseCount('wallet_transfers', 0);
});

test('cancelled requests and payment history use legacy withdrawal records', function () {
    createLegacyWithdrawalRequest($this->member, 'Cancelled', 51);
    createLegacyWithdrawalRequest($this->member, 'Approved', 52);

    $this->withSession($this->session)
        ->get(route('admin.payment.requests.cancelled'))
        ->assertOk()
        ->assertSee('51.00')
        ->assertDontSee('52.00');

    $this->withSession($this->session)
        ->get(route('admin.payment.history', ['status' => 'Approved', 'memberid' => 'PAY001']))
        ->assertOk()
        ->assertSee('52.00')
        ->assertDontSee('51.00');
});

function createLegacyWithdrawalRequest(MemberDetail $member, string $status, float $amount): WithdrawalRequest
{
    return WithdrawalRequest::create([
        'request_date' => now(),
        'request_id' => 'REQ'.random_int(10000, 99999),
        'memberid' => $member->memberid,
        'name' => $member->name,
        'wallet_address' => '0x123456789',
        'type' => 'Member Wallet',
        'gross_amount' => $amount,
        'service_charge' => 0,
        'net_amount' => $amount,
        'status' => $status,
    ]);
}
