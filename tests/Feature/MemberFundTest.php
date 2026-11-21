<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MemberFundTest extends TestCase
{
    public function test_add_fund_page_redirects_if_unauthenticated(): void
    {
        $response = $this->get('/member/add-fund');

        $response->assertRedirect(route('member.login'));
    }

    public function test_add_fund_page_loads_with_session(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'member_id' => 'GLN-784512',
            ]
        ])->get('/member/add-fund');

        $response->assertStatus(200);
        $response->assertSee('Add Fund');
        $response->assertSee('Request Preview');
        $response->assertSee('Payment Instructions');
        $response->assertSee('TX9Kj7xDemoCompanyWallet123456789');
    }

    public function test_add_fund_form_validation_fails_on_empty(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/add-fund', [
            'amount' => '',
            'transaction_id' => '',
        ]);

        $response->assertSessionHasErrors(['amount', 'transaction_id']);
    }

    public function test_add_fund_form_validation_fails_on_invalid_amount(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/add-fund', [
            'amount' => 'invalid_amount',
            'transaction_id' => 'TX123456789',
        ]);

        $response->assertSessionHasErrors(['amount']);
    }

    public function test_add_fund_form_submits_successfully_without_screenshot(): void
    {
        $response = $this->from(route('member.add-fund'))->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/add-fund', [
            'amount' => '100.50',
            'transaction_id' => 'TX_DEMO_BLOCKCHAIN_ID_98765',
        ]);

        $response->assertRedirect(route('member.add-fund'));
        $response->assertSessionHas('success', 'Fund request submitted successfully.');

        // Check stored session data
        $requests = session('fund_requests');
        $this->assertNotEmpty($requests);
        $this->assertEquals('100.50', $requests[0]['amount']);
        $this->assertEquals('TX_DEMO_BLOCKCHAIN_ID_98765', $requests[0]['transaction_id']);
        $this->assertEquals('Pending', $requests[0]['status']);
        $this->assertEquals('USDT', $requests[0]['type']);
        $this->assertNull($requests[0]['screenshot']);
    }

    public function test_add_fund_form_submits_successfully_with_screenshot(): void
    {
        $file = UploadedFile::fake()->image('screenshot.png');

        $response = $this->from(route('member.add-fund'))->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/add-fund', [
            'amount' => '250.00',
            'transaction_id' => 'TX_SCREENSHOT_ID_112233',
            'transaction_screenshot' => $file,
        ]);

        $response->assertRedirect(route('member.add-fund'));
        $response->assertSessionHas('success', 'Fund request submitted successfully.');

        // Check stored session data
        $requests = session('fund_requests');
        $this->assertNotEmpty($requests);
        
        // Find our screenshot request (it will be index 0 or 1 depending on session stashing setup)
        $latest = end($requests);
        $this->assertEquals('250.00', $latest['amount']);
        $this->assertEquals('TX_SCREENSHOT_ID_112233', $latest['transaction_id']);
        $this->assertNotNull($latest['screenshot']);
        $this->assertFileExists(public_path($latest['screenshot']));

        // Clean up the created screenshot file
        if (file_exists(public_path($latest['screenshot']))) {
            unlink(public_path($latest['screenshot']));
        }
    }

    public function test_add_fund_history_page_redirects_if_unauthenticated(): void
    {
        $response = $this->get('/member/add-fund/history');

        $response->assertRedirect(route('member.login'));
    }

    public function test_add_fund_history_page_loads_with_session_empty_state(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->get('/member/add-fund/history');

        $response->assertStatus(200);
        $response->assertSee('Add Fund History');
        $response->assertSee('Add Fund Request History Table');
        $response->assertSee('No data available in table');
    }

    public function test_add_fund_history_page_loads_with_stashed_requests(): void
    {
        // Stash some requests in both fund_requests and member_fund_requests to verify robustness
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'fund_requests' => [
                [
                    'date' => '17 Jun 2026',
                    'member_id' => 'GLN-784512',
                    'transaction_id' => 'TXN_SESSION_STASH_1',
                    'amount' => '150.00',
                    'type' => 'USDT',
                    'status' => 'Pending',
                    'screenshot' => null,
                ],
                [
                    'date' => '16 Jun 2026',
                    'member_id' => 'GLN-784512',
                    'transaction_id' => 'TXN_SESSION_STASH_2',
                    'amount' => '300.50',
                    'type' => 'USDT',
                    'status' => 'Approved',
                    'screenshot' => null,
                ]
            ]
        ])->get('/member/add-fund/history');

        $response->assertStatus(200);
        $response->assertSee('TXN_SESSION_STASH_1');
        $response->assertSee('TXN_SESSION_STASH_2');
        $response->assertSee('$150.00');
        $response->assertSee('$300.50');
        $response->assertSee('Pending');
        $response->assertSee('Approved');
        $response->assertDontSee('No data available in table');
    }
}
