<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PackageDetail;
use App\Models\MemberDetail;
use Tests\TestCase;

class MemberActivationTest extends TestCase
{
    use RefreshDatabase;

    public function test_activation_page_redirects_if_unauthenticated(): void
    {
        $response = $this->get('/member/activation');
        $response->assertRedirect(route('member.login'));
    }

    public function test_activation_page_loads_with_session_and_seeds_packages(): void
    {
        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'member_id' => 'GLN-784512',
            ]
        ])->get('/member/activation');

        $response->assertStatus(200);
        $response->assertSee('Activate Your Account');
        $response->assertSee('ACTIVATION');
        $response->assertSee('Package A');
        $response->assertSee('Package B');
        $response->assertSee('Package C');
        $response->assertSee('$210');
        $response->assertSee('$280');
        $response->assertSee('$350');
        $response->assertSee('Inactive');
    }

    public function test_activation_can_buy_package(): void
    {
        // First load page to trigger seeding
        $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'member_id' => 'GLN-784512',
            ]
        ])->get('/member/activation');

        $package = PackageDetail::where('package_code', 'A')->first();
        $this->assertNotNull($package);

        $response = $this->withSession([
            'member' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'member_id' => 'GLN-784512',
            ]
        ])->post('/member/activation/buy', [
            'package_detail_id' => $package->id,
        ]);

        $response->assertRedirect(route('member.activation.index'));
        $response->assertSessionHas('success', 'Package purchased successfully. Your account is now active.');

        // Verify record exists in member_details
        $memberDetail = MemberDetail::where('member_id', 'GLN-784512')->first();
        $this->assertNotNull($memberDetail);
        $this->assertEquals('active', $memberDetail->activation_status);
        $this->assertEquals($package->id, $memberDetail->package_detail_id);
    }
}
