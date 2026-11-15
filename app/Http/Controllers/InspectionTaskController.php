<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InspectionTask;
use App\Models\User;
use App\Services\GoogleSheetsSyncService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InspectionTaskController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $inspectorFilter = $request->input('inspector_id');
        $typeFilter = $request->input('equipment_type');
        $currentView = $request->input('view', 'kanban');

        $query = InspectionTask::with(['customer', 'inspector', 'certificate'])->latest('scheduled_date');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('task_number', 'like', "%{$search}%")
                    ->orWhere('title_ar', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('equipment_identifier', 'like', "%{$search}%")
                    ->orWhere('equipment_description', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('inspector', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($inspectorFilter) {
            $query->where('assigned_inspector_id', $inspectorFilter);
        }

        if ($typeFilter) {
            $query->where('equipment_type', $typeFilter);
        }

        $tasks = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => InspectionTask::count(),
            'scheduled' => InspectionTask::where('status', 'scheduled')->count(),
            'in_progress' => InspectionTask::where('status', 'in_progress')->count(),
            'completed' => InspectionTask::where('status', 'completed')->count(),
            'certified' => InspectionTask::whereNotNull('certificate_id')->count(),
            'synced' => InspectionTask::whereNotNull('synced_to_sheets_at')->count(),
        ];

        $inspectors = User::where('is_active', true)->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'stats', 'inspectors', 'search', 'statusFilter', 'inspectorFilter', 'typeFilter', 'currentView'));
    }

    public function create(Request $request): View
    {
        $customers = Customer::orderBy('name')->get();
        $inspectors = User::where('is_active', true)->orderBy('name')->get();
        $nextNumber = InspectionTask::generateNextTaskNumber();
        $selectedType = $request->input('type', 'crane');

        return view('tasks.create', compact('customers', 'inspectors', 'nextNumber', 'selectedType'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'customer_id' => ['required', 'exists:customers,id'],
            'equipment_type' => ['required', 'string', 'in:crane,accessory,general,environmental'],
            'equipment_description' => ['nullable', 'string', 'max:500'],
            'equipment_identifier' => ['nullable', 'string', 'max:100'],
            'assigned_inspector_id' => ['nullable', 'exists:users,id'],
            'scheduled_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:scheduled_date'],
            'priority' => ['required', 'string', 'in:low,normal,high,urgent'],
            'examination_location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);

        $task = InspectionTask::create([
            'task_number' => InspectionTask::generateNextTaskNumber(),
            'title_ar' => $validated['title_ar'],
            'title_en' => ($validated['title_en'] ?? null) ?: $validated['title_ar'],
            'customer_id' => $customer->id,
            'equipment_type' => $validated['equipment_type'],
            'equipment_description' => $validated['equipment_description'] ?? null,
            'equipment_identifier' => $validated['equipment_identifier'] ?? null,
            'assigned_inspector_id' => $validated['assigned_inspector_id'] ?? null,
            'scheduled_date' => $validated['scheduled_date'],
            'due_date' => $validated['due_date'] ?? Carbon::parse($validated['scheduled_date'])->addDays(3),
            'status' => 'scheduled',
            'priority' => $validated['priority'],
            'examination_location' => $validated['examination_location'] ?: ($customer->address ?: 'ساحة العميل'),
            'checklist_items' => InspectionTask::defaultChecklist($validated['equipment_type']),
            'notes' => $validated['notes'] ?? null,
        ]);

        // Auto-sync to Google Sheets in background or locally
        GoogleSheetsSyncService::syncTask($task);

        return redirect()->route('tasks.edit', $task)
            ->with('success', 'تم إنشاء وتكليف مهمة الفحص بنجاح برقم: '.$task->task_number.'، يمكنك الآن البدء بتعبئة شيك ليست الفحص الميداني.');
    }

    public function show(InspectionTask $task): View
    {
        $task->load(['customer', 'inspector', 'certificate']);
        $summary = $task->checklistSummary();

        return view('tasks.show', compact('task', 'summary'));
    }

    public function edit(InspectionTask $task): View
    {
        $task->load(['customer', 'inspector', 'certificate']);
        $customers = Customer::orderBy('name')->get();
        $inspectors = User::where('is_active', true)->orderBy('name')->get();
        $summary = $task->checklistSummary();

        if (empty($task->checklist_items)) {
            $task->checklist_items = InspectionTask::defaultChecklist($task->equipment_type);
            $task->saveQuietly();
        }

        return view('tasks.edit', compact('task', 'customers', 'inspectors', 'summary'));
    }

    public function update(Request $request, InspectionTask $task): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'customer_id' => ['required', 'exists:customers,id'],
            'equipment_type' => ['required', 'string', 'in:crane,accessory,general,environmental'],
            'equipment_description' => ['nullable', 'string', 'max:500'],
            'equipment_identifier' => ['nullable', 'string', 'max:100'],
            'assigned_inspector_id' => ['nullable', 'exists:users,id'],
            'scheduled_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', 'string', 'in:draft,scheduled,in_progress,completed,certified,cancelled'],
            'priority' => ['required', 'string', 'in:low,normal,high,urgent'],
            'examination_location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'checklist' => ['nullable', 'array'],
        ]);

        // Parse updated checklist items if submitted
        if (! empty($validated['checklist'])) {
            $currentChecklist = $task->checklist_items ?? InspectionTask::defaultChecklist($task->equipment_type);

            foreach ($validated['checklist'] as $id => $itemData) {
                foreach ($currentChecklist as $index => $item) {
                    if ((int) ($item['id'] ?? 0) === (int) $id) {
                        $currentChecklist[$index]['status'] = $itemData['status'] ?? 'pass';
                        $currentChecklist[$index]['notes'] = $itemData['notes'] ?? '';
                        break;
                    }
                }
            }
            $task->checklist_items = $currentChecklist;
        }

        $task->fill([
            'title_ar' => $validated['title_ar'],
            'title_en' => ($validated['title_en'] ?? null) ?: $validated['title_ar'],
            'customer_id' => $validated['customer_id'],
            'equipment_type' => $validated['equipment_type'],
            'equipment_description' => $validated['equipment_description'] ?? null,
            'equipment_identifier' => $validated['equipment_identifier'] ?? null,
            'assigned_inspector_id' => $validated['assigned_inspector_id'] ?? null,
            'scheduled_date' => $validated['scheduled_date'],
            'due_date' => $validated['due_date'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'examination_location' => $validated['examination_location'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        $task->save();

        // Sync changes with Google Sheets
        GoogleSheetsSyncService::syncTask($task);

        // If inspector clicked "Save and Convert to Certificate"
        if ($request->has('convert_to_certificate')) {
            return $this->convertToCertificate($task);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'تم حفظ وتحديث شيك ليست وبيانات مهمة الفحص بنجاح: '.$task->task_number);
    }

    public function destroy(InspectionTask $task): RedirectResponse
    {
        $taskNumber = $task->task_number;
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'تم حذف أمر شغل ومهمة الفحص: '.$taskNumber);
    }

    public function syncNow(InspectionTask $task): RedirectResponse
    {
        $result = GoogleSheetsSyncService::syncTask($task);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('errors', collect([$result['message']]));
    }

    public function syncAll(): RedirectResponse
    {
        $result = GoogleSheetsSyncService::syncAllTasks(true);

        return redirect()->back()
            ->with('success', "تمت مزامنة جميع مهام الفحص مع جداول Google Sheets بنجاح ({$result['synced']} مهمة محدثة).");
    }

    public function convertToCertificate(InspectionTask $task): RedirectResponse
    {
        $params = [
            'type' => in_array($task->equipment_type, ['crane', 'accessory', 'general']) ? $task->equipment_type : 'crane',
            'customer_id' => $task->customer_id,
            'equipment_description' => $task->equipment_description ?: $task->title_ar,
            'equipment_id_range' => $task->equipment_identifier,
            'examination_address' => $task->examination_location,
            'thorough_examination_date' => $task->scheduled_date->format('Y-m-d'),
            'task_id' => $task->id,
        ];

        return redirect()->route('certificates.create', $params)
            ->with('success', 'تم تجهيز بيانات الشهادة الرسمية تلقائياً من مهمة الفحص رقم: '.$task->task_number);
    }
}
