<?php

namespace Tests\Feature;

use Tests\TestCase;

class MemberReferralTest extends TestCase
{
    public function test_referral_pages_redirect_if_unauthenticated(): void
    {
        $routes = [
            '/member/referral/genealogy',
            '/member/referral/direct-members',
            '/member/referral/level-members',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertRedirect(route('member.login'));
        }
    }

    public function test_referral_pages_load_with_session(): void
    {
        $pages = [
            [
                'url' => '/member/referral/genealogy',
                'title' => 'Genealogy Tree',
            ],
            [
                'url' => '/member/referral/direct-members',
                'title' => 'Direct Members',
            ],
            [
                'url' => '/member/referral/level-members',
                'title' => 'Level Members',
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

    public function test_referral_pages_load_with_session_data(): void
    {
        $directRow = [
            'image' => null,
            'joining_date' => '2025-01-01',
            'activation_date' => '2025-01-05',
            'member_id' => 'GLN-784513',
            'name' => 'Demo Member',
            'mobile' => '(555) 123-4567',
            'email' => 'demo@gmail.com',
            'rank' => 'Silver',
            'joining_package' => 'Premium',
            'investment' => 500,
            'status' => 'Active',
        ];

        $levelRow = [
            'level_no' => 'Level 1',
            'total_members' => 5,
            'business' => 1500,
            'action' => 'View',
        ];

        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_direct_members_rows' => [$directRow],
            'member_level_members_rows' => [$levelRow],
        ])->get('/member/referral/direct-members');

        $response->assertStatus(200);
        $response->assertSee('GLN-784513');
        $response->assertSee('Demo Member');
        $response->assertSee('Premium');

        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_direct_members_rows' => [$directRow],
            'member_level_members_rows' => [$levelRow],
        ])->get('/member/referral/level-members');

        $response->assertStatus(200);
        $response->assertSee('Level 1');
        $response->assertSee('5');
    }
}
