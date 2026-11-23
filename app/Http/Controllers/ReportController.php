<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Inspection;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->input('period', 'all');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Determine date filter
        $now = Carbon::now();
        if ($period === 'this_month') {
            $fromDate = $now->copy()->startOfMonth()->toDateString();
            $toDate = $now->copy()->endOfMonth()->toDateString();
        } elseif ($period === 'last_month') {
            $fromDate = $now->copy()->subMonth()->startOfMonth()->toDateString();
            $toDate = $now->copy()->subMonth()->endOfMonth()->toDateString();
        } elseif ($period === 'this_year') {
            $fromDate = $now->copy()->startOfYear()->toDateString();
            $toDate = $now->copy()->endOfYear()->toDateString();
        }

        // 1. Invoices & Profits Query
        $invoiceQuery = Invoice::query();
        if ($fromDate) {
            $invoiceQuery->whereDate('invoice_date', '>=', $fromDate);
        }
        if ($toDate) {
            $invoiceQuery->whereDate('invoice_date', '<=', $toDate);
        }

        $totalInvoiced = (float) $invoiceQuery->sum('total_amount');
        $totalPaid = (float) $invoiceQuery->sum('paid_amount');
        $totalRemaining = (float) $invoiceQuery->sum('remaining_balance');
        $totalDiscount = (float) $invoiceQuery->sum('discount_amount');
        $invoicesCount = $invoiceQuery->count();

        $collectionRate = $totalInvoiced > 0 ? (int) round(($totalPaid / $totalInvoiced) * 100) : 0;

        // 1.1 Expenses & Real Profit Calculation (حساب المصاريف والأرباح الحقيقية)
        $expenseQuery = Expense::query();
        if ($fromDate) {
            $expenseQuery->whereDate('expense_date', '>=', $fromDate);
        }
        if ($toDate) {
            $expenseQuery->whereDate('expense_date', '<=', $toDate);
        }

        $totalExpenses = (float) $expenseQuery->sum('amount');
        $expensesCount = $expenseQuery->count();

        // Real Net Profit = Collected Cash - Operational Expenses
        $realNetProfit = $totalPaid - $totalExpenses;
        $projectedNetProfit = $totalInvoiced - $totalExpenses;
        $profitMargin = $totalPaid > 0 ? round(($realNetProfit / $totalPaid) * 100, 1) : 0;

        $expensesByCategory = DB::table('expenses')
            ->select('category', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->when($fromDate, fn ($q) => $q->whereDate('expense_date', '>=', $fromDate))
            ->when($toDate, fn ($q) => $q->whereDate('expense_date', '<=', $toDate))
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        // 2. Inspections & Certificates Query
        $inspectionQuery = Inspection::query();
        $certificateQuery = Certificate::query();

        if ($fromDate) {
            $inspectionQuery->whereDate('inspection_date', '>=', $fromDate);
            $certificateQuery->whereDate('thorough_examination_date', '>=', $fromDate);
        }
        if ($toDate) {
            $inspectionQuery->whereDate('inspection_date', '<=', $toDate);
            $certificateQuery->whereDate('thorough_examination_date', '<=', $toDate);
        }

        $totalInspections = $inspectionQuery->count();
        $safeInspections = (clone $inspectionQuery)->where('overall_condition', 'safe')->count();
        $unsafeInspections = (clone $inspectionQuery)->where('overall_condition', 'unsafe')->count();
        $totalCertificates = $certificateQuery->count();

        $avgCertPrice = $totalCertificates > 0 ? round($totalInvoiced / $totalCertificates, 2) : 0;

        // 3. Customer Profitability Breakdown
        $customerReports = Customer::withCount(['certificates', 'inspections', 'invoices'])
            ->with(['invoices' => function ($q) use ($fromDate, $toDate) {
                if ($fromDate) {
                    $q->whereDate('invoice_date', '>=', $fromDate);
                }
                if ($toDate) {
                    $q->whereDate('invoice_date', '<=', $toDate);
                }
            }])
            ->get()
            ->map(function ($cust) {
                $invSum = $cust->invoices->sum('total_amount');
                $paidSum = $cust->invoices->sum('paid_amount');
                $remainingSum = $cust->invoices->sum('remaining_balance');

                return [
                    'id' => $cust->id,
                    'name' => $cust->name,
                    'phone' => $cust->phone,
                    'certificates_count' => $cust->certificates_count,
                    'inspections_count' => $cust->inspections_count,
                    'invoices_count' => $cust->invoices_count,
                    'total_invoiced' => $invSum,
                    'total_paid' => $paidSum,
                    'total_remaining' => $remainingSum,
                    'status' => $remainingSum <= 0 && $invSum > 0 ? 'paid' : ($paidSum > 0 ? 'partial' : ($invSum > 0 ? 'unpaid' : 'none')),
                ];
            })
            ->sortByDesc('total_invoiced')
            ->values();

        // 4. Equipment Breakdown
        $equipmentStats = DB::table('inspections')
            ->select('equipment_type', DB::raw('count(*) as count'), DB::raw('sum(case when overall_condition = "safe" then 1 else 0 end) as safe_count'), DB::raw('sum(case when overall_condition = "unsafe" then 1 else 0 end) as unsafe_count'))
            ->when($fromDate, fn ($q) => $q->whereDate('inspection_date', '>=', $fromDate))
            ->when($toDate, fn ($q) => $q->whereDate('inspection_date', '<=', $toDate))
            ->groupBy('equipment_type')
            ->orderByDesc('count')
            ->get();

        // 5. Recent 6 Months Trend
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            $monthLabel = $monthStart->translatedFormat('F Y');

            $monthInvoiced = (float) Invoice::whereBetween('invoice_date', [$monthStart, $monthEnd])->sum('total_amount');
            $monthPaid = (float) Invoice::whereBetween('invoice_date', [$monthStart, $monthEnd])->sum('paid_amount');
            $monthExpenses = (float) Expense::whereBetween('expense_date', [$monthStart, $monthEnd])->sum('amount');
            $monthInspections = Inspection::whereBetween('inspection_date', [$monthStart, $monthEnd])->count();

            $monthlyTrends[] = [
                'month' => $monthLabel,
                'invoiced' => $monthInvoiced,
                'paid' => $monthPaid,
                'expenses' => $monthExpenses,
                'real_profit' => $monthPaid - $monthExpenses,
                'inspections' => $monthInspections,
            ];
        }

        return view('reports.index', compact(
            'period',
            'fromDate',
            'toDate',
            'totalInvoiced',
            'totalPaid',
            'totalRemaining',
            'totalDiscount',
            'totalExpenses',
            'expensesCount',
            'realNetProfit',
            'projectedNetProfit',
            'profitMargin',
            'expensesByCategory',
            'invoicesCount',
            'collectionRate',
            'totalInspections',
            'safeInspections',
            'unsafeInspections',
            'totalCertificates',
            'avgCertPrice',
            'customerReports',
            'equipmentStats',
            'monthlyTrends'
        ));
    }
}
