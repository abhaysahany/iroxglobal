<?php

namespace Tests\Feature;

use Tests\TestCase;

class MemberIncomeTest extends TestCase
{
    public function test_income_pages_redirect_if_unauthenticated(): void
    {
        $routes = [
            '/member/incomes/daily-income',
            '/member/incomes/referral-income',
            '/member/incomes/level-income',
            '/member/incomes/rank-income',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertRedirect(route('member.login'));
        }
    }

    public function test_income_pages_load_with_session(): void
    {
        $pages = [
            [
                'url' => '/member/incomes/daily-income',
                'title' => 'Daily Income',
            ],
            [
                'url' => '/member/incomes/referral-income',
                'title' => 'Referral Income',
            ],
            [
                'url' => '/member/incomes/level-income',
                'title' => 'Level Income',
            ],
            [
                'url' => '/member/incomes/rank-income',
                'title' => 'Rank Income',
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
            $response->assertDontSee('Income table will be added in the next phase.');
        }
    }

    public function test_daily_income_table_shows_empty_state_by_default(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->get('/member/incomes/daily-income');

        $response->assertStatus(200);
        $response->assertSee('Daily Income Table');
        $response->assertSee('No data available in table');
    }

    public function test_daily_income_table_shows_stashed_session_rows(): void
    {
        $rows = [
            [
                'date' => '2025-01-01',
                'member_id' => 'GLN-784512',
                'member_name' => 'John Doe',
                'credited_to' => 'Main Wallet',
                'rank' => 'Silver',
                'level_no' => 'Level 1',
                'sponsor_id' => 'GLN-100245',
                'sponsor_name' => 'Demo Sponsor',
                'activation_date' => '2025-01-01',
                'total_amount' => 500,
                'income_status' => 'Pending',
                'days_completed' => 5,
                'days_remaining' => 25,
                'amount' => 10,
                'action' => 'View'
            ]
        ];

        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_daily_income_rows' => $rows
        ])->get('/member/incomes/daily-income');

        $response->assertStatus(200);
        $response->assertSee('GLN-784512');
        $response->assertSee('John Doe');
        $response->assertSee('$500.00');
        $response->assertSee('$10.00');
        $response->assertSee('Pending');
    }

    public function test_referral_income_table_shows_empty_state_by_default(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->get('/member/incomes/referral-income');

        $response->assertStatus(200);
        $response->assertSee('Referral Income Table');
        $response->assertSee('No data available in table');
    }

    public function test_referral_income_table_shows_stashed_rows(): void
    {
        $rows = [
            [
                'date' => '2025-01-01',
                'from_member_id' => 'GLN-784513',
                'from_member_name' => 'Demo Member',
                'sponsor_id' => 'GLN-784512',
                'sponsor_name' => 'John Doe',
                'package' => 'Premium',
                'referral_type' => 'Direct',
                'commission_percent' => 10,
                'business_volume' => 500,
                'amount' => 50,
                'income_status' => 'Pending',
                'payout_status' => 'Unpaid',
                'remarks' => 'Direct referral bonus',
                'action' => 'View'
            ]
        ];

        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_referral_income_rows' => $rows
        ])->get('/member/incomes/referral-income');

        $response->assertStatus(200);
        $response->assertSee('GLN-784513');
        $response->assertSee('Demo Member');
        $response->assertSee('10%');
        $response->assertSee('$50.00');
        $response->assertSee('Pending');
        $response->assertSee('Unpaid');
    }

    public function test_level_income_table_shows_empty_state_by_default(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->get('/member/incomes/level-income');

        $response->assertStatus(200);
        $response->assertSee('Level Income Table');
        $response->assertSee('No data available in table');
    }

    public function test_level_income_table_shows_stashed_rows(): void
    {
        $rows = [
            [
                'date' => '2025-01-01',
                'level_no' => 'Level 1',
                'from_member_id' => 'GLN-784513',
                'from_member_name' => 'Demo Member',
                'downline_rank' => 'Silver',
                'package' => 'Premium',
                'business_volume' => 500,
                'commission_percent' => 5,
                'matching_status' => 'Qualified',
                'amount' => 25,
                'income_status' => 'Pending',
                'payout_status' => 'Unpaid',
                'remarks' => 'Level income generated',
                'action' => 'View'
            ]
        ];

        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_level_income_rows' => $rows
        ])->get('/member/incomes/level-income');

        $response->assertStatus(200);
        $response->assertSee('Level 1');
        $response->assertSee('GLN-784513');
        $response->assertSee('Demo Member');
        $response->assertSee('5%');
        $response->assertSee('$25.00');
        $response->assertSee('Pending');
        $response->assertSee('Unpaid');
    }

    public function test_rank_income_table_shows_empty_state_by_default(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->get('/member/incomes/rank-income');

        $response->assertStatus(200);
        $response->assertSee('Rank Income Table');
        $response->assertSee('No data available in table');
    }

    public function test_rank_income_table_shows_stashed_rows(): void
    {
        $rows = [
            [
                'date' => '2025-01-01',
                'member_id' => 'GLN-784512',
                'member_name' => 'John Doe',
                'previous_rank' => 'Silver',
                'current_rank' => 'Gold',
                'achieved_rank' => 'Gold',
                'team_business' => 2500,
                'qualified_legs' => 2,
                'rank_bonus' => 100,
                'reward_type' => 'Rank Achievement',
                'income_status' => 'Pending',
                'payout_date' => '-',
                'amount' => 100,
                'action' => 'View'
            ]
        ];

        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'member_rank_income_rows' => $rows
        ])->get('/member/incomes/rank-income');

        $response->assertStatus(200);
        $response->assertSee('GLN-784512');
        $response->assertSee('John Doe');
        $response->assertSee('Silver');
        $response->assertSee('Gold');
        $response->assertSee('$100.00');
        $response->assertSee('Pending');
    }
}
