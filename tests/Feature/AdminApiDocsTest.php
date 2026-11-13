<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminApiDocsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get(route('admin.api-docs'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_staff_user_can_view_api_documentation(): void
    {
        $staff = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($staff)->get(route('admin.api-docs'));

        $response->assertOk();
        $response->assertSee('API reference');
        $response->assertSee('Admin panel is web-based');
    }

    public function test_non_staff_user_cannot_view_api_documentation(): void
    {
        $reader = User::factory()->create(['role' => 'reader']);

        $response = $this->actingAs($reader)->get(route('admin.api-docs'));

        $response->assertForbidden();
    }
}
