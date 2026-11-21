<?php

namespace Tests\Feature;

use Tests\TestCase;

class MemberSupportTest extends TestCase
{
    public function test_support_pages_redirect_if_unauthenticated(): void
    {
        $routes = [
            '/member/support/create-ticket',
            '/member/support/tickets',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertRedirect(route('member.login'));
        }
    }

    public function test_support_pages_load_with_session(): void
    {
        $pages = [
            [
                'url' => '/member/support/create-ticket',
                'title' => 'Create Ticket',
            ],
            [
                'url' => '/member/support/tickets',
                'title' => 'Support Tickets',
            ],
        ];

        foreach ($pages as $page) {
            $response = $this->withSession([
                'member' => [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                ]
            ])->get($page['url']);

            $response->assertStatus(200);
            $response->assertSee($page['title']);
        }
    }

    public function test_store_ticket_creates_and_redirects(): void
    {
        $postData = [
            'member_id' => 'GLN-784512',
            'subject' => 'Payment Issue',
            'issue' => 'I cannot verify my P2P wallet payment.',
        ];

        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/support/create-ticket', $postData);

        $response->assertRedirect(route('member.support.tickets'));
        $response->assertSessionHas('success', 'Support ticket created successfully.');

        // Verify that the ticket is in the session support tickets list
        $tickets = session('member_support_tickets', []);
        $this->assertCount(1, $tickets);
        $this->assertEquals('SUP-1001', $tickets[0]['ticket_id']);
        $this->assertEquals('Payment Issue', $tickets[0]['subject']);
        $this->assertEquals('Open', $tickets[0]['status']);
    }

    public function test_support_tickets_load_with_session_data(): void
    {
        $ticketRow = [
            'ticket_id' => 'SUP-1001',
            'member_id' => 'VM104081',
            'subject' => 'Verify transaction',
            'issue' => 'Transaction #5672 is not approved yet.',
            'created_date' => '2025-01-01 10:30',
            'status' => 'Open',
            'priority' => 'Normal',
            'last_reply' => '-',
            'action' => 'View',
        ];

        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_support_tickets' => [$ticketRow],
        ])->get('/member/support/tickets');

        $response->assertStatus(200);
        $response->assertSee('SUP-1001');
        $response->assertSee('Verify transaction');
        $response->assertSee('Open');
    }
}
