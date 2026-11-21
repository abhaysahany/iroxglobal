<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MemberProfileTest extends TestCase
{
    public function test_profile_page_redirects_if_unauthenticated(): void
    {
        $response = $this->get('/member/profile');

        $response->assertRedirect(route('member.login'));
    }

    public function test_profile_page_loads_with_session(): void
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
        $response->assertSee('My Profile');
        $response->assertSee('John Doe');
        $response->assertSee('john@example.com');
    }

    public function test_profile_can_be_updated(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'member_id' => 'GLN-784512',
                'rank' => 'Platinum',
                'role' => 'Executive',
            ]
        ])->post('/member/profile/update', [
            'name' => 'Jane Doe',
            'username' => 'jane_doe',
            'email' => 'jane@example.com',
            'phone_code' => 'US +1',
            'phone' => '(555) 987-6543',
            'country' => 'United States',
            'address' => '456 Oak Road',
            'city' => 'Boston',
            'state' => 'MA',
            'zip' => '02108',
        ]);

        $response->assertRedirect(route('member.profile'));
        $response->assertSessionHas('success');

        // Check if session contains updated values
        $this->assertEquals('Jane Doe', session('member.name'));
        $this->assertEquals('jane_doe', session('member.username'));
        $this->assertEquals('jane@example.com', session('member.email'));
        $this->assertEquals('456 Oak Road', session('member.address'));
    }

    public function test_password_can_be_updated_demo(): void
    {
        $response = $this->from(route('member.profile'))->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/profile/password', [
            'current_password' => 'old_password_123',
            'password' => 'new_password_123',
            'password_confirmation' => 'new_password_123',
        ]);

        $response->assertRedirect(route('member.profile'));
        $response->assertSessionHas('success');
    }

    public function test_avatar_can_be_updated(): void
    {
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/profile/avatar', [
            'avatar' => $file,
        ]);

        $response->assertRedirect(route('member.profile'));
        $response->assertSessionHas('success');

        $avatarPath = session('member.avatar');
        $this->assertNotNull($avatarPath);
        $this->assertFileExists(public_path($avatarPath));

        // Cleanup
        if (file_exists(public_path($avatarPath))) {
            unlink(public_path($avatarPath));
        }
    }

    public function test_summary_page_redirects_if_unauthenticated(): void
    {
        $response = $this->get('/member/profile/summary');

        $response->assertRedirect(route('member.login'));
    }

    public function test_summary_page_loads_with_session(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'member_id' => 'GLN-784512',
                'rank' => 'Platinum',
                'role' => 'Executive',
            ]
        ])->get('/member/profile/summary');

        $response->assertStatus(200);
        $response->assertSee('User Summary');
        $response->assertSee('John Doe');
        $response->assertSee('GLN-784512');
        $response->assertSee('$25,680.50');
        $response->assertSee('78%');
    }

    public function test_change_password_page_redirects_if_unauthenticated(): void
    {
        $response = $this->get('/member/profile/change-password');

        $response->assertRedirect(route('member.login'));
    }

    public function test_change_password_page_loads_with_session(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->get('/member/profile/change-password');

        $response->assertStatus(200);
        $response->assertSee('Change Password');
        $response->assertSee('Update Password');
        $response->assertSee('Security Tips');
    }

    public function test_change_password_form_validation_fails(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/profile/change-password', [
            'current_password' => 'short',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors(['current_password', 'password']);
    }

    public function test_change_password_form_submits_successfully(): void
    {
        $response = $this->from(route('member.profile.change-password'))->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/profile/change-password', [
            'current_password' => 'old_password_123',
            'password' => 'new_password_123',
            'password_confirmation' => 'new_password_123',
        ]);

        $response->assertRedirect(route('member.profile.change-password'));
        $response->assertSessionHas('success', 'Password updated successfully.');
    }

    public function test_wallet_address_page_redirects_if_unauthenticated(): void
    {
        $response = $this->get('/member/profile/wallet-address');

        $response->assertRedirect(route('member.login'));
    }

    public function test_wallet_address_page_loads_with_session(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->get('/member/profile/wallet-address');

        $response->assertStatus(200);
        $response->assertSee('Wallet Address');
        $response->assertSee('Update Wallet Address');
        $response->assertSee('Wallet Verification');
    }

    public function test_wallet_address_form_validation_fails(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/profile/wallet-address', [
            'wallet_type' => '',
            'wallet_address' => '',
            'network' => '',
        ]);

        $response->assertSessionHasErrors(['wallet_type', 'wallet_address', 'network']);
    }

    public function test_wallet_address_form_submits_successfully(): void
    {
        $response = $this->from(route('member.profile.wallet-address'))->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]
        ])->post('/member/profile/wallet-address', [
            'wallet_type' => 'USDT BEP20',
            'wallet_address' => '0xDemoWalletAddressBEP20123456',
            'network' => 'BEP20',
            'account_holder' => 'John Doe',
            'country' => 'United States',
        ]);

        $response->assertRedirect(route('member.profile.wallet-address'));
        $response->assertSessionHas('success', 'Wallet address updated successfully.');

        // Check if session contains updated values
        $this->assertEquals('USDT BEP20', session('wallet.wallet_type'));
        $this->assertEquals('0xDemoWalletAddressBEP20123456', session('wallet.wallet_address'));
        $this->assertEquals('BEP20', session('wallet.network'));
    }
}
