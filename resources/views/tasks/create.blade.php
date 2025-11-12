@extends('layouts.app')

@section('title', 'Nova Tarefa')
@section('page-title', 'Nova Tarefa')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Título *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Categoria</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id">
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="goal_id" class="form-label">Meta</label>
                                <select class="form-select @error('goal_id') is-invalid @enderror" 
                                        id="goal_id" name="goal_id">
                                    <option value="">Selecione uma meta</option>
                                    @foreach($goals as $goal)
                                        <option value="{{ $goal->id }}" {{ old('goal_id') == $goal->id ? 'selected' : '' }}>
                                            {{ $goal->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('goal_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Tarefa Pai (para subtarefas)</label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" 
                                id="parent_id" name="parent_id">
                            <option value="">Esta é uma tarefa principal</option>
                            @foreach($parentTasks as $parentTask)
                                <option value="{{ $parentTask->id }}" {{ old('parent_id') == $parentTask->id ? 'selected' : '' }}>
                                    {{ $parentTask->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Data de Vencimento</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" name="due_date" value="{{ old('due_date') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reward" class="form-label">Recompensa</label>
                                <input type="text" class="form-control @error('reward') is-invalid @enderror" 
                                       id="reward" name="reward" value="{{ old('reward') }}" 
                                       placeholder="Ex: Assistir um filme, Comer um doce...">
                                @error('reward')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Criar Tarefa
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Dicas</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-lightbulb text-warning"></i>
                        <strong>Título:</strong> Seja específico e claro sobre o que precisa ser feito.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-tags text-info"></i>
                        <strong>Categoria:</strong> Organize suas tarefas por contexto (trabalho, pessoal, etc.).
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-target text-success"></i>
                        <strong>Meta:</strong> Vincule tarefas às suas metas para acompanhar o progresso.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-gift text-primary"></i>
                        <strong>Recompensa:</strong> Motive-se com pequenas recompensas ao concluir tarefas.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
