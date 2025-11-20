<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FinanceController extends Controller
{
    /**
     * Display the finance dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get selected month/year or default to current
        $selectedMonth = $request->get('month', now()->month);
        $selectedYear = $request->get('year', now()->year);
        
        // Get expenses for the selected month
        $expenses = Expense::where('user_id', $user->id)
            ->whereMonth('date', $selectedMonth)
            ->whereYear('date', $selectedYear)
            ->orderBy('date', 'desc')
            ->get();
        
        // Calculate totals
        $totalExpenses = $expenses->sum('amount');
        $monthlyIncome = $user->monthly_income ?? 0;
        $balance = $monthlyIncome - $totalExpenses;
        
        // Get expenses by category for chart
        $expensesByCategory = $expenses->groupBy('category')
            ->map(function ($categoryExpenses) {
                return [
                    'category' => $categoryExpenses->first()->category ?? 'Sem categoria',
                    'total' => $categoryExpenses->sum('amount'),
                    'count' => $categoryExpenses->count(),
                ];
            })->values();
        
        // Get recent months for navigation
        $recentMonths = collect();
        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths($i);
            $recentMonths->push([
                'month' => $date->month,
                'year' => $date->year,
                'name' => $date->format('F Y'),
                'short_name' => $date->format('M/Y'),
            ]);
        }

        return view('finances.index', compact(
            'expenses',
            'totalExpenses',
            'monthlyIncome',
            'balance',
            'expensesByCategory',
            'selectedMonth',
            'selectedYear',
            'recentMonths'
        ));
    }

    /**
     * Store a new expense.
     */
    public function storeExpense(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        Expense::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'amount' => $request->amount,
            'category' => $request->category,
            'date' => $request->date,
        ]);

        return back()->with('success', 'Despesa adicionada com sucesso!');
    }

    /**
     * Update the user's monthly income.
     */
    public function updateIncome(Request $request)
    {
        $request->validate([
            'monthly_income' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        
        $user->update([
            'monthly_income' => $request->monthly_income,
        ]);

        return back()->with('success', 'Renda mensal atualizada com sucesso!');
    }

    /**
     * Delete an expense.
     */
    public function destroyExpense(Expense $expense)
    {
        $this->authorize('delete', $expense);
        
        $expense->delete();

        return back()->with('success', 'Despesa excluída com sucesso!');
    }
}
