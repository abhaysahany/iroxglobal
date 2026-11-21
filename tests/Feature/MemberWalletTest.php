<?php

namespace Tests\Feature;

use Tests\TestCase;

class MemberWalletTest extends TestCase
{
    public function test_wallet_pages_redirect_if_unauthenticated(): void
    {
        $routes = [
            '/member/wallet/income-wallet',
            '/member/wallet/withdrawal-history',
            '/member/wallet/payout-date',
            '/member/wallet/all-transactions',
            '/member/wallet/to-p2p',
            '/member/wallet/to-p2p-history',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertRedirect(route('member.login'));
        }

        $responsePost = $this->post('/member/wallet/to-p2p/transfer', []);
        $responsePost->assertRedirect(route('member.login'));
    }

    public function test_wallet_pages_load_with_session(): void
    {
        $pages = [
            [
                'url' => '/member/wallet/income-wallet',
                'title' => 'Income Wallet',
            ],
            [
                'url' => '/member/wallet/withdrawal-history',
                'title' => 'Withdrawal History',
            ],
            [
                'url' => '/member/wallet/payout-date',
                'title' => 'Payout Date',
            ],
            [
                'url' => '/member/wallet/all-transactions',
                'title' => 'All Transactions',
            ],
            [
                'url' => '/member/wallet/to-p2p',
                'title' => 'Wallet To P2P',
            ],
            [
                'url' => '/member/wallet/to-p2p-history',
                'title' => 'Wallet To P2P History',
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
            
            if ($page['url'] === '/member/wallet/income-wallet') {
                $response->assertSee('WALLET OVERVIEW');
            } elseif ($page['url'] === '/member/wallet/withdrawal-history') {
                $response->assertSee('Withdrawal History Details');
            } elseif ($page['url'] === '/member/wallet/payout-date') {
                $response->assertSee('Payout Date Details');
            } elseif ($page['url'] === '/member/wallet/all-transactions') {
                $response->assertSee('Transaction Details');
            } elseif ($page['url'] === '/member/wallet/to-p2p') {
                $response->assertSee('WALLET TRANSFER');
            } elseif ($page['url'] === '/member/wallet/to-p2p-history') {
                $response->assertSee('Wallet To P2P History');
            }
        }
    }

    public function test_wallet_to_p2p_transfer_validation_errors(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/wallet/to-p2p/transfer', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['amount', 'security_pin']);
    }

    public function test_wallet_to_p2p_transfer_insufficient_funds(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_income_wallet_balance' => 50,
        ])->post('/member/wallet/to-p2p/transfer', [
            'amount' => 100,
            'security_pin' => '1234',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Insufficient income wallet balance.');
    }

    public function test_wallet_to_p2p_transfer_success(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_income_wallet_balance' => 150,
            'member_p2p_wallet_balance' => 10,
            'member_wallet_transaction_rows' => [],
            'member_wallet_to_p2p_history_rows' => [],
        ])->post('/member/wallet/to-p2p/transfer', [
            'amount' => 100,
            'security_pin' => '1234',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Wallet to P2P transfer completed successfully.');
        
        $this->assertEquals(50, session('member_income_wallet_balance'));
        $this->assertEquals(110, session('member_p2p_wallet_balance'));
        
        $transactions = session('member_wallet_transaction_rows');
        $this->assertCount(1, $transactions);
        $this->assertEquals('Wallet To P2P', $transactions[0]['type']);
        $this->assertEquals(0, $transactions[0]['amount_add']);
        $this->assertEquals(100, $transactions[0]['amount_deduct']);
        $this->assertEquals(50, $transactions[0]['balance']);
        $this->assertEquals('Transferred to P2P wallet', $transactions[0]['particular']);
        
        $history = session('member_wallet_to_p2p_history_rows');
        $this->assertCount(1, $history);
        $this->assertEquals('Wallet to P2P transfer', $history[0]['particular']);
        $this->assertEquals(100, $history[0]['credit']);
        $this->assertEquals(0, $history[0]['debit']);
        $this->assertEquals(110, $history[0]['balance']);
    }
}
