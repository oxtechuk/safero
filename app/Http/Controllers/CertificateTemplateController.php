<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CertificateTemplateController extends Controller
{
    public function index(Request $request): View
    {
        $query = CertificateTemplate::with('creator');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('equipment_description', 'like', "%{$search}%")
                    ->orWhere('reference_standard', 'like', "%{$search}%")
                    ->orWhere('swl', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('equipment_type', $type);
        }

        $templates = $query->latest('id')->paginate(12)->withQueryString();

        $stats = [
            'total' => CertificateTemplate::count(),
            'crane' => CertificateTemplate::where('equipment_type', 'crane')->count(),
            'accessory' => CertificateTemplate::where('equipment_type', 'accessory')->count(),
            'general' => CertificateTemplate::where('equipment_type', 'general')->count(),
        ];

        return view('certificate-templates.index', compact('templates', 'stats'));
    }

    public function create(Request $request): View
    {
        $type = $request->input('type', 'crane');

        $defaultChecklist = match ($type) {
            'crane' => Certificate::defaultCraneChecklist(),
            'accessory' => CertificateTemplate::defaultAccessoryChecklist(),
            default => CertificateTemplate::defaultGeneralChecklist(),
        };

        $defaultSpecs = [
            ['name' => 'أداة الفحص / جهاز القياس', 'type' => 'tool', 'value' => ''],
            ['name' => 'المواصفة المرجعية الإلزامية', 'type' => 'standard', 'value' => ''],
            ['name' => 'طريقة الاختبار والتحقق', 'type' => 'method', 'value' => ''],
        ];

        return view('certificate-templates.create', compact('type', 'defaultChecklist', 'defaultSpecs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTemplate($request);
        $validated['created_by'] = Auth::id();
        $validated['checklist_items'] = $this->formatChecklistItems($request->input('checklist_items', []));
        $validated['custom_specifications'] = $this->formatCustomSpecs($request->input('custom_specifications', []));

        $template = CertificateTemplate::create($validated);

        return redirect()->route('certificate-templates.index')
            ->with('success', 'تم حفظ قالب الفحص الجديد بنجاح في الاستوديو: '.$template->name);
    }

    public function show(CertificateTemplate $certificateTemplate): View
    {
        $certificateTemplate->load('creator');
        $template = $certificateTemplate;

        return view('certificate-templates.show', compact('certificateTemplate', 'template'));
    }

    public function edit(CertificateTemplate $certificateTemplate): View
    {
        $template = $certificateTemplate;

        return view('certificate-templates.edit', compact('certificateTemplate', 'template'));
    }

    public function update(Request $request, CertificateTemplate $certificateTemplate): RedirectResponse
    {
        $validated = $this->validateTemplate($request, $certificateTemplate->id);
        $validated['checklist_items'] = $this->formatChecklistItems($request->input('checklist_items', []));
        $validated['custom_specifications'] = $this->formatCustomSpecs($request->input('custom_specifications', []));

        $certificateTemplate->update($validated);

        return redirect()->route('certificate-templates.index')
            ->with('success', 'تم تحديث قالب الفحص بنجاح: '.$certificateTemplate->name);
    }

    public function destroy(CertificateTemplate $certificateTemplate): RedirectResponse
    {
        $name = $certificateTemplate->name;
        $certificateTemplate->delete();

        return redirect()->route('certificate-templates.index')
            ->with('success', 'تم حذف القالب بنجاح: '.$name);
    }

    public function duplicate(CertificateTemplate $certificateTemplate): RedirectResponse
    {
        $newTemplate = $certificateTemplate->replicate();
        $newTemplate->name = $certificateTemplate->name.' (نسخة مطابقة)';
        $newTemplate->code = $certificateTemplate->code ? $certificateTemplate->code.'-COPY' : null;
        $newTemplate->is_default = false;
        $newTemplate->created_by = Auth::id();
        $newTemplate->save();

        return redirect()->route('certificate-templates.edit', $newTemplate)
            ->with('success', 'تم استنساخ القالب بنجاح. يمكنك الآن تعديل بيانات النسخة الجديدة وحفظها.');
    }

    public function apiData(CertificateTemplate $certificateTemplate): JsonResponse
    {
        return response()->json([
            'success' => true,
            'template' => $certificateTemplate,
        ]);
    }

    protected function validateTemplate(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'code' => ['nullable', 'string', 'max:50', 'unique:certificate_templates,code,'.$id],
            'equipment_type' => ['required', 'in:crane,accessory,general'],
            'equipment_description' => ['nullable', 'string'],
            'reference_standard' => ['nullable', 'string', 'max:190'],
            'swl' => ['nullable', 'string', 'max:100'],
            'manufacturer' => ['nullable', 'string', 'max:190'],
            'model' => ['nullable', 'string', 'max:190'],
            'capacity' => ['nullable', 'string', 'max:100'],
            'dimensions' => ['nullable', 'string', 'max:190'],
            'safety_factor' => ['nullable', 'string', 'max:100'],
            'length' => ['nullable', 'string', 'max:100'],
            'validity_months' => ['required', 'integer', 'min:1', 'max:60'],
            'tests_carried_out' => ['nullable', 'string'],
            'defect_identification' => ['nullable', 'string'],
            'repair_particulars' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'is_default' => ['boolean'],
        ]);
    }

    protected function formatChecklistItems($items): array
    {
        if (is_string($items)) {
            $decoded = json_decode($items, true);

            return is_array($decoded) ? $decoded : [];
        }

        if (! is_array($items)) {
            return [];
        }

        $formatted = [];
        $index = 1;
        foreach ($items as $item) {
            if (empty($item['question']) && empty($item['item'])) {
                continue;
            }
            $formatted[] = [
                'no' => $index++,
                'category' => $item['category'] ?? 'General',
                'item' => $item['item'] ?? $item['question'] ?? '',
                'question' => $item['question'] ?? $item['item'] ?? '',
                'status' => $item['status'] ?? 'Pass',
                'check' => $item['check'] ?? 'In Order',
                'remarks' => $item['remarks'] ?? 'Good condition',
                'comment' => $item['comment'] ?? '',
            ];
        }

        return $formatted;
    }

    protected function formatCustomSpecs($specs): array
    {
        if (is_string($specs)) {
            $decoded = json_decode($specs, true);

            return is_array($decoded) ? $decoded : [];
        }

        if (! is_array($specs)) {
            return [];
        }

        $formatted = [];
        foreach ($specs as $spec) {
            if (empty($spec['name']) && empty($spec['value'])) {
                continue;
            }
            $formatted[] = [
                'name' => trim($spec['name'] ?? ''),
                'type' => trim($spec['type'] ?? 'tool'),
                'value' => trim($spec['value'] ?? ''),
            ];
        }

        return $formatted;
    }
}
