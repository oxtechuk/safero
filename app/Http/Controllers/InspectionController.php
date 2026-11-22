<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Customer;
use App\Models\Inspection;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InspectionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Inspection::with(['customer', 'template', 'inspector', 'certificate'])->latest('inspection_date');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('inspection_number', 'like', "%{$search}%")
                    ->orWhere('equipment_name', 'like', "%{$search}%")
                    ->orWhere('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial_no', 'like', "%{$search}%")
                    ->orWhere('doc_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($customerId = $request->input('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        $inspections = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Inspection::count(),
            'draft' => Inspection::where('status', 'draft')->count(),
            'completed' => Inspection::where('status', 'completed')->count(),
            'certified' => Inspection::where('status', 'certified')->count(),
            'safe' => Inspection::where('overall_condition', 'safe')->count(),
            'unsafe' => Inspection::where('overall_condition', 'unsafe')->count(),
        ];

        $customers = Customer::orderBy('name')->get();

        return view('inspections.index', compact('inspections', 'stats', 'customers'));
    }

    /**
     * Step 1: Start Inspection Wizard (Select Client, Template, and Asset details).
     */
    public function start(Request $request): View
    {
        $customers = Customer::orderBy('name')->get();
        $templates = CertificateTemplate::active()->orderBy('code')->orderBy('name')->get();
        $nextNumber = Inspection::generateNextInspectionNumber();
        $today = Carbon::today()->toDateString();
        $user = Auth::user();

        $selectedTemplate = null;
        if ($templateId = $request->input('template_id')) {
            $selectedTemplate = CertificateTemplate::find($templateId);
        }

        $selectedCustomerId = $request->input('customer_id');

        return view('inspections.start', compact(
            'customers',
            'templates',
            'nextNumber',
            'today',
            'user',
            'selectedTemplate',
            'selectedCustomerId'
        ));
    }

    /**
     * Store new inspection session and forward immediately to mobile checklist.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'certificate_template_id' => ['nullable', 'exists:certificate_templates,id'],
            'equipment_name' => ['required', 'string', 'max:190'],
            'equipment_type' => ['nullable', 'string', 'max:100'],
            'reference_standard' => ['nullable', 'string', 'max:190'],
            'doc_number' => ['nullable', 'string', 'max:50'],
            'asset_tag' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'manufacturer' => ['nullable', 'string', 'max:190'],
            'model_no' => ['nullable', 'string', 'max:190'],
            'serial_no' => ['nullable', 'string', 'max:190'],
            'year_of_manufacture' => ['nullable', 'string', 'max:50'],
            'swl' => ['nullable', 'string', 'max:100'],
            'inspection_type' => ['required', 'string', 'in:initial,periodic,post_repair,post_incident'],
            'inspection_date' => ['required', 'date'],
            'inspector_name' => ['required', 'string', 'max:190'],
            'inspector_cert_ref' => ['nullable', 'string', 'max:190'],
            'notes' => ['nullable', 'string'],
        ]);

        $template = null;
        if (! empty($validated['certificate_template_id'])) {
            $template = CertificateTemplate::find($validated['certificate_template_id']);
        }

        // Calculate next inspection date based on validity months
        $validityMonths = $template ? ($template->validity_months ?: 12) : 12;
        $nextInspectionDate = Carbon::parse($validated['inspection_date'])->addMonths($validityMonths)->subDay()->toDateString();

        // Copy checklist items from template
        $checklistItems = [];
        if ($template && ! empty($template->checklist_items)) {
            $checklistItems = $template->checklist_items;
        } else {
            $checklistItems = Certificate::defaultCraneChecklist();
        }

        // Initialize all checklist items status to pending/pass
        $formattedChecklist = [];
        foreach ($checklistItems as $idx => $item) {
            $formattedChecklist[] = [
                'id' => $idx + 1,
                'no' => $item['no'] ?? ($idx + 1),
                'clause_ref' => $item['clause_ref'] ?? '',
                'item' => $item['item'] ?? $item['question'] ?? 'نقطة فحص '.($idx + 1),
                'acceptance_criteria' => $item['acceptance_criteria'] ?? '',
                'rejection_criteria' => $item['rejection_criteria'] ?? '',
                'status' => 'pass', // Default to pass, inspector can tap to fail or NA
                'remarks' => '',
            ];
        }

        $inspection = Inspection::create([
            'inspection_number' => Inspection::generateNextInspectionNumber(),
            'customer_id' => $validated['customer_id'],
            'certificate_template_id' => $template?->id,
            'equipment_type' => ($validated['equipment_type'] ?? null) ?: ($template?->equipment_type ?? 'crane'),
            'equipment_name' => $validated['equipment_name'],
            'reference_standard' => ($validated['reference_standard'] ?? null) ?: $template?->reference_standard,
            'doc_number' => ($validated['doc_number'] ?? null) ?: $template?->code,
            'asset_tag' => $validated['asset_tag'] ?? null,
            'location' => $validated['location'] ?? null,
            'manufacturer' => ($validated['manufacturer'] ?? null) ?: $template?->manufacturer,
            'model_no' => ($validated['model_no'] ?? null) ?: $template?->model,
            'serial_no' => $validated['serial_no'] ?? null,
            'year_of_manufacture' => $validated['year_of_manufacture'] ?? null,
            'swl' => ($validated['swl'] ?? null) ?: $template?->swl,
            'inspection_type' => $validated['inspection_type'],
            'inspection_date' => $validated['inspection_date'],
            'next_inspection_date' => $nextInspectionDate,
            'inspector_name' => $validated['inspector_name'],
            'inspector_cert_ref' => $validated['inspector_cert_ref'] ?? null,
            'inspector_id' => Auth::id(),
            'checklist_results' => $formattedChecklist,
            'overall_condition' => 'safe',
            'status' => 'draft',
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($template) {
            $template->increment('usage_count');
        }

        return redirect()->route('inspections.inspect', $inspection)
            ->with('success', 'تم بدء جلسة الفحص بنجاح برقم '.$inspection->inspection_number.'. يمكنك الآن فحص بنود الشيك ليست.');
    }

    /**
     * Mobile-Friendly Field Checklist Screen.
     */
    public function inspect(Inspection $inspection): View
    {
        $inspection->load(['customer', 'template', 'inspector', 'certificate']);
        $summary = $inspection->checklistSummary();

        return view('inspections.inspect', compact('inspection', 'summary'));
    }

    /**
     * Update checklist answers and overall declaration.
     */
    public function updateChecklist(Request $request, Inspection $inspection): RedirectResponse|JsonResponse
    {
        if (! $request->filled('overall_condition')) {
            $request->merge(['overall_condition' => $inspection->overall_condition ?: 'safe']);
        }

        $validated = $request->validate([
            'checklist' => ['nullable', 'array'],
            'overall_condition' => ['required', 'in:safe,unsafe'],
            'defects_identified' => ['nullable', 'string'],
            'corrective_actions' => ['nullable', 'string'],
            'repeat_examination_required' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:draft,completed'],
            'notes' => ['nullable', 'string'],
        ]);

        $currentItems = $inspection->checklist_results ?? [];

        if (! empty($validated['checklist'])) {
            foreach ($validated['checklist'] as $id => $data) {
                foreach ($currentItems as $index => $item) {
                    if ((int) ($item['id'] ?? 0) === (int) $id) {
                        $currentItems[$index]['status'] = $data['status'] ?? 'pass';
                        $currentItems[$index]['remarks'] = $data['remarks'] ?? '';
                        break;
                    }
                }
            }
        }

        $inspection->checklist_results = $currentItems;
        $inspection->overall_condition = $validated['overall_condition'];
        $inspection->defects_identified = $validated['defects_identified'] ?? null;
        $inspection->corrective_actions = $validated['corrective_actions'] ?? null;
        $inspection->repeat_examination_required = (bool) ($validated['repeat_examination_required'] ?? false);
        $inspection->notes = $validated['notes'] ?? $inspection->notes;

        if (! empty($validated['status'])) {
            $inspection->status = $validated['status'];
        }

        $inspection->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الفحص بنجاح',
                'summary' => $inspection->checklistSummary(),
            ]);
        }

        // If requested to directly convert to certificate
        if ($request->has('convert_to_certificate')) {
            return $this->convertToCertificate($request, $inspection);
        }

        return redirect()->route('inspections.inspect', $inspection)
            ->with('success', 'تم حفظ نتائج الفحص الميداني بنجاح.');
    }

    /**
     * Stage 2: Convert Inspection to Official Certificate of Thorough Examination.
     */
    public function convertToCertificate(Request $request, Inspection $inspection): RedirectResponse
    {
        $inspection->load(['customer', 'template']);

        // Check if certificate already generated
        if ($inspection->certificate_id && $inspection->certificate) {
            return redirect()->route('certificates.show', $inspection->certificate)
                ->with('info', 'تم إنشاء شهادة الفحص بالفعل لهذه المعاينة برقم '.$inspection->certificate->certificate_number);
        }

        $examDate = $inspection->inspection_date ? Carbon::parse($inspection->inspection_date) : Carbon::today();
        $nextDue = $inspection->next_inspection_date ? Carbon::parse($inspection->next_inspection_date) : $examDate->copy()->addMonths(12)->subDay();

        $certNumber = Certificate::generateNextNumber($examDate->toDateString());

        // Prepare checklist for certificate format
        $certChecklist = [];
        foreach (($inspection->checklist_results ?? []) as $item) {
            $clauseRef = $item['clause_ref'] ?? '';
            $itemStatus = $item['status'] ?? 'pass';
            $remarks = $item['remarks'] ?? '';

            $certChecklist[] = [
                'no' => $item['no'] ?? 1,
                'category' => ! empty($clauseRef) ? 'Standard Clause '.$clauseRef : 'Inspection Point',
                'item' => $item['item'] ?? '',
                'question' => $item['item'] ?? '',
                'status' => ucfirst($itemStatus),
                'check' => $itemStatus === 'pass' ? 'In Order' : 'Defect',
                'remarks' => ! empty($remarks) ? $remarks : ($itemStatus === 'pass' ? 'In Order / Accepted' : 'Defect Reported'),
                'comment' => '',
            ];
        }

        $certificate = Certificate::create([
            'uuid' => (string) Str::uuid(),
            'certificate_number' => $certNumber,
            'order_no' => $inspection->inspection_number,
            'customer_id' => $inspection->customer_id,
            'examination_address' => $inspection->location ?: ($inspection->customer->address ?: 'موقع العميل'),
            'thorough_examination_date' => $examDate->toDateString(),
            'next_examination_due_date' => $nextDue->toDateString(),
            'reference_standard' => $inspection->reference_standard ?: 'BS 7121 / LOLER 1998 / ISO 17020',
            'equipment_type' => $inspection->equipment_type ?: 'crane',
            'equipment_description' => $inspection->equipment_name ?: 'Lifting Equipment',
            'equipment_id_range' => $inspection->asset_tag,
            'swl' => $inspection->swl ?: 'Per Specifications',
            'quantity' => 1,
            'manufacturer' => $inspection->manufacturer,
            'model' => $inspection->model_no,
            'chassis_no' => $inspection->serial_no,
            'capacity' => $inspection->swl,
            'date_of_manufacture' => $inspection->year_of_manufacture,
            'checklist_items' => $certChecklist,
            'is_first_examination_after_installation' => ($inspection->inspection_type === 'initial'),
            'was_examination_within_6_months' => ($inspection->template && $inspection->template->validity_months <= 6),
            'was_examination_within_12_months' => (! $inspection->template || $inspection->template->validity_months > 6),
            'has_equipment_installed_correctly' => true,
            'in_accordance_with_scheme' => true,
            'after_exceptional_circumstances' => in_array($inspection->inspection_type, ['post_repair', 'post_incident']),
            'defect_identification' => $inspection->defects_identified ?: 'None observed at time of inspection.',
            'is_defect_danger_to_process' => ($inspection->overall_condition === 'unsafe'),
            'repair_particulars' => $inspection->corrective_actions ?: 'None required.',
            'tests_carried_out' => 'Thorough Visual Examination, Operational & Safety Limit Verification in accordance with '.$inspection->reference_standard,
            'is_safe_to_operate' => ($inspection->overall_condition === 'safe'),
            'issuer_name' => $inspection->inspector_name ?: (Auth::user()?->name ?? 'Eng. Technical Inspector'),
            'authenticator_name' => setting('authenticator_name', 'Eng. Technical Manager (ISO 17020)'),
            'status' => 'issued',
            'created_by' => Auth::id(),
        ]);

        // Link certificate back to inspection
        $inspection->certificate_id = $certificate->id;
        $inspection->status = 'certified';
        $inspection->save();

        return redirect()->route('certificates.show', $certificate)
            ->with('success', '🎉 تم إنشاء شهادة الفحص المعتمدة بنجاح برقم: '.$certificate->certificate_number.' من الفحص الميداني: '.$inspection->inspection_number);
    }

    public function show(Inspection $inspection): View
    {
        $inspection->load(['customer', 'template', 'inspector', 'certificate']);
        $summary = $inspection->checklistSummary();

        return view('inspections.show', compact('inspection', 'summary'));
    }

    /**
     * Printable Equipment Inspection Sticker Tag.
     */
    public function sticker(Inspection $inspection): View
    {
        $inspection->load(['customer', 'template', 'inspector', 'certificate']);

        $verifyUrl = $inspection->certificate
            ? $inspection->certificate->verification_url
            : route('inspections.show', $inspection);

        $qrCodeSvg = base64_encode(QrCode::format('svg')->size(220)->margin(0)->generate($verifyUrl));

        return view('inspections.sticker', compact('inspection', 'qrCodeSvg', 'verifyUrl'));
    }

    public function destroy(Inspection $inspection): RedirectResponse
    {
        $num = $inspection->inspection_number;
        $inspection->delete();

        return redirect()->route('inspections.index')
            ->with('success', 'تم حذف تقرير الفحص الميداني: '.$num);
    }
}
