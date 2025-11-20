@extends('layouts.app')

@section('title', 'Meta: ' . $goal->title)
@section('page-title', 'Meta: ' . $goal->title)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('goals.edit', $goal) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <form method="POST" action="{{ route('goals.destroy', $goal) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta meta? As tarefas associadas não serão excluídas.')">
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
                <h3>{{ $goal->title }}</h3>
                @if($goal->description)
                    <p class="text-muted mb-4">{{ $goal->description }}</p>
                @endif

                @php
                    $totalTasks = $goal->tasks->count();
                    $completedTasks = $goal->tasks->where('completed_at', '!=', null)->count();
                    $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    
                    // Ícone de humor baseado na porcentagem
                    $moodIcon = '😐'; // Padrão sério
                    if ($percentage >= 75) {
                        $moodIcon = '😄'; // Feliz
                    } elseif ($percentage >= 25) {
                        $moodIcon = '🙂'; // Neutro/OK
                    }
                @endphp

                @if($totalTasks > 0)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6>Progresso da Meta</h6>
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size: 1.5rem;">{{ $moodIcon }}</span>
                                <span class="fw-bold">{{ $percentage }}%</span>
                            </div>
                        </div>
                        <div class="progress" style="height: 15px;">
                            <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                        </div>
                        <small class="text-muted">{{ $completedTasks }} de {{ $totalTasks }} tarefas concluídas</small>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-task"></i> Tarefas desta Meta</h5>
            </div>
            <div class="card-body">
                @forelse($goal->tasks as $task)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 {{ $task->isCompleted() ? 'task-completed' : '' }}">
                                {{ $task->title }}
                            </h6>
                            
                            <div class="mb-2">
                                @if($task->category)
                                    <span class="badge bg-secondary">{{ $task->category->name }}</span>
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
                        <i class="bi bi-target display-1 text-muted"></i>
                        <h4 class="mt-3">Nenhuma tarefa associada a esta meta</h4>
                        <p class="text-muted">Crie tarefas e associe-as a esta meta para acompanhar seu progresso!</p>
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
                            <h4 class="text-primary">{{ $totalTasks }}</h4>
                            <small>Total de Tarefas</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 mb-2">
                            <h4 class="text-success">{{ $completedTasks }}</h4>
                            <small>Concluídas</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 mb-2">
                            <h4 class="text-warning">{{ $goal->tasks->whereNull('completed_at')->count() }}</h4>
                            <small>Pendentes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 mb-2">
                            <h4 class="text-danger">{{ $goal->tasks->whereNull('completed_at')->where('due_date', '<', now())->count() }}</h4>
                            <small>Atrasadas</small>
                        </div>
                    </div>
                </div>

                @if($totalTasks > 0)
                    <div class="mt-3 text-center">
                        <h6>Status da Meta</h6>
                        <div style="font-size: 3rem;">{{ $moodIcon }}</div>
                        <p class="mb-0">
                            @if($percentage < 25)
                                <strong>Começando...</strong><br>
                                <small class="text-muted">Continue trabalhando nas tarefas!</small>
                            @elseif($percentage < 75)
                                <strong>Progredindo bem!</strong><br>
                                <small class="text-muted">Você está no caminho certo!</small>
                            @else
                                <strong>Parabéns!</strong><br>
                                <small class="text-muted">Parabéns pelo progresso!</small>
                            @endif
                        </p>
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
                    <a href="{{ route('tasks.create') }}?goal_id={{ $goal->id }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Nova Tarefa para esta Meta
                    </a>
                    <a href="{{ route('goals.edit', $goal) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar Meta
                    </a>
                    <a href="{{ route('goals.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar para Metas
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
                        <td><strong>Título:</strong></td>
                        <td>{{ $goal->title }}</td>
                    </tr>
                    @if($goal->description)
                        <tr>
                            <td><strong>Descrição:</strong></td>
                            <td>{{ Str::limit($goal->description, 100) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td><strong>Criada em:</strong></td>
                        <td>{{ $goal->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Última atualização:</strong></td>
                        <td>{{ $goal->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
