<?php

namespace Tests\Feature;

use Tests\TestCase;

class MemberP2PTest extends TestCase
{
    public function test_p2p_pages_redirect_if_unauthenticated(): void
    {
        $routes = [
            '/member/p2p/wallet',
            '/member/p2p/wallet-history',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertRedirect(route('member.login'));
        }

        $responsePost = $this->post('/member/p2p/wallet/transfer', []);
        $responsePost->assertRedirect(route('member.login'));
    }

    public function test_p2p_pages_load_with_session(): void
    {
        $response1 = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->get('/member/p2p/wallet');
        $response1->assertStatus(200);
        $response1->assertSee('P2P Wallet');
        $response1->assertSee('Transfer Amount');

        $response2 = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->get('/member/p2p/wallet-history');
        $response2->assertStatus(200);
        $response2->assertSee('P2P Wallet History');
        $response2->assertSee('No data available in table');
    }

    public function test_p2p_transfer_validation_errors(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/p2p/wallet/transfer', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['member_id', 'amount', 'security_pin']);
    }

    public function test_p2p_transfer_insufficient_funds(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_p2p_balance' => 50,
        ])->post('/member/p2p/wallet/transfer', [
            'member_id' => 'GLN-999',
            'amount' => 100,
            'security_pin' => '1234',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Insufficient P2P wallet balance.');
    }

    public function test_p2p_transfer_success(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_p2p_balance' => 150,
            'member_p2p_wallet_history' => [],
        ])->post('/member/p2p/wallet/transfer', [
            'member_id' => 'GLN-999',
            'amount' => 100,
            'security_pin' => '1234',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'P2P transfer submitted successfully.');
        
        $this->assertEquals(50, session('member_p2p_balance'));
        
        $history = session('member_p2p_wallet_history');
        $this->assertCount(1, $history);
        $this->assertEquals('GLN-999', $history[0]['member_id']);
        $this->assertEquals(now()->format('Y-m-d H:i'), $history[0]['date']);
        $this->assertEquals('Transfer', $history[0]['type']);
        $this->assertEquals(100, $history[0]['debit']);
        $this->assertEquals(0, $history[0]['credit']);
        $this->assertEquals(50, $history[0]['balance']);
        $this->assertEquals('P2P transfer to GLN-999', $history[0]['particular']);
    }

    public function test_p2p_history_shows_empty_state_by_default(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->get('/member/p2p/wallet-history');

        $response->assertStatus(200);
        $response->assertSee('P2P Wallet History');
        $response->assertSee('No data available in table');
    }

    public function test_p2p_history_shows_stashed_rows(): void
    {
        $rows = [
            [
                'date' => '2025-01-01 10:30',
                'member_id' => 'GLN-784512',
                'type' => 'Transfer',
                'debit' => 100,
                'credit' => 0,
                'balance' => 900,
                'particular' => 'P2P transfer to GLN-784513',
            ]
        ];

        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_p2p_wallet_history' => $rows
        ])->get('/member/p2p/wallet-history');

        $response->assertStatus(200);
        $response->assertSee('GLN-784512');
        $response->assertSee('Transfer');
        $response->assertSee('$100.00');
        $response->assertSee('$0.00');
        $response->assertSee('$900.00');
        $response->assertSee('P2P transfer to GLN-784513');
    }
}
