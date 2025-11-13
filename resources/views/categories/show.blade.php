@extends('layouts.app')

@section('title', 'Categoria: ' . $category->name)
@section('page-title', 'Categoria: ' . $category->name)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria? As tarefas associadas não serão excluídas.')">
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
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-task"></i> Tarefas desta Categoria</h5>
            </div>
            <div class="card-body">
                @forelse($category->tasks as $task)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 {{ $task->isCompleted() ? 'task-completed' : '' }}">
                                {{ $task->title }}
                            </h6>
                            
                            <div class="mb-2">
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

                            @if($task->isCompleted())
                                <small class="text-success d-block">
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
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-check-square display-1 text-muted"></i>
                        <h4 class="mt-3">Nenhuma tarefa nesta categoria</h4>
                        <p class="text-muted">Crie tarefas e associe-as a esta categoria!</p>
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Criar Tarefa
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Estatísticas</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border rounded p-3 mb-2">
                            <h4 class="text-primary">{{ $category->tasks->count() }}</h4>
                            <small>Total de Tarefas</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 mb-2">
                            <h4 class="text-success">{{ $category->tasks->where('completed_at', '!=', null)->count() }}</h4>
                            <small>Concluídas</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 mb-2">
                            <h4 class="text-warning">{{ $category->tasks->whereNull('completed_at')->count() }}</h4>
                            <small>Pendentes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 mb-2">
                            <h4 class="text-danger">{{ $category->tasks->whereNull('completed_at')->where('due_date', '<', now())->count() }}</h4>
                            <small>Atrasadas</small>
                        </div>
                    </div>
                </div>

                @if($category->tasks->count() > 0)
                    <div class="mt-3">
                        <h6>Progresso Geral</h6>
                        @php
                            $completedTasks = $category->tasks->where('completed_at', '!=', null)->count();
                            $totalTasks = $category->tasks->count();
                            $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                        @endphp
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                        </div>
                        <small class="text-muted">{{ $percentage }}% concluído</small>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-gear"></i> Ações</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('tasks.create') }}?category_id={{ $category->id }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Nova Tarefa nesta Categoria
                    </a>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar Categoria
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar para Categorias
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Informações</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Nome:</strong></td>
                        <td>{{ $category->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Criada em:</strong></td>
                        <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Última atualização:</strong></td>
                        <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
