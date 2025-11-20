@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Today's Tasks -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Tarefas de Hoje</h5>
                <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus"></i> Nova Tarefa
                </a>
            </div>
            <div class="card-body">
                @forelse($todayTasks as $task)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <div class="flex-grow-1">
                            <span class="{{ $task->isCompleted() ? 'task-completed' : '' }}">
                                {{ $task->title }}
                            </span>
                            <div class="mt-1">
                                <span class="badge {{ $task->getPriorityBadgeClass() }}">
                                    <i class="{{ $task->getPriorityIcon() }}"></i> {{ $task->getPriorityLabel() }}
                                </span>
                                @if($task->category)
                                    <span class="badge bg-secondary">{{ $task->category->name }}</span>
                                @endif
                                @if($task->goal)
                                    <span class="badge bg-info">{{ $task->goal->title }}</span>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $task->isCompleted() ? 'btn-outline-success' : 'btn-success' }}">
                                <i class="bi bi-check"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted">Nenhuma tarefa para hoje.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Active Goals -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-target"></i> Metas Ativas</h5>
                <a href="{{ route('goals.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus"></i> Nova Meta
                </a>
            </div>
            <div class="card-body">
                @forelse($activeGoals as $goal)
                    <div class="mb-3 p-2 border rounded">
                        <h6 class="mb-1">{{ $goal->title }}</h6>
                        @if($goal->description)
                            <p class="text-muted small mb-2">{{ Str::limit($goal->description, 100) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                {{ $goal->tasks->count() }} tarefa(s) pendente(s)
                            </small>
                            <a href="{{ route('goals.show', $goal) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Nenhuma meta ativa.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Overdue Tasks -->
    @if($overdueTasks->count() > 0)
    <div class="col-md-6 mb-4">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Tarefas Atrasadas</h5>
            </div>
            <div class="card-body">
                @foreach($overdueTasks as $task)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <div class="flex-grow-1">
                            <span>{{ $task->title }}</span>
                            <small class="text-danger d-block">
                                Venceu em: {{ $task->due_date->format('d/m/Y') }}
                            </small>
                            @if($task->category)
                                <span class="badge bg-secondary">{{ $task->category->name }}</span>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-check"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Completed Tasks -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-check-circle"></i> Tarefas Concluídas Recentemente</h5>
            </div>
            <div class="card-body">
                @forelse($recentCompletedTasks as $task)
                    <div class="mb-2 p-2 border rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="task-completed">{{ $task->title }}</span>
                            <small class="text-muted">
                                {{ $task->completed_at->diffForHumans() }}
                            </small>
                        </div>
                        @if($task->category)
                            <span class="badge bg-secondary">{{ $task->category->name }}</span>
                        @endif
                        @if($task->reward)
                            <span class="badge bg-success">🎁 {{ $task->reward }}</span>
                        @endif
                    </div>
                @empty
                    <p class="text-muted">Nenhuma tarefa concluída recentemente.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Resumo Rápido</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <h3 class="text-primary">{{ $todayTasks->count() }}</h3>
                            <p class="mb-0">Tarefas Hoje</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <h3 class="text-warning">{{ $overdueTasks->count() }}</h3>
                            <p class="mb-0">Atrasadas</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <h3 class="text-info">{{ $activeGoals->count() }}</h3>
                            <p class="mb-0">Metas Ativas</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <h3 class="text-success">{{ $recentCompletedTasks->count() }}</h3>
                            <p class="mb-0">Concluídas Recentemente</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
