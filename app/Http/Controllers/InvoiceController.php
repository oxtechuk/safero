<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Invoice::with(['customer', 'items.certificate'])->latest('invoice_date');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('payment_status', $status);
        }

        if ($customerId = $request->input('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        if ($from = $request->input('from_date')) {
            $query->whereDate('invoice_date', '>=', $from);
        }

        if ($to = $request->input('to_date')) {
            $query->whereDate('invoice_date', '<=', $to);
        }

        $invoices = $query->paginate(15)->withQueryString();

        $stats = [
            'total_invoiced' => (float) Invoice::sum('total_amount'),
            'total_paid' => (float) Invoice::sum('paid_amount'),
            'total_remaining' => (float) Invoice::sum('remaining_balance'),
            'count' => Invoice::count(),
            'paid_count' => Invoice::where('payment_status', 'paid')->count(),
            'partial_count' => Invoice::where('payment_status', 'partial')->count(),
            'unpaid_count' => Invoice::where('payment_status', 'unpaid')->count(),
        ];

        $customers = Customer::orderBy('name')->get();

        return view('invoices.index', compact('invoices', 'stats', 'customers'));
    }

    public function create(Request $request): View
    {
        $customers = Customer::with('certificates')->orderBy('name')->get();
        $nextNumber = Invoice::generateNextInvoiceNumber();
        $today = Carbon::today()->toDateString();
        $dueDate = Carbon::today()->addDays(15)->toDateString();

        $selectedCustomerId = $request->input('customer_id');
        $preselectedCertificates = [];

        if ($selectedCustomerId) {
            $preselectedCertificates = Certificate::where('customer_id', $selectedCustomerId)
                ->latest('thorough_examination_date')
                ->get();
        }

        return view('invoices.create', compact(
            'customers',
            'nextNumber',
            'today',
            'dueDate',
            'selectedCustomerId',
            'preselectedCertificates'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'terms' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.certificate_id' => ['nullable', 'exists:certificates,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($validated) {
            $invoice = new Invoice;
            $invoice->invoice_number = Invoice::generateNextInvoiceNumber();
            $invoice->customer_id = $validated['customer_id'];
            $invoice->invoice_date = $validated['invoice_date'];
            $invoice->due_date = $validated['due_date'] ?? null;
            $invoice->discount_type = $validated['discount_type'];
            $invoice->discount_value = $validated['discount_value'] ?? 0;
            $invoice->tax_rate = $validated['tax_rate'] ?? 0;
            $invoice->paid_amount = $validated['paid_amount'] ?? 0;
            $invoice->payment_method = $validated['payment_method'] ?? 'cash';
            $invoice->notes = $validated['notes'] ?? null;
            $invoice->terms = $validated['terms'] ?? null;
            $invoice->created_by = Auth::id();
            $invoice->save();

            $subtotal = 0;
            foreach ($validated['items'] as $itemData) {
                $qty = (int) ($itemData['quantity'] ?? 1);
                $unitPrice = (float) ($itemData['unit_price'] ?? 0);
                $totalPrice = $qty * $unitPrice;
                $subtotal += $totalPrice;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'certificate_id' => ! empty($itemData['certificate_id']) ? $itemData['certificate_id'] : null,
                    'description' => $itemData['description'],
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
            }

            $invoice->subtotal = $subtotal;
            $invoice->recalculateTotals();
            $invoice->save();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'تم إنشاء الفاتورة بنجاح برقم: '.$invoice->invoice_number);
        });
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer', 'items.certificate', 'creator']);

        $verifyUrl = route('invoices.show', $invoice);
        $qrCodeSvg = base64_encode(QrCode::format('svg')->size(130)->margin(0)->generate($verifyUrl));

        return view('invoices.show', compact('invoice', 'qrCodeSvg', 'verifyUrl'));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load(['customer.certificates', 'items.certificate']);
        $customers = Customer::orderBy('name')->get();
        $customerCertificates = Certificate::where('customer_id', $invoice->customer_id)->get();

        return view('invoices.edit', compact('invoice', 'customers', 'customerCertificates'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'terms' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.certificate_id' => ['nullable', 'exists:certificates,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($validated, $invoice) {
            $invoice->customer_id = $validated['customer_id'];
            $invoice->invoice_date = $validated['invoice_date'];
            $invoice->due_date = $validated['due_date'] ?? null;
            $invoice->discount_type = $validated['discount_type'];
            $invoice->discount_value = $validated['discount_value'] ?? 0;
            $invoice->tax_rate = $validated['tax_rate'] ?? 0;
            $invoice->paid_amount = $validated['paid_amount'] ?? 0;
            $invoice->payment_method = $validated['payment_method'] ?? 'cash';
            $invoice->notes = $validated['notes'] ?? null;
            $invoice->terms = $validated['terms'] ?? null;

            // Remove existing items and recreate
            $invoice->items()->delete();

            $subtotal = 0;
            foreach ($validated['items'] as $itemData) {
                $qty = (int) ($itemData['quantity'] ?? 1);
                $unitPrice = (float) ($itemData['unit_price'] ?? 0);
                $totalPrice = $qty * $unitPrice;
                $subtotal += $totalPrice;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'certificate_id' => ! empty($itemData['certificate_id']) ? $itemData['certificate_id'] : null,
                    'description' => $itemData['description'],
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
            }

            $invoice->subtotal = $subtotal;
            $invoice->recalculateTotals();
            $invoice->save();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'تم تحديث بيانات الفاتورة بنجاح: '.$invoice->invoice_number);
        });
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $num = $invoice->invoice_number;
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'تم حذف الفاتورة: '.$num);
    }

    /**
     * AJAX endpoint to fetch certificates of a specific customer.
     */
    public function customerCertificates(Customer $customer): JsonResponse
    {
        $certificates = $customer->certificates()
            ->latest('thorough_examination_date')
            ->get(['id', 'certificate_number', 'equipment_description', 'equipment_type', 'swl', 'thorough_examination_date']);

        return response()->json([
            'success' => true,
            'certificates' => $certificates,
        ]);
    }
}
