<?php

use App\Models\Admin;
use App\Models\MemberDetail;
use App\Models\SupportTicket;
use App\Models\TicketText;
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

    Schema::dropIfExists('support_tickets');
    Schema::create('support_tickets', function (Blueprint $table) {
        $table->id();
        $table->string('ticket_id');
        $table->string('memberid');
        $table->string('masterid')->nullable();
        $table->string('subject');
        $table->text('remark')->nullable();
        $table->enum('support_status', ['New', 'Replied'])->default('New');
        $table->enum('status', ['Open', 'Closed'])->default('Open');
        $table->timestamps();
    });

    if (! Schema::hasTable('ticket_texts')) {
        Schema::create('ticket_texts', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id');
            $table->enum('written_by', ['Admin', 'Member'])->nullable();
            $table->string('text', 1000);
            $table->text('remark')->nullable();
            $table->enum('status', ['Unread', 'Read'])->default('Unread');
            $table->timestamps();
        });
    }

    $this->admin = Admin::create([
        'name' => 'Support Admin',
        'email' => 'support@example.test',
        'password' => Hash::make('password'),
    ]);

    $this->member = MemberDetail::create([
        'memberid' => 'SUP001',
        'name' => 'Support Member',
        'wallet' => 0,
        'p2p_wallet' => 0,
    ]);

    $this->newTicket = createSupportTicket($this->member, 'TICKET-NEW', 'New', 'Open');
    $this->openTicket = createSupportTicket($this->member, 'TICKET-OPEN', 'Replied', 'Open');
    $this->closedTicket = createSupportTicket($this->member, 'TICKET-CLOSED', 'Replied', 'Closed');

    $this->session = [
        'admin_id' => $this->admin->id,
        'admin_name' => $this->admin->name,
        'MEMBER_ID' => $this->member->memberid,
    ];
});

test('support listing pages require admin authentication', function () {
    $this->get(route('admin.support.new-tickets'))->assertRedirect(route('home'));
    $this->get(route('admin.support.open-tickets'))->assertRedirect(route('home'));
    $this->get(route('admin.support.close-tickets'))->assertRedirect(route('home'));
});

test('new support page shows only new support status tickets', function () {
    $this->withSession($this->session)
        ->get(route('admin.support.new-tickets'))
        ->assertOk()
        ->assertSee('New Support Tickets')
        ->assertSee('TICKET-NEW')
        ->assertDontSee('TICKET-OPEN')
        ->assertDontSee('TICKET-CLOSED')
        ->assertSee('admin-datatable');
});

test('open support page shows every ticket with open status', function () {
    $this->withSession($this->session)
        ->get(route('admin.support.open-tickets'))
        ->assertOk()
        ->assertSee('Open Support Tickets')
        ->assertSee('TICKET-OPEN')
        ->assertSee('TICKET-NEW')
        ->assertDontSee('TICKET-CLOSED');
});

test('close support page shows closed tickets', function () {
    $this->withSession($this->session)
        ->get(route('admin.support.close-tickets'))
        ->assertOk()
        ->assertSee('Close Support Tickets')
        ->assertSee('TICKET-CLOSED')
        ->assertDontSee('TICKET-NEW')
        ->assertDontSee('TICKET-OPEN');
});

test('support ticket view action opens existing ticket details', function () {
    $this->withSession($this->session)
        ->get(route('admin.support.show', $this->newTicket))
        ->assertOk()
        ->assertSee('TICKET-NEW')
        ->assertSee('Support Member')
        ->assertSee('Test support remark');
});

test('existing sidebar support routes show the blue view button', function () {
    $this->withSession($this->session)
        ->get(route('admin.support.open'))
        ->assertOk()
        ->assertSee('TICKET-OPEN')
        ->assertSee(route('admin.support.view', $this->openTicket->id), false)
        ->assertSee('btn btn-primary', false)
        ->assertSee('>View</a>', false);

    $this->withSession($this->session)
        ->get(route('admin.support.closed'))
        ->assertOk()
        ->assertSee('TICKET-CLOSED')
        ->assertSee('>View</a>', false);
});

test('view support page shows ordered escaped ticket chat', function () {
    TicketText::create([
        'ticket_id' => $this->newTicket->ticket_id,
        'written_by' => 'Member',
        'text' => '<script>alert("x")</script>',
        'status' => 'Unread',
        'created_at' => now()->subMinute(),
    ]);
    TicketText::create([
        'ticket_id' => $this->newTicket->ticket_id,
        'written_by' => 'Admin',
        'text' => 'Safe admin reply',
        'status' => 'Unread',
        'created_at' => now(),
    ]);

    $this->withSession($this->session)
        ->get(route('admin.support.view', $this->newTicket->id))
        ->assertOk()
        ->assertSee('View Support Tickets')
        ->assertSeeInOrder(['&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;', 'Safe admin reply'], false)
        ->assertDontSee('<script>alert("x")</script>', false)
        ->assertSee('placeholder="Type and enter"', false);
});

test('admin can reply to one support ticket', function () {
    $this->withSession($this->session)
        ->post(route('admin.support.reply', $this->newTicket->id), [
            'message' => 'Admin response text',
        ])
        ->assertSessionHas('success', 'Reply sent successfully');

    $this->assertDatabaseHas('ticket_texts', [
        'ticket_id' => $this->newTicket->ticket_id,
        'written_by' => 'Admin',
        'text' => 'Admin response text',
        'status' => 'Unread',
    ]);
    expect($this->newTicket->fresh()->support_status)->toBe('Replied');
});

test('admin can close only the selected support ticket', function () {
    $this->withSession($this->session)
        ->post(route('admin.support.close', $this->newTicket->id))
        ->assertRedirect(route('admin.support.open-tickets'))
        ->assertSessionHas('success', 'Ticket closed successfully');

    expect($this->newTicket->fresh()->status)->toBe('Closed')
        ->and($this->openTicket->fresh()->status)->toBe('Open');
});

test('missing support ticket view returns required failure', function () {
    $this->withSession($this->session)
        ->from(route('admin.support.open-tickets'))
        ->get(route('admin.support.view', 999999))
        ->assertRedirect(route('admin.support.open-tickets'))
        ->assertSessionHas('failedMsg', 'Support ticket not found');
});

function createSupportTicket(MemberDetail $member, string $ticketId, string $supportStatus, string $status): SupportTicket
{
    return SupportTicket::create([
        'ticket_id' => $ticketId,
        'memberid' => $member->memberid,
        'subject' => $ticketId.' Subject',
        'remark' => 'Test support remark',
        'support_status' => $supportStatus,
        'status' => $status,
    ]);
}
