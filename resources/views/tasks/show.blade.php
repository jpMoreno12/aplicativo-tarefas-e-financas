@extends('layouts.app')

@section('title', 'Detalhes da Tarefa')
@section('page-title', 'Detalhes da Tarefa')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="d-inline"
            onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Excluir
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h3 class="{{ $task->isCompleted() ? 'task-completed' : '' }}">
                            {{ $task->title }}
                        </h3>
                        <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="btn {{ $task->isCompleted() ? 'btn-outline-success' : 'btn-success' }}">
                                <i class="bi bi-check"></i>
                                {{ $task->isCompleted() ? 'Marcar como Pendente' : 'Marcar como Concluída' }}
                            </button>
                        </form>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Informações Gerais</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if ($task->isCompleted())
                                            <span class="badge bg-success">Concluída</span>
                                        @else
                                            <span class="badge bg-warning">Pendente</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Categoria:</strong></td>
                                    <td>
                                        @if ($task->category)
                                            <span class="badge bg-secondary">{{ $task->category->name }}</span>
                                        @else
                                            <span class="text-muted">Sem categoria</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Meta:</strong></td>
                                    <td>
                                        @if ($task->goal)
                                            <span class="badge bg-info">{{ $task->goal->title }}</span>
                                        @else
                                            <span class="text-muted">Sem meta associada</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Prioridade:</strong></td>
                                    <td>
                                        <span class="badge {{ $task->getPriorityBadgeClass() }}">
                                            <i class="{{ $task->getPriorityIcon() }}"></i> {{ $task->getPriorityLabel() }}
                                        </span>
                                    </td>
                                </tr>
                                @if ($task->due_date)
                                    <tr>
                                        <td><strong>Vencimento:</strong></td>
                                        <td>
                                            <span
                                                class="badge {{ $task->due_date->isPast() && !$task->isCompleted() ? 'bg-danger' : 'bg-info' }}">
                                                {{ $task->due_date->format('d/m/Y') }}
                                            </span>
                                            @if ($task->due_date->isPast() && !$task->isCompleted())
                                                <small class="text-danger">(Atrasada)</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($task->reward)
                                    <tr>
                                        <td><strong>Recompensa:</strong></td>
                                        <td>
                                            <span class="badge bg-success">🎁 {{ $task->reward }}</span>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6>Datas</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Criada em:</strong></td>
                                    <td>{{ $task->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Última atualização:</strong></td>
                                    <td>{{ $task->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @if ($task->completed_at)
                                    <tr>
                                        <td><strong>Concluída em:</strong></td>
                                        <td>{{ $task->completed_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if ($task->subtasks->count() > 0)
                        <div class="mb-4">
                            <h6>Subtarefas ({{ $task->subtasks->count() }})</h6>
                            <div class="list-group">
                                @foreach ($task->subtasks as $subtask)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <span class="{{ $subtask->isCompleted() ? 'task-completed' : '' }}">
                                                {{ $subtask->title }}
                                            </span>
                                            <div class="mt-1">
                                                @if ($subtask->category)
                                                    <span class="badge bg-secondary">{{ $subtask->category->name }}</span>
                                                @endif
                                                @if ($subtask->due_date)
                                                    <span
                                                        class="badge {{ $subtask->due_date->isPast() && !$subtask->isCompleted() ? 'bg-danger' : 'bg-info' }}">
                                                        {{ $subtask->due_date->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <form method="POST" action="{{ route('tasks.toggle', $subtask) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-sm {{ $subtask->isCompleted() ? 'btn-outline-success' : 'btn-success' }}">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('tasks.show', $subtask) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('tasks.edit', $subtask) }}"
                                                class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar para Lista
                        </a>
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Editar Tarefa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Ações Rápidas</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="btn {{ $task->isCompleted() ? 'btn-outline-success' : 'btn-success' }} w-100">
                                <i class="bi bi-check"></i>
                                {{ $task->isCompleted() ? 'Marcar como Pendente' : 'Concluir Tarefa' }}
                            </button>
                        </form>

                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning w-100">
                            <i class="bi bi-pencil"></i> Editar
                        </a>

                        @if ($task->goal)
                            <a href="{{ route('goals.show', $task->goal) }}" class="btn btn-info w-100">
                                <i class="bi bi-target"></i> Ver Meta
                            </a>
                        @endif

                        @if ($task->category)
                            <a href="{{ route('categories.show', $task->category) }}" class="btn btn-secondary w-100">
                                <i class="bi bi-tags"></i> Ver Categoria
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if ($task->parent)
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-arrow-up"></i> Tarefa Pai</h6>
                    </div>
                    <div class="card-body">
                        <h6>{{ $task->parent->title }}</h6>
                        <a href="{{ route('tasks.show', $task->parent) }}" class="btn btn-sm btn-outline-primary">
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
