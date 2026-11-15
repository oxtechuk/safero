<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\InspectionTask;
use App\Models\User;
use App\Services\GoogleSheetsSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InspectionTaskTest extends TestCase
{
    use RefreshDatabase;

    protected User $inspector;

    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inspector = User::factory()->create([
            'name' => 'Eng. Tarek Inspector',
            'email' => 'tarek@safero.com',
            'role' => 'inspector',
            'is_active' => true,
        ]);

        $this->customer = Customer::factory()->create([
            'name' => 'Alexandria Port Logistics Co',
            'address' => 'Alexandria Port Gate 27',
        ]);
    }

    public function test_authenticated_user_can_view_tasks_index_kanban_and_list(): void
    {
        InspectionTask::create([
            'task_number' => 'TSK-2026-0001',
            'title_ar' => 'فحص ونش هيدروليكي 50 طن',
            'title_en' => '50T Mobile Crane Periodic Examination',
            'customer_id' => $this->customer->id,
            'equipment_type' => 'crane',
            'scheduled_date' => now()->toDateString(),
            'status' => 'scheduled',
            'priority' => 'normal',
        ]);

        $response = $this->actingAs($this->inspector)->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertSee('TSK-2026-0001');
        $response->assertSee('Alexandria Port Logistics Co');
        $response->assertSee('مهام الفحص الميداني وأوامر الشغل');
    }

    public function test_user_can_create_new_inspection_task_with_default_checklist(): void
    {
        $taskData = [
            'title_ar' => 'فحص ونش كاتو 35 طن بموقع الدخيلة',
            'title_en' => '35T Kato Crane Thorough Examination',
            'customer_id' => $this->customer->id,
            'equipment_type' => 'crane',
            'equipment_identifier' => 'KAT-35-9081',
            'equipment_description' => 'Kato Hydraulic Mobile Crane 35T SWL',
            'assigned_inspector_id' => $this->inspector->id,
            'scheduled_date' => now()->toDateString(),
            'due_date' => now()->addDays(2)->toDateString(),
            'priority' => 'high',
            'examination_location' => 'ساحة الدخيلة رصيف 14',
            'notes' => 'يجب التأكد من معايرة وايرات الرفع الرئيسية.',
        ];

        $response = $this->actingAs($this->inspector)
            ->post(route('tasks.store'), $taskData);

        $this->assertDatabaseHas('inspection_tasks', [
            'equipment_identifier' => 'KAT-35-9081',
            'customer_id' => $this->customer->id,
            'status' => 'scheduled',
        ]);

        $task = InspectionTask::where('equipment_identifier', 'KAT-35-9081')->firstOrFail();
        $response->assertRedirect(route('tasks.edit', $task));

        // Default 28-point crane checklist should be auto-populated
        $this->assertNotEmpty($task->checklist_items);
        $this->assertCount(28, $task->checklist_items);
        $this->assertNotNull($task->synced_to_sheets_at);
    }

    public function test_user_can_update_task_checklist_items_and_status(): void
    {
        $task = InspectionTask::create([
            'task_number' => 'TSK-2026-0002',
            'title_ar' => 'فحص مهمات رفع وسلاسل',
            'title_en' => 'Lifting Accessories Slings Inspection',
            'customer_id' => $this->customer->id,
            'equipment_type' => 'accessory',
            'scheduled_date' => now()->toDateString(),
            'status' => 'scheduled',
            'priority' => 'normal',
            'checklist_items' => InspectionTask::defaultChecklist('accessory'),
        ]);

        $updateData = [
            'title_ar' => $task->title_ar,
            'customer_id' => $this->customer->id,
            'equipment_type' => 'accessory',
            'scheduled_date' => now()->toDateString(),
            'status' => 'completed',
            'priority' => 'normal',
            'checklist' => [
                1 => ['status' => 'pass', 'notes' => 'حبال سليمة خالية من الأسلاك المقطوعة'],
                2 => ['status' => 'fail', 'notes' => 'تآكل في عروة التثبيت يجب استبدالها'],
            ],
        ];

        $response = $this->actingAs($this->inspector)
            ->put(route('tasks.update', $task), $updateData);

        $response->assertRedirect(route('tasks.show', $task));

        $task->refresh();
        $this->assertEquals('completed', $task->status);
        $summary = $task->checklistSummary();
        $this->assertGreaterThan(0, $summary['fail']);
    }

    public function test_task_can_be_converted_to_official_certificate(): void
    {
        $task = InspectionTask::create([
            'task_number' => 'TSK-2026-0003',
            'title_ar' => 'فحص ونش برجى 60 طن',
            'title_en' => '60T Tower Crane Examination',
            'customer_id' => $this->customer->id,
            'equipment_type' => 'crane',
            'equipment_identifier' => 'TOW-60-2026',
            'examination_location' => 'موقع كليوباترا الإسكندرية',
            'scheduled_date' => now()->toDateString(),
            'status' => 'in_progress',
            'priority' => 'urgent',
        ]);

        $response = $this->actingAs($this->inspector)
            ->get(route('tasks.convert', $task));

        $response->assertRedirect();
        $this->assertStringContainsString('certificates/create', $response->headers->get('Location'));
    }

    public function test_google_sheets_sync_service_processes_task_safely(): void
    {
        $task = InspectionTask::create([
            'task_number' => 'TSK-2026-0004',
            'title_ar' => 'مهمة فحص دورية للمزامنة',
            'title_en' => 'Sync Test Task',
            'customer_id' => $this->customer->id,
            'equipment_type' => 'general',
            'scheduled_date' => now()->toDateString(),
            'status' => 'scheduled',
            'priority' => 'normal',
        ]);

        $result = GoogleSheetsSyncService::syncTask($task);

        $this->assertTrue($result['success']);
        $task->refresh();
        $this->assertNotNull($task->synced_to_sheets_at);
    }

    public function test_sync_artisan_command_runs_successfully(): void
    {
        InspectionTask::create([
            'task_number' => 'TSK-2026-0005',
            'title_ar' => 'فحص دوري لمهمة عبر أمر أرتيزان',
            'title_en' => 'Artisan Command Sync Task',
            'customer_id' => $this->customer->id,
            'equipment_type' => 'crane',
            'scheduled_date' => now()->toDateString(),
            'status' => 'scheduled',
            'priority' => 'normal',
        ]);

        $this->artisan('tasks:sync-google-sheets --all')
            ->expectsOutputToContain('Total 1 tasks processed')
            ->assertExitCode(0);
    }

    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertRedirect(route('login'));
    }
}
