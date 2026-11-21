<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('member.login'));
    }

    public function test_sidebar_rank_card_structure(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'member_id' => 'GLN-784512',
                'rank' => 'Platinum',
                'role' => 'Executive',
            ]
        ])->get('/member/profile');

        $response->assertStatus(200);
        $response->assertSee('sidebar-rank-card');
        $response->assertSee('sidebar-rank-info');
        $response->assertSee('sidebar-rank-label');
        $response->assertSee('sidebar-rank-name');
        $response->assertSee('sidebar-rank-icon');
        $response->assertSee('sidebar-rank-progress');
        $response->assertSee('sidebar-rank-progress-track');
        $response->assertSee('sidebar-rank-progress-fill');
        $response->assertSee('sidebar-rank-progress-text');
        
        // Check navigation menu items
        $response->assertSee('Wallet');
        $response->assertSee('Support');
        $response->assertDontSee('fa-gear'); // settings icon
        $response->assertDontSee('<span>Settings</span>', false); // settings label
        $response->assertSee('data-sidebar-dropdown-toggle');
        
        // Check header items
        $response->assertDontSee('placeholder="Search anything..."', false); // search bar is commented out
    }
}
