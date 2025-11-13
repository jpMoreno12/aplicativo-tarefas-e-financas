<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Task::where('user_id', $user->id)
            ->with(['category', 'goal', 'subtasks']);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by goal
        if ($request->filled('goal_id')) {
            $query->where('goal_id', $request->goal_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->whereNotNull('completed_at');
            } elseif ($request->status === 'pending') {
                $query->whereNull('completed_at');
            }
        }

        // Only show parent tasks (not subtasks)
        $query->whereNull('parent_id');

        $tasks = $query->orderBy('position')->paginate(15);
        
        $categories = Category::where('user_id', $user->id)->get();
        $goals = Goal::where('user_id', $user->id)->get();

        return view('tasks.index', compact('tasks', 'categories', 'goals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)->get();
        $goals = Goal::where('user_id', $user->id)->get();
        $parentTasks = Task::where('user_id', $user->id)
            ->whereNull('parent_id')
            ->whereNull('completed_at')
            ->get();

        return view('tasks.create', compact('categories', 'goals', 'parentTasks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'goal_id' => 'nullable|exists:goals,id',
            'parent_id' => 'nullable|exists:tasks,id',
            'due_date' => 'nullable|date',
            'reward' => 'nullable|string|max:255',
            'priority' => 'nullable|in:muito_facil,facil,medio,dificil,muito_dificil',
        ]);

        $user = Auth::user();
        
        // Get the next position
        $maxPosition = Task::where('user_id', $user->id)->max('position') ?? 0;

        Task::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'category_id' => $request->category_id,
            'goal_id' => $request->goal_id,
            'parent_id' => $request->parent_id,
            'due_date' => $request->due_date,
            'reward' => $request->reward,
            'priority' => $request->priority ?? 'medio',
            'position' => $maxPosition + 1,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        $task->load(['category', 'goal', 'subtasks.category', 'subtasks.goal']);
        
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        
        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)->get();
        $goals = Goal::where('user_id', $user->id)->get();
        $parentTasks = Task::where('user_id', $user->id)
            ->whereNull('parent_id')
            ->where('id', '!=', $task->id)
            ->whereNull('completed_at')
            ->get();

        return view('tasks.edit', compact('task', 'categories', 'goals', 'parentTasks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'goal_id' => 'nullable|exists:goals,id',
            'parent_id' => 'nullable|exists:tasks,id',
            'due_date' => 'nullable|date',
            'reward' => 'nullable|string|max:255',
            'priority' => 'nullable|in:muito_facil,facil,medio,dificil,muito_dificil',
        ]);

        $task->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'goal_id' => $request->goal_id,
            'parent_id' => $request->parent_id,
            'due_date' => $request->due_date,
            'reward' => $request->reward,
            'priority' => $request->priority ?? 'medio',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tarefa excluída com sucesso!');
    }

    /**
     * Toggle task completion status.
     */
    public function toggleComplete(Task $task)
    {
        $this->authorize('update', $task);
        
        if ($task->isCompleted()) {
            $task->markAsPending();
            $message = 'Tarefa marcada como pendente!';
        } else {
            $task->markAsCompleted();
            $message = 'Tarefa concluída!';
        }

        return back()->with('success', $message);
    }
}
