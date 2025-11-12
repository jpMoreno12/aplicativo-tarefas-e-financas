<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $goals = Goal::where('user_id', $user->id)
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
                $query->whereNotNull('completed_at');
            }])
            ->latest()
            ->paginate(15);

        return view('goals.index', compact('goals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('goals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Goal::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('goals.index')->with('success', 'Meta criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Goal $goal)
    {
        $this->authorize('view', $goal);
        
        $goal->load(['tasks' => function ($query) {
            $query->with(['category'])->orderBy('position');
        }]);

        return view('goals.show', compact('goal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Goal $goal)
    {
        $this->authorize('update', $goal);
        
        return view('goals.edit', compact('goal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $goal->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('goals.index')->with('success', 'Meta atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);
        
        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Meta excluída com sucesso!');
    }
}
