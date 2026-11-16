<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Customer;
use App\Models\InspectionTask;
use App\Services\GoogleSheetsSyncService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateController extends Controller
{
    public function index(Request $request): View
    {
        $query = Certificate::with('customer');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                    ->orWhere('order_no', 'like', "%{$search}%")
                    ->orWhere('equipment_description', 'like', "%{$search}%")
                    ->orWhere('reference_standard', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $certificates = $query->latest('id')->paginate(12)->withQueryString();

        return view('certificates.index', compact('certificates'));
    }

    public function create(Request $request): View
    {
        $customers = Customer::orderBy('name')->get();
        $selectedCustomerId = $request->input('customer_id');

        $type = $request->query('type', 'crane');
        if (! in_array($type, ['crane', 'accessory', 'general'])) {
            $type = 'crane';
        }

        $today = Carbon::today();
        $nextDue = Carbon::today()->addMonths(6)->subDay();

        $defaultCertNo = Certificate::generateNextNumber($today->toDateString());
        $defaultCraneChecklist = Certificate::defaultCraneChecklist();

        // Check if loading from Studio Template
        $activeTemplate = null;
        if ($templateId = $request->input('template_id')) {
            $activeTemplate = CertificateTemplate::find($templateId);
            if ($activeTemplate) {
                $type = $activeTemplate->equipment_type;
                $months = $activeTemplate->validity_months ?: 6;
                $nextDue = Carbon::today()->addMonths($months)->subDay();
            }
        }

        // Check if renewing from previous certificate
        $renewCertificate = null;
        if ($renewId = $request->input('renew_from')) {
            $renewCertificate = Certificate::with('customer')->find($renewId);
            if ($renewCertificate) {
                $type = $renewCertificate->equipment_type;
                $selectedCustomerId = $renewCertificate->customer_id;
                $nextDue = Carbon::today()->addMonths(6)->subDay();
            }
        }

        $templates = CertificateTemplate::active()->orderBy('name')->get();

        return view('certificates.create', compact(
            'customers',
            'selectedCustomerId',
            'today',
            'nextDue',
            'defaultCertNo',
            'defaultCraneChecklist',
            'type',
            'templates',
            'activeTemplate',
            'renewCertificate'
        ));
    }

    public function expiring(Request $request): View
    {
        $today = Carbon::today();
        $query = Certificate::with('customer');

        // Filter by timeframe
        $timeframe = $request->input('timeframe', '30');
        if ($timeframe === 'expired') {
            $query->whereDate('next_examination_due_date', '<', $today);
        } elseif ($timeframe === '7') {
            $query->whereDate('next_examination_due_date', '>=', $today)
                ->whereDate('next_examination_due_date', '<=', $today->copy()->addDays(7));
        } elseif ($timeframe === '15') {
            $query->whereDate('next_examination_due_date', '>=', $today)
                ->whereDate('next_examination_due_date', '<=', $today->copy()->addDays(15));
        } elseif ($timeframe === '30') {
            $query->whereDate('next_examination_due_date', '>=', $today)
                ->whereDate('next_examination_due_date', '<=', $today->copy()->addDays(30));
        } elseif ($timeframe === '60') {
            $query->whereDate('next_examination_due_date', '>=', $today)
                ->whereDate('next_examination_due_date', '<=', $today->copy()->addDays(60));
        } elseif ($timeframe === 'all_expiring') {
            $query->whereDate('next_examination_due_date', '<=', $today->copy()->addDays(60));
        }

        if ($customerId = $request->input('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        if ($type = $request->input('type')) {
            $query->where('equipment_type', $type);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                    ->orWhere('equipment_description', 'like', "%{$search}%")
                    ->orWhere('order_no', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $certificates = $query->orderBy('next_examination_due_date', 'asc')
            ->paginate(15)
            ->withQueryString();

        $customers = Customer::orderBy('name')->get();

        $stats = [
            'expired' => Certificate::whereDate('next_examination_due_date', '<', $today)->count(),
            'due_7' => Certificate::whereDate('next_examination_due_date', '>=', $today)
                ->whereDate('next_examination_due_date', '<=', $today->copy()->addDays(7))->count(),
            'due_30' => Certificate::whereDate('next_examination_due_date', '>=', $today)
                ->whereDate('next_examination_due_date', '<=', $today->copy()->addDays(30))->count(),
            'total_active' => Certificate::where('status', 'issued')->count(),
        ];

        return view('certificates.expiring', compact('certificates', 'customers', 'stats', 'timeframe', 'today'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'certificate_number' => ['required', 'string', 'max:50', 'unique:certificates,certificate_number'],
            'order_no' => ['nullable', 'string', 'max:50'],
            'customer_id' => ['required', 'exists:customers,id'],
            'examination_address' => ['required', 'string', 'max:255'],
            'thorough_examination_date' => ['required', 'date'],
            'next_examination_due_date' => ['required', 'date', 'after_or_equal:thorough_examination_date'],
            'reference_standard' => ['required', 'string', 'max:100'],
            'equipment_type' => ['nullable', 'string', 'in:crane,accessory,general'],
            'equipment_description' => ['required', 'string', 'max:500'],
            'equipment_id_range' => ['nullable', 'string', 'max:100'],
            'date_of_manufacture' => ['nullable', 'string', 'max:50'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'swl' => ['required', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:1'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'string', 'max:255'],
            'chassis_no' => ['nullable', 'string', 'max:255'],
            'main_wire' => ['nullable', 'string', 'max:255'],
            'hook_block' => ['nullable', 'string', 'max:255'],
            'load_test_cert_no' => ['nullable', 'string', 'max:255'],
            'date_of_load_test' => ['nullable', 'date'],
            'last_examination_date' => ['nullable', 'date'],
            'test_point' => ['nullable', 'string', 'max:255'],
            'safety_factor' => ['nullable', 'string', 'max:100'],
            'length' => ['nullable', 'string', 'max:100'],
            'checklist_items' => ['nullable', 'array'],
            'is_first_examination_after_installation' => ['boolean'],
            'was_examination_within_6_months' => ['boolean'],
            'has_equipment_installed_correctly' => ['boolean'],
            'was_examination_within_12_months' => ['boolean'],
            'in_accordance_with_scheme' => ['boolean'],
            'after_exceptional_circumstances' => ['boolean'],
            'defect_identification' => ['nullable', 'string'],
            'is_defect_danger_to_process' => ['boolean'],
            'repair_particulars' => ['nullable', 'string'],
            'tests_carried_out' => ['required', 'string'],
            'is_safe_to_operate' => ['boolean'],
            'issuer_name' => ['required', 'string', 'max:255'],
            'authenticator_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:issued,draft,revoked'],
        ]);

        $validated['uuid'] = (string) Str::uuid();
        $validated['created_by'] = Auth::id();
        $validated['defect_identification'] = $validated['defect_identification'] ?? 'NONE';
        $validated['repair_particulars'] = $validated['repair_particulars'] ?? 'NONE';
        $validated['equipment_type'] = $validated['equipment_type'] ?? 'general';

        if ($validated['equipment_type'] === 'crane' && empty($validated['checklist_items'])) {
            $validated['checklist_items'] = Certificate::defaultCraneChecklist();
        }

        $certificate = Certificate::create($validated);

        if ($request->filled('template_id')) {
            CertificateTemplate::where('id', $request->input('template_id'))->increment('usage_count');
        }

        if ($request->filled('task_id')) {
            $task = InspectionTask::find($request->input('task_id'));
            if ($task) {
                $task->update([
                    'certificate_id' => $certificate->id,
                    'status' => 'completed',
                ]);
                GoogleSheetsSyncService::syncTask($task);
            }
        }

        return redirect()->route('certificates.show', $certificate)
            ->with('success', 'تم إنشاء شهادة الفحص بنجاح برقم: '.$certificate->certificate_number);
    }

    public function show(Certificate $certificate): View
    {
        $certificate->load('customer');
        $verifyUrl = $certificate->verification_url;

        $qrCodeSvg = base64_encode(QrCode::format('svg')->size(130)->margin(1)->generate($verifyUrl));

        return view('certificates.show', compact('certificate', 'qrCodeSvg', 'verifyUrl'));
    }

    public function edit(Certificate $certificate): View
    {
        $customers = Customer::orderBy('name')->get();
        $craneChecklist = $certificate->checklist_items ?: Certificate::defaultCraneChecklist();

        return view('certificates.edit', compact('certificate', 'customers', 'craneChecklist'));
    }

    public function update(Request $request, Certificate $certificate): RedirectResponse
    {
        $validated = $request->validate([
            'certificate_number' => ['required', 'string', 'max:50', 'unique:certificates,certificate_number,'.$certificate->id],
            'order_no' => ['nullable', 'string', 'max:50'],
            'customer_id' => ['required', 'exists:customers,id'],
            'examination_address' => ['required', 'string', 'max:255'],
            'thorough_examination_date' => ['required', 'date'],
            'next_examination_due_date' => ['required', 'date'],
            'reference_standard' => ['required', 'string', 'max:100'],
            'equipment_type' => ['nullable', 'string', 'in:crane,accessory,general'],
            'equipment_description' => ['required', 'string', 'max:500'],
            'equipment_id_range' => ['nullable', 'string', 'max:100'],
            'date_of_manufacture' => ['nullable', 'string', 'max:50'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'swl' => ['required', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:1'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'string', 'max:255'],
            'chassis_no' => ['nullable', 'string', 'max:255'],
            'main_wire' => ['nullable', 'string', 'max:255'],
            'hook_block' => ['nullable', 'string', 'max:255'],
            'load_test_cert_no' => ['nullable', 'string', 'max:255'],
            'date_of_load_test' => ['nullable', 'date'],
            'last_examination_date' => ['nullable', 'date'],
            'test_point' => ['nullable', 'string', 'max:255'],
            'safety_factor' => ['nullable', 'string', 'max:100'],
            'length' => ['nullable', 'string', 'max:100'],
            'checklist_items' => ['nullable', 'array'],
            'is_first_examination_after_installation' => ['boolean'],
            'was_examination_within_6_months' => ['boolean'],
            'has_equipment_installed_correctly' => ['boolean'],
            'was_examination_within_12_months' => ['boolean'],
            'in_accordance_with_scheme' => ['boolean'],
            'after_exceptional_circumstances' => ['boolean'],
            'defect_identification' => ['nullable', 'string'],
            'is_defect_danger_to_process' => ['boolean'],
            'repair_particulars' => ['nullable', 'string'],
            'tests_carried_out' => ['required', 'string'],
            'is_safe_to_operate' => ['boolean'],
            'issuer_name' => ['required', 'string', 'max:255'],
            'authenticator_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:issued,draft,revoked'],
        ]);

        if (($validated['equipment_type'] ?? $certificate->equipment_type) === 'crane' && empty($validated['checklist_items']) && empty($certificate->checklist_items)) {
            $validated['checklist_items'] = Certificate::defaultCraneChecklist();
        }

        $certificate->update($validated);

        return redirect()->route('certificates.show', $certificate)
            ->with('success', 'تم تحديث بيانات الشهادة بنجاح.');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $certificate->delete();

        return redirect()->route('certificates.index')
            ->with('success', 'تم حذف الشهادة بنجاح.');
    }

    public function pdf(Request $request, string|Certificate $certificate): Response
    {
        if (is_string($certificate)) {
            $raw = trim(urldecode($certificate));
            $variants = array_unique(array_filter([
                $raw,
                strtoupper($raw),
                str_replace('-', '/', $raw),
                str_replace('/', '-', $raw),
                str_starts_with(strtoupper($raw), 'SAF/') ? substr($raw, 4) : 'SAF/'.$raw,
                str_starts_with(strtoupper($raw), 'SAF-') ? substr($raw, 4) : 'SAF-'.$raw,
            ]));

            $found = is_numeric($raw)
                ? Certificate::where('id', (int) $raw)->orWhereIn('certificate_number', $variants)->first()
                : Certificate::whereIn('certificate_number', $variants)->orWhere('uuid', $raw)->first();

            if (! $found) {
                abort(404, 'Certificate not found.');
            }

            $certificate = $found;
        }

        $certificate->load('customer');
        $verifyUrl = $certificate->verification_url;
        $qrCodeSvg = base64_encode(QrCode::format('svg')->size(130)->margin(0)->generate($verifyUrl));

        $bgPath = file_exists(public_path('build/assets/cert-inspect.pdf.jpg'))
            ? public_path('build/assets/cert-inspect.pdf.jpg')
            : public_path('branding/cert_inspect_bg.jpg');
        $bgBase64 = file_exists($bgPath) ? base64_encode(file_get_contents($bgPath)) : '';

        $logoPath = public_path('branding/safero_logo.jpg');
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';

        $stampPath = public_path('branding/stamp.svg');
        $stampBase64 = file_exists($stampPath) ? base64_encode(file_get_contents($stampPath)) : '';

        $engSignPath = public_path('branding/eng_signature.svg');
        $engSignBase64 = file_exists($engSignPath) ? base64_encode(file_get_contents($engSignPath)) : '';

        $dirSignPath = public_path('branding/director_signature.svg');
        $dirSignBase64 = file_exists($dirSignPath) ? base64_encode(file_get_contents($dirSignPath)) : '';

        $pdf = Pdf::loadView('certificates.pdf', [
            'certificate' => $certificate,
            'qrCodeSvg' => $qrCodeSvg,
            'verifyUrl' => $verifyUrl,
            'bgBase64' => $bgBase64,
            'logoBase64' => $logoBase64,
            'stampBase64' => $stampBase64,
            'engSignBase64' => $engSignBase64,
            'dirSignBase64' => $dirSignBase64,
        ])->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $filename = 'Safero-Certificate-'.Str::slug($certificate->certificate_number).'.pdf';

        $isDownload = $request->boolean('download') || $request->has('dl');
        $response = $isDownload ? $pdf->download($filename) : $pdf->stream($filename);

        return $response
            ->header('X-Frame-Options', 'ALLOWALL')
            ->header('Content-Security-Policy', 'frame-ancestors *');
    }

    public function verify(string $uuid): View
    {
        $certificate = Certificate::with('customer')
            ->where('uuid', $uuid)
            ->firstOrFail();

        $verifyUrl = route('certificates.verify', ['uuid' => $certificate->uuid]);
        $qrCodeSvg = base64_encode(QrCode::format('svg')->size(130)->margin(1)->generate($verifyUrl));

        return view('certificates.verify', compact('certificate', 'qrCodeSvg'));
    }

    public function embed(Request $request, ?string $uuid = null): Response
    {
        $raw = trim((string) ($uuid ?: $request->query('cert') ?: $request->query('number') ?: $request->query('uuid')));
        if ($raw === '') {
            abort(404, 'Certificate identifier is required.');
        }

        $raw = urldecode($raw);

        $variants = array_unique(array_filter([
            $raw,
            strtoupper($raw),
            str_replace('-', '/', $raw),
            str_replace('/', '-', $raw),
            str_starts_with(strtoupper($raw), 'SAF/') ? substr($raw, 4) : 'SAF/'.$raw,
            str_starts_with(strtoupper($raw), 'SAF-') ? substr($raw, 4) : 'SAF-'.$raw,
        ]));

        $certificate = Certificate::with('customer')
            ->whereIn('certificate_number', $variants)
            ->orWhere('uuid', $raw)
            ->firstOrFail();

        $verifyUrl = $certificate->verification_url;
        $qrCodeSvg = base64_encode(QrCode::format('svg')->size(110)->margin(1)->generate($verifyUrl));

        return response()
            ->view('certificates.embed', compact('certificate', 'qrCodeSvg', 'verifyUrl'))
            ->header('X-Frame-Options', 'ALLOWALL')
            ->header('Content-Security-Policy', 'frame-ancestors *');
    }

    public function verifyLookup(Request $request): View
    {
        $search = $request->input('number');
        $certificate = null;
        $searched = false;

        if ($search) {
            $searched = true;
            $certificate = Certificate::with('customer')
                ->where('certificate_number', trim($search))
                ->orWhere('uuid', trim($search))
                ->first();
        }

        return view('certificates.verify-lookup', compact('certificate', 'searched', 'search'));
    }
}
