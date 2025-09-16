@extends('layouts.app')

@section('title', 'Nova Meta')
@section('page-title', 'Nova Meta')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('goals.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Título da Meta *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Criar Meta
                        </button>
                        <a href="{{ route('goals.index') }}" class="btn btn-secondary">
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
                <h6 class="mb-0"><i class="bi bi-lightbulb"></i> Dicas para Metas</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-check text-success"></i>
                        <strong>Seja específico:</strong> Defina claramente o que você quer alcançar.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-calendar text-info"></i>
                        <strong>Divida em tarefas:</strong> Após criar a meta, adicione tarefas específicas para alcançá-la.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-graph-up text-warning"></i>
                        <strong>Acompanhe o progresso:</strong> Use o dashboard para ver como está indo.
                    </li>
                </ul>
                
                <hr>
                
                <p><strong>Exemplos de metas:</strong></p>
                <ul class="small">
                    <li>Aprender uma nova linguagem de programação</li>
                    <li>Organizar a casa</li>
                    <li>Melhorar a saúde</li>
                    <li>Economizar dinheiro</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
