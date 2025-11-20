@extends('layouts.app')

@section('title', 'Nova Categoria')
@section('page-title', 'Nova Categoria')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome da Categoria *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Criar Categoria
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Sobre Categorias</h6>
            </div>
            <div class="card-body">
                <p>As categorias ajudam você a organizar suas tarefas por contexto ou tipo.</p>
                <p><strong>Exemplos de categorias:</strong></p>
                <ul>
                    <li>Trabalho</li>
                    <li>Pessoal</li>
                    <li>Estudos</li>
                    <li>Casa</li>
                    <li>Saúde</li>
                    <li>Projetos</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
