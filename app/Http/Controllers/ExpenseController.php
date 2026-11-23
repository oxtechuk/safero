<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->input('period', 'all');
        $category = $request->input('category');
        $search = $request->input('search');

        $now = Carbon::now();
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

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

        $query = Expense::with('creator');

        if ($fromDate) {
            $query->whereDate('expense_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('expense_date', '<=', $toDate);
        }
        if ($category) {
            $query->where('category', $category);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('expense_number', 'like', "%{$search}%")
                    ->orWhere('paid_to', 'like', "%{$search}%")
                    ->orWhere('reference_no', 'like', "%{$search}%");
            });
        }

        $expenses = $query->latest('expense_date')->paginate(15)->withQueryString();

        // Stats calculation
        $allTimeQuery = Expense::query();
        $totalExpenses = (float) $allTimeQuery->sum('amount');
        $thisMonthExpenses = (float) Expense::whereMonth('expense_date', $now->month)
            ->whereYear('expense_date', $now->year)
            ->sum('amount');
        $expensesCount = $allTimeQuery->count();

        $categories = Expense::categories();

        return view('expenses.index', compact(
            'expenses',
            'period',
            'category',
            'search',
            'totalExpenses',
            'thisMonthExpenses',
            'expensesCount',
            'categories'
        ));
    }

    public function create(): View
    {
        $nextNumber = Expense::generateNextNumber();
        $categories = Expense::categories();
        $today = Carbon::today()->toDateString();

        return view('expenses.create', compact('nextNumber', 'categories', 'today'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'expense_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank_transfer,cheque'],
            'paid_to' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['expense_number'] = Expense::generateNextNumber($validated['expense_date']);
        $validated['created_by'] = Auth::id();

        $expense = Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', "تم تسجيل المصروف بنجاح برقم: {$expense->expense_number}");
    }

    public function edit(Expense $expense): View
    {
        $categories = Expense::categories();

        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'expense_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank_transfer,cheque'],
            'paid_to' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', "تم تحديث بيانات المصروف {$expense->expense_number} بنجاح.");
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $number = $expense->expense_number;
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', "تم حذف سند المصروف {$number} بنجاح.");
    }
}
