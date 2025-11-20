<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\json;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get today's pending tasks
        $todayTasks = Task::where('user_id', $user->id)
            ->whereDate('due_date', today())
            ->whereNull('completed_at')
            ->with(['category', 'goal'])
            ->orderBy('position')
            ->get();
        
        // Get active goals
        $activeGoals = Goal::where('user_id', $user->id)
            ->with(['tasks' => function ($query) {
                $query->whereNull('completed_at');
            }])
            ->latest()
            ->take(5)
            ->get();
        
        // Get recent completed tasks
        $recentCompletedTasks = Task::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->with(['category', 'goal'])
            ->latest('completed_at')
            ->take(5)
            ->get();
        
        // Get overdue tasks
        $overdueTasks = Task::where('user_id', $user->id)
            ->where('due_date', '<', today())
            ->whereNull('completed_at')
            ->with(['category', 'goal'])
            ->orderBy('due_date')
            ->get();

        return view('dashboard', compact(
            'todayTasks',
            'activeGoals',
            'recentCompletedTasks',
            'overdueTasks'
        ));
    }
}
