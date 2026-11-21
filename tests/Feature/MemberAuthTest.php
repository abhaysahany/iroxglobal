<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\MemberDetail;
use App\Models\UplineMember;
use Tests\TestCase;

class MemberAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_page_loads_successfully(): void
    {
        $response = $this->get('/member/signup');

        $response->assertStatus(200);
        $response->assertSee('Create Your Account');
        $response->assertSee('Enter Sponsor Id');
        $response->assertSee('Enter Name');
        $response->assertSee('Enter Email');
        $response->assertSee('Enter Mobile');
        $response->assertSee('Enter Password');
        $response->assertSee('Enter Security Pin');
        $response->assertSee('Select Country');
        $response->assertSee('Confirm Password');

        $response->assertDontSee('Username');
        $response->assertDontSee('Terms & Conditions');
    }

    public function test_signup_validation_fails_on_empty_fields(): void
    {
        $response = $this->post('/member/signup', [
            'sponsor' => '',
            'name' => '',
            'email' => '',
            'mobile' => '',
            'country' => '',
            'password' => '',
            'txn_password' => '',
        ]);

        $response->assertSessionHasErrors(['sponsor', 'name', 'email', 'mobile', 'country', 'password', 'txn_password']);
    }

    public function test_signup_succeeds_with_valid_sponsor(): void
    {
        // 1. Create a sponsor
        $sponsor = new MemberDetail();
        $sponsor->memberid = 'GLN-111111';
        $sponsor->name = 'Sponsor John';
        $sponsor->email = 'sponsor@example.com';
        $sponsor->mobile = '9876543210';
        $sponsor->location = 'India';
        $sponsor->status = 'Active';
        $sponsor->password = bcrypt('password123');
        $sponsor->txn_password = bcrypt('password123');
        $sponsor->save();

        // 2. Create upline member record for the sponsor to prevent updateUpline crash
        $upline = new UplineMember();
        $upline->memberid = 'GLN-111111';
        $upline->upline_1 = 'Root';
        $upline->save();

        // 3. Submit signup
        $response = $this->post('/member/signup', [
            'sponsor' => 'GLN-111111',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'mobile' => '9998887776',
            'country' => 'United States',
            'password' => 'password123',
            'txn_password' => 'secpin123',
        ]);

        // 4. Should redirect to account created page
        $response->assertRedirect('/member/account-created');

        // 5. Verify record exists
        $newMember = MemberDetail::where('email', 'jane@example.com')->first();
        $this->assertNotNull($newMember);
        $this->assertEquals('Jane Doe', $newMember->name);
        $this->assertEquals('GLN-111111', $newMember->sponsorid);
    }

    public function test_account_created_page_loads_successfully(): void
    {
        $response = $this->get('/member/account-created');

        $response->assertStatus(200);
        $response->assertSee('Account Created Successfully');
        $response->assertSee('Your account has been created successfully. Please login to continue.');
        $response->assertSee('Welcome to GreenLife Network');
        $response->assertSee('Login Now');
    }

    public function test_live_sponsor_check_returns_correct_json(): void
    {
        // 1. Empty sponsor
        $response = $this->postJson('/member/check-sponsor', ['sponsor' => '']);
        $response->assertStatus(200)
            ->assertJson([
                'status' => false,
                'message' => 'Enter Sponsor Id'
            ]);

        // 2. Non-existent sponsor
        $response = $this->postJson('/member/check-sponsor', ['sponsor' => 'GLN-NOTFOUND']);
        $response->assertStatus(200)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid Sponsor Id'
            ]);

        // 3. Valid sponsor
        $sponsor = new MemberDetail();
        $sponsor->memberid = 'GLN-222222';
        $sponsor->name = 'Verified Sponsor';
        $sponsor->email = 'verified@example.com';
        $sponsor->mobile = '9876543222';
        $sponsor->location = 'India';
        $sponsor->status = 'Active';
        $sponsor->password = bcrypt('password123');
        $sponsor->txn_password = bcrypt('password123');
        $sponsor->save();

        $response = $this->postJson('/member/check-sponsor', ['sponsor' => 'GLN-222222']);
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Sponsor found',
                'name' => 'Verified Sponsor'
            ]);

        // 4. Inactive sponsor
        $sponsor2 = new MemberDetail();
        $sponsor2->memberid = 'GLN-333333';
        $sponsor2->name = 'Inactive Sponsor';
        $sponsor2->email = 'inactive@example.com';
        $sponsor2->mobile = '9876543233';
        $sponsor2->location = 'India';
        $sponsor2->status = 'Inactive';
        $sponsor2->password = bcrypt('password123');
        $sponsor2->txn_password = bcrypt('password123');
        $sponsor2->save();

        $response = $this->postJson('/member/check-sponsor', ['sponsor' => 'GLN-333333']);
        $response->assertStatus(200)
            ->assertJson([
                'status' => false,
                'message' => 'Sponsor account is not activated.'
            ]);
    }
}
