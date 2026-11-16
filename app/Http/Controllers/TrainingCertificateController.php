<?php

namespace App\Http\Controllers;

use App\Models\Trainee;
use App\Models\TrainingCertificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TrainingCertificateController extends Controller
{
    public function index(Request $request): View
    {
        $query = TrainingCertificate::with('trainee');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('certificate_serial', 'like', "%{$search}%")
                    ->orWhere('trainee_name', 'like', "%{$search}%")
                    ->orWhere('program_title', 'like', "%{$search}%")
                    ->orWhere('training_scope', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $certificates = $query->latest('id')->paginate(15)->withQueryString();

        return view('training-certificates.index', compact('certificates'));
    }

    public function create(Request $request): View
    {
        $trainees = Trainee::orderBy('name')->get();
        $selectedTraineeId = $request->input('trainee_id');

        $today = Carbon::today();
        $expiryDate = Carbon::today()->addYears(2)->subDay();

        $defaultSerial = TrainingCertificate::generateNextSerial($today->toDateString());

        return view('training-certificates.create', compact('trainees', 'selectedTraineeId', 'today', 'expiryDate', 'defaultSerial'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'trainee_id' => ['nullable', 'exists:trainees,id'],
            'trainee_name' => ['required', 'string', 'max:255'],
            'certificate_serial' => ['required', 'string', 'max:50', 'unique:training_certificates,certificate_serial'],
            'training_scope' => ['required', 'string', 'max:255'],
            'program_title' => ['required', 'string', 'max:255'],
            'hours' => ['required', 'integer', 'min:1', 'max:1000'],
            'issue_date' => ['required', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'instructor_name' => ['required', 'string', 'max:255'],
            'training_manager_name' => ['required', 'string', 'max:255'],
            'accreditation_text' => ['nullable', 'string'],
            'status' => ['required', 'in:issued,draft,revoked'],
        ]);

        // If trainee_id is provided but trainee_name is empty, get from model
        if (! empty($validated['trainee_id']) && empty($validated['trainee_name'])) {
            $trainee = Trainee::find($validated['trainee_id']);
            $validated['trainee_name'] = $trainee?->name;
        }

        $validated['uuid'] = (string) Str::uuid();
        $validated['created_by'] = Auth::id();

        $certificate = TrainingCertificate::create($validated);

        return redirect()->route('training-certificates.show', $certificate)
            ->with('success', 'تم إصدار شهادة التدريب بنجاح برقم: '.$certificate->certificate_serial);
    }

    public function show(TrainingCertificate $trainingCertificate): View
    {
        $trainingCertificate->load('trainee');
        $verifyUrl = $trainingCertificate->verification_url;
        $embedUrl = $trainingCertificate->embed_url;

        $qrCodeSvg = base64_encode(QrCode::format('svg')->size(140)->margin(1)->generate($verifyUrl));

        return view('training-certificates.show', compact('trainingCertificate', 'qrCodeSvg', 'verifyUrl', 'embedUrl'));
    }

    public function edit(TrainingCertificate $trainingCertificate): View
    {
        $trainees = Trainee::orderBy('name')->get();

        return view('training-certificates.edit', compact('trainingCertificate', 'trainees'));
    }

    public function update(Request $request, TrainingCertificate $trainingCertificate): RedirectResponse
    {
        $validated = $request->validate([
            'trainee_id' => ['nullable', 'exists:trainees,id'],
            'trainee_name' => ['required', 'string', 'max:255'],
            'certificate_serial' => ['required', 'string', 'max:50', 'unique:training_certificates,certificate_serial,'.$trainingCertificate->id],
            'training_scope' => ['required', 'string', 'max:255'],
            'program_title' => ['required', 'string', 'max:255'],
            'hours' => ['required', 'integer', 'min:1', 'max:1000'],
            'issue_date' => ['required', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'instructor_name' => ['required', 'string', 'max:255'],
            'training_manager_name' => ['required', 'string', 'max:255'],
            'accreditation_text' => ['nullable', 'string'],
            'status' => ['required', 'in:issued,draft,revoked'],
        ]);

        $trainingCertificate->update($validated);

        return redirect()->route('training-certificates.show', $trainingCertificate)
            ->with('success', 'تم تحديث بيانات شهادة التدريب بنجاح.');
    }

    public function destroy(TrainingCertificate $trainingCertificate): RedirectResponse
    {
        $trainingCertificate->delete();

        return redirect()->route('training-certificates.index')
            ->with('success', 'تم حذف شهادة التدريب بنجاح.');
    }

    public function pdf(Request $request, string|TrainingCertificate $trainingCertificate): Response
    {
        if (is_string($trainingCertificate)) {
            $raw = trim(urldecode($trainingCertificate));
            $variants = array_unique(array_filter([
                $raw,
                strtoupper($raw),
                str_replace('-', '/', $raw),
                str_replace('/', '-', $raw),
            ]));

            $found = is_numeric($raw)
                ? TrainingCertificate::where('id', (int) $raw)->orWhereIn('certificate_serial', $variants)->first()
                : TrainingCertificate::whereIn('certificate_serial', $variants)->orWhere('uuid', $raw)->first();

            if (! $found) {
                abort(404, 'Training certificate not found.');
            }

            $trainingCertificate = $found;
        }

        $trainingCertificate->load('trainee');
        $verifyUrl = $trainingCertificate->verification_url;
        $qrCodeSvg = base64_encode(QrCode::format('svg')->size(130)->margin(0)->generate($verifyUrl));

        $logoPath = public_path('branding/safero_logo.jpg');
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';

        $stampPath = public_path('branding/stamp.svg');
        $stampBase64 = file_exists($stampPath) ? base64_encode(file_get_contents($stampPath)) : '';

        $engSignPath = public_path('branding/eng_signature.svg');
        $engSignBase64 = file_exists($engSignPath) ? base64_encode(file_get_contents($engSignPath)) : '';

        $dirSignPath = public_path('branding/director_signature.svg');
        $dirSignBase64 = file_exists($dirSignPath) ? base64_encode(file_get_contents($dirSignPath)) : '';

        $pdf = Pdf::loadView('training-certificates.pdf', [
            'certificate' => $trainingCertificate,
            'qrCodeSvg' => $qrCodeSvg,
            'verifyUrl' => $verifyUrl,
            'logoBase64' => $logoBase64,
            'stampBase64' => $stampBase64,
            'engSignBase64' => $engSignBase64,
            'dirSignBase64' => $dirSignBase64,
        ])->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $filename = 'Safero-Training-Certificate-'.Str::slug($trainingCertificate->certificate_serial).'.pdf';

        $isDownload = $request->boolean('download') || $request->has('dl');
        $response = $isDownload ? $pdf->download($filename) : $pdf->stream($filename);

        return $response
            ->header('X-Frame-Options', 'ALLOWALL')
            ->header('Content-Security-Policy', 'frame-ancestors *');
    }

    public function verify(string $uuid): View
    {
        $certificate = TrainingCertificate::with('trainee')
            ->where('uuid', $uuid)
            ->orWhere('certificate_serial', $uuid)
            ->firstOrFail();

        $verifyUrl = $certificate->verification_url;
        $qrCodeSvg = base64_encode(QrCode::format('svg')->size(140)->margin(1)->generate($verifyUrl));

        return view('training-certificates.verify', compact('certificate', 'qrCodeSvg'));
    }

    public function embed(Request $request, ?string $uuid = null): Response
    {
        $raw = trim((string) ($uuid ?: $request->query('cert') ?: $request->query('serial') ?: $request->query('uuid')));
        if ($raw === '') {
            abort(404, 'Training certificate identifier is required.');
        }

        $raw = urldecode($raw);

        $variants = array_unique(array_filter([
            $raw,
            strtoupper($raw),
            str_replace('-', '/', $raw),
            str_replace('/', '-', $raw),
        ]));

        $certificate = TrainingCertificate::with('trainee')
            ->whereIn('certificate_serial', $variants)
            ->orWhere('uuid', $raw)
            ->firstOrFail();

        $verifyUrl = $certificate->verification_url;
        $qrCodeSvg = base64_encode(QrCode::format('svg')->size(120)->margin(1)->generate($verifyUrl));

        return response()
            ->view('training-certificates.embed', compact('certificate', 'qrCodeSvg', 'verifyUrl'))
            ->header('X-Frame-Options', 'ALLOWALL')
            ->header('Content-Security-Policy', 'frame-ancestors *');
    }
}
