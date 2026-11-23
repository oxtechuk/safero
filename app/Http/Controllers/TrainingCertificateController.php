<?php

namespace App\Http\Controllers;

use App\Models\Trainee;
use App\Models\TrainingCertificate;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $mode = $request->input('mode', 'single');

        $today = Carbon::today();
        $expiryDate = Carbon::today()->addYears(2)->subDay();

        $defaultSerial = TrainingCertificate::generateNextSerial($today->toDateString());
        $instructors = TrainingCertificate::defaultInstructors();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('training-certificates.create', compact(
            'trainees',
            'selectedTraineeId',
            'today',
            'expiryDate',
            'defaultSerial',
            'instructors',
            'users',
            'mode'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $mode = $request->input('mode', 'single');

        // BATCH ISSUANCE MODE (إصدار عدة شهادات دفعة واحدة)
        if ($mode === 'batch') {
            $validated = $request->validate([
                'training_scope' => ['required', 'string', 'max:255'],
                'program_title' => ['required', 'string', 'max:255'],
                'hours' => ['required', 'integer', 'min:1', 'max:1000'],
                'issue_date' => ['required', 'date'],
                'expiry_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
                'instructor_name' => ['required', 'string', 'max:255'],
                'instructor_id' => ['nullable', 'exists:users,id'],
                'training_manager_name' => ['required', 'string', 'max:255'],
                'accreditation_text' => ['nullable', 'string'],
                'status' => ['required', 'in:issued,draft,revoked'],
                'batch_trainee_ids' => ['nullable', 'array'],
                'batch_trainee_ids.*' => ['exists:trainees,id'],
                'raw_trainee_names' => ['nullable', 'string'],
            ]);

            // Collect all trainee targets
            $traineesList = collect();

            // 1. From selected existing trainees
            if (! empty($validated['batch_trainee_ids'])) {
                $existing = Trainee::whereIn('id', $validated['batch_trainee_ids'])->get();
                foreach ($existing as $t) {
                    $traineesList->push([
                        'id' => $t->id,
                        'name' => $t->name,
                    ]);
                }
            }

            // 2. From raw text names (one per line)
            if (! empty($validated['raw_trainee_names'])) {
                $lines = explode("\n", str_replace("\r", '', $validated['raw_trainee_names']));
                foreach ($lines as $line) {
                    $cleanName = trim($line);
                    if ($cleanName !== '') {
                        $traineesList->push([
                            'id' => null,
                            'name' => $cleanName,
                        ]);
                    }
                }
            }

            if ($traineesList->isEmpty()) {
                return back()->withInput()->withErrors([
                    'batch_trainee_ids' => 'يرجى اختيار متدرب واحد على الأقل أو كتابة أسماء المتدربين لإصدار الشهادات الجماعية.',
                ]);
            }

            $createdCount = 0;
            $firstSerial = null;
            $lastSerial = null;

            DB::transaction(function () use ($traineesList, $validated, &$createdCount, &$firstSerial, &$lastSerial) {
                foreach ($traineesList as $target) {
                    $serial = TrainingCertificate::generateNextSerial($validated['issue_date']);

                    if (! $firstSerial) {
                        $firstSerial = $serial;
                    }
                    $lastSerial = $serial;

                    TrainingCertificate::create([
                        'uuid' => (string) Str::uuid(),
                        'certificate_serial' => $serial,
                        'trainee_id' => $target['id'],
                        'trainee_name' => $target['name'],
                        'training_scope' => $validated['training_scope'],
                        'program_title' => $validated['program_title'],
                        'hours' => $validated['hours'],
                        'issue_date' => $validated['issue_date'],
                        'expiry_date' => $validated['expiry_date'] ?? null,
                        'instructor_name' => $validated['instructor_name'],
                        'instructor_id' => $validated['instructor_id'] ?? null,
                        'training_manager_name' => $validated['training_manager_name'],
                        'accreditation_text' => $validated['accreditation_text'] ?? null,
                        'status' => $validated['status'],
                        'created_by' => Auth::id(),
                    ]);

                    $createdCount++;
                }
            });

            return redirect()->route('training-certificates.index')
                ->with('success', "تم بنجاح إصدار عدد {$createdCount} شهادات تدريب معتمدة من الرقم {$firstSerial} إلى {$lastSerial}.");
        }

        // SINGLE ISSUANCE MODE (شهادة فردية)
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
            'instructor_id' => ['nullable', 'exists:users,id'],
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
        $instructors = TrainingCertificate::defaultInstructors();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('training-certificates.edit', compact('trainingCertificate', 'trainees', 'instructors', 'users'));
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
            'instructor_id' => ['nullable', 'exists:users,id'],
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

        $resolveDataUri = function (?string $settingKey, string $defaultRelPath): array {
            $path = null;
            $configured = $settingKey ? setting($settingKey) : null;
            if ($configured) {
                $candidate = public_path(ltrim($configured, '/\\'));
                if (file_exists($candidate)) {
                    $path = $candidate;
                }
            }
            if (! $path) {
                $candidate = public_path(ltrim($defaultRelPath, '/\\'));
                if (file_exists($candidate)) {
                    $path = $candidate;
                }
            }

            if ($path && file_exists($path)) {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $mime = match ($ext) {
                    'svg' => 'image/svg+xml',
                    'png' => 'image/png',
                    'jpg', 'jpeg' => 'image/jpeg',
                    'webp' => 'image/webp',
                    default => 'image/jpeg',
                };
                $content = file_get_contents($path);
                $b64 = base64_encode($content);

                return [
                    'uri' => 'data:'.$mime.';base64,'.$b64,
                    'base64' => $b64,
                    'mime' => $mime,
                ];
            }

            return ['uri' => '', 'base64' => '', 'mime' => ''];
        };

        $logoInfo = $resolveDataUri('logo_path', 'branding/safero_logo.jpg');
        $stampInfo = $resolveDataUri('stamp_path', 'branding/stamp.svg');
        $engSignInfo = $resolveDataUri('engineer_signature_path', 'branding/eng_signature.svg');
        $dirSignInfo = $resolveDataUri('director_signature_path', 'branding/director_signature.svg');

        $pdf = Pdf::loadView('training-certificates.pdf', [
            'certificate' => $trainingCertificate,
            'qrCodeSvg' => $qrCodeSvg,
            'verifyUrl' => $verifyUrl,
            'logoDataUri' => $logoInfo['uri'],
            'logoBase64' => $logoInfo['base64'],
            'stampDataUri' => $stampInfo['uri'],
            'stampBase64' => $stampInfo['base64'],
            'stampMime' => $stampInfo['mime'],
            'engSignDataUri' => $engSignInfo['uri'],
            'engSignBase64' => $engSignInfo['base64'],
            'engSignMime' => $engSignInfo['mime'],
            'dirSignDataUri' => $dirSignInfo['uri'],
            'dirSignBase64' => $dirSignInfo['base64'],
            'dirSignMime' => $dirSignInfo['mime'],
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
