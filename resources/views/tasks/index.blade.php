@extends('layouts.app')

@section('title', 'Tarefas')
@section('page-title', 'Tarefas')

@section('page-actions')
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Nova Tarefa
    </a>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('tasks.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <select name="category_id" class="form-select">
                            <option value="">Todas as Categorias</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="goal_id" class="form-select">
                            <option value="">Todas as Metas</option>
                            @foreach($goals as $goal)
                                <option value="{{ $goal->id }}" {{ request('goal_id') == $goal->id ? 'selected' : '' }}>
                                    {{ $goal->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Todos os Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Concluídas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i> Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @forelse($tasks as $task)
                    <div class="d-flex justify-content-between align-items-start mb-3 p-3 border rounded">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 {{ $task->isCompleted() ? 'task-completed' : '' }}">
                                {{ $task->title }}
                            </h6>
                            
                            <div class="mb-2">
                                <span class="badge {{ $task->getPriorityBadgeClass() }}">
                                    <i class="{{ $task->getPriorityIcon() }}"></i> {{ $task->getPriorityLabel() }}
                                </span>
                                @if($task->category)
                                    <span class="badge bg-secondary">{{ $task->category->name }}</span>
                                @endif
                                @if($task->goal)
                                    <span class="badge bg-info">{{ $task->goal->title }}</span>
                                @endif
                                @if($task->due_date)
                                    <span class="badge {{ $task->due_date->isPast() && !$task->isCompleted() ? 'bg-danger' : 'bg-warning' }}">
                                        {{ $task->due_date->format('d/m/Y') }}
                                    </span>
                                @endif
                                @if($task->reward)
                                    <span class="badge bg-success">🎁 {{ $task->reward }}</span>
                                @endif
                            </div>

                            @if($task->subtasks->count() > 0)
                                <div class="mt-2">
                                    <small class="text-muted">Subtarefas:</small>
                                    @foreach($task->subtasks as $subtask)
                                        <div class="ms-3 mt-1">
                                            <small class="{{ $subtask->isCompleted() ? 'task-completed' : '' }}">
                                                <i class="bi bi-arrow-return-right"></i> {{ $subtask->title }}
                                            </small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if($task->isCompleted())
                                <small class="text-success d-block mt-1">
                                    <i class="bi bi-check-circle"></i> Concluída em {{ $task->completed_at->format('d/m/Y H:i') }}
                                </small>
                            @endif
                        </div>

                        <div class="d-flex gap-1">
                            <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $task->isCompleted() ? 'btn-outline-success' : 'btn-success' }}" title="{{ $task->isCompleted() ? 'Marcar como pendente' : 'Marcar como concluída' }}">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                            
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                <i class="bi bi-eye"></i>
                            </a>
                            
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-check-square display-1 text-muted"></i>
                        <h4 class="mt-3">Nenhuma tarefa encontrada</h4>
                        <p class="text-muted">Comece criando sua primeira tarefa!</p>
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Criar Tarefa
                        </a>
                    </div>
                @endforelse

                @if($tasks->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
