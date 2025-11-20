<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display the calendar view.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get selected month/year or default to current
        $selectedMonth = $request->get('month', now()->month);
        $selectedYear = $request->get('year', now()->year);
        
        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // Get tasks with due dates in the selected month
        $tasks = Task::where('user_id', $user->id)
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->with(['category', 'goal'])
            ->orderBy('due_date')
            ->get();
        
        // Get expenses in the selected month
        $expenses = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
        
        // Group tasks by date
        $tasksByDate = $tasks->groupBy(function ($task) {
            return $task->due_date->format('Y-m-d');
        });
        
        // Group expenses by date
        $expensesByDate = $expenses->groupBy(function ($expense) {
            return $expense->date->format('Y-m-d');
        });
        
        // Generate calendar data
        $calendarData = $this->generateCalendarData($startDate, $endDate, $tasksByDate, $expensesByDate);
        
        // Navigation data
        $prevMonth = $startDate->copy()->subMonth();
        $nextMonth = $startDate->copy()->addMonth();
        
        return view('calendar.index', compact(
            'calendarData',
            'selectedMonth',
            'selectedYear',
            'startDate',
            'prevMonth',
            'nextMonth',
            'tasks',
            'expenses'
        ));
    }

    /**
     * Generate calendar data for the view.
     */
    private function generateCalendarData($startDate, $endDate, $tasksByDate, $expensesByDate = null)
    {
        $calendar = [];
        $current = $startDate->copy()->startOfWeek(Carbon::SUNDAY);
        
        // Generate 6 weeks of calendar data
        for ($week = 0; $week < 6; $week++) {
            $weekData = [];
            
            for ($day = 0; $day < 7; $day++) {
                $date = $current->copy();
                $dateKey = $date->format('Y-m-d');
                
                $weekData[] = [
                    'date' => $date,
                    'day' => $date->day,
                    'is_current_month' => $date->month === $startDate->month,
                    'is_today' => $date->isToday(),
                    'tasks' => $tasksByDate->get($dateKey, collect()),
                    'expenses' => $expensesByDate ? $expensesByDate->get($dateKey, collect()) : collect(),
                ];
                
                $current->addDay();
            }
            
            $calendar[] = $weekData;
            
            // If we've passed the end of the month and filled at least 4 weeks, break
            if ($week >= 3 && $current->month !== $startDate->month) {
                break;
            }
        }
        
        return $calendar;
    }
}
