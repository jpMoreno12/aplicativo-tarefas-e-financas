@extends('layouts.app')

@section('title', 'Metas')
@section('page-title', 'Metas')

@section('page-actions')
    <a href="{{ route('goals.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Nova Meta
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @forelse($goals as $goal)
                    <div class="mb-4 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="mb-2">{{ $goal->title }}</h5>
                                @if($goal->description)
                                    <p class="text-muted mb-2">{{ $goal->description }}</p>
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="bi bi-list-task"></i> 
                                            {{ $goal->tasks_count }} tarefa(s) total
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-success">
                                            <i class="bi bi-check-circle"></i> 
                                            {{ $goal->completed_tasks_count }} concluída(s)
                                        </small>
                                    </div>
                                </div>
                                
                                @if($goal->tasks_count > 0)
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div class="progress-bar bg-success" 
                                             style="width: {{ ($goal->completed_tasks_count / $goal->tasks_count) * 100 }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        {{ round(($goal->completed_tasks_count / $goal->tasks_count) * 100) }}% concluído
                                    </small>
                                @endif
                            </div>
                            
                            <div class="d-flex gap-1">
                                <a href="{{ route('goals.show', $goal) }}" class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('goals.edit', $goal) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('goals.destroy', $goal) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta meta? As tarefas associadas não serão excluídas.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-target display-1 text-muted"></i>
                        <h4 class="mt-3">Nenhuma meta encontrada</h4>
                        <p class="text-muted">Defina seus objetivos criando metas!</p>
                        <a href="{{ route('goals.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Criar Meta
                        </a>
                    </div>
                @endforelse

                @if($goals->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $goals->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
