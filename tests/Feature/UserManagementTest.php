<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\ModuleDiscoveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        ModuleDiscoveryService::syncPermissionsToDatabase();

        $this->adminUser = User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@safero-test.com',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $this->adminUser->assignRole($adminRole);
        }
    }

    public function test_admin_can_view_users_list_and_stats(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee('المستخدمون والصلاحيات');
        $response->assertSee($this->adminUser->name);
        $response->assertSee($this->adminUser->email);
    }

    public function test_admin_can_create_new_user_with_role_and_permissions(): void
    {
        $inspectorRole = Role::where('name', 'inspector')->firstOrFail();
        $customPerm = Permission::where('name', 'certificates.approve')->firstOrFail();

        $userData = [
            'name' => 'Eng. Ahmed Inspector',
            'email' => 'ahmed.inspector@safero.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'phone' => '+201099887766',
            'job_title_ar' => 'مهندس فحص رافعات معتمد',
            'job_title_en' => 'Certified Crane Inspector',
            'role_id' => $inspectorRole->id,
            'is_active' => 1,
            'permissions' => [$customPerm->id],
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('users.store'), $userData);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'ahmed.inspector@safero.com',
            'name' => 'Eng. Ahmed Inspector',
            'job_title_ar' => 'مهندس فحص رافعات معتمد',
            'is_active' => 1,
        ]);

        $newUser = User::where('email', 'ahmed.inspector@safero.com')->firstOrFail();
        $this->assertTrue($newUser->hasRole('inspector'));
        $this->assertTrue($newUser->hasPermissionTo('certificates.approve'));
    }

    public function test_admin_can_update_user_details_and_roles(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old.email@safero.com',
            'role' => 'viewer',
            'is_active' => true,
        ]);

        $managerRole = Role::where('name', 'inspection_manager')->firstOrFail();

        $updateData = [
            'name' => 'Updated Manager Name',
            'email' => 'updated.email@safero.com',
            'phone' => '+201122334455',
            'job_title_ar' => 'مدير قسم الفحص الهندسي',
            'job_title_en' => 'Inspection Department Manager',
            'role_id' => $managerRole->id,
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('users.update', $user), $updateData);

        $response->assertRedirect(route('users.index'));

        $user->refresh();
        $this->assertEquals('Updated Manager Name', $user->name);
        $this->assertEquals('updated.email@safero.com', $user->email);
        $this->assertTrue($user->hasRole('inspection_manager'));
    }

    public function test_admin_can_toggle_user_active_status(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->patch(route('users.toggle_status', $user));

        $response->assertRedirect();
        $user->refresh();
        $this->assertFalse($user->is_active);

        // Toggle back to active
        $this->actingAs($this->adminUser)
            ->patch(route('users.toggle_status', $user));

        $user->refresh();
        $this->assertTrue($user->is_active);
    }

    public function test_admin_cannot_delete_self_or_last_admin(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->delete(route('users.destroy', $this->adminUser));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $this->adminUser->id]);
    }

    public function test_module_discovery_service_auto_discovers_pluggable_modules(): void
    {
        $modules = ModuleDiscoveryService::all();

        $this->assertArrayHasKey('certificates', $modules);
        $this->assertArrayHasKey('training', $modules);
        $this->assertArrayHasKey('customers', $modules);
        $this->assertArrayHasKey('users', $modules);
        $this->assertArrayHasKey('environmental', $modules);

        $this->assertDatabaseHas('permissions', [
            'name' => 'environmental.create',
            'module' => 'environmental',
        ]);
    }

    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_all_permissions_returns_support_collection_for_admin_and_non_admin(): void
    {
        $adminPermissions = $this->adminUser->allPermissions();
        $this->assertInstanceOf(Collection::class, $adminPermissions);

        $inspectorRole = Role::where('name', 'inspector')->firstOrFail();
        $nonAdmin = User::factory()->create(['role' => 'inspector']);
        $nonAdmin->assignRole($inspectorRole);

        $nonAdminPermissions = $nonAdmin->allPermissions();
        $this->assertInstanceOf(Collection::class, $nonAdminPermissions);
        $this->assertGreaterThan(0, $nonAdminPermissions->count());
    }
}
