@extends('layouts.app')

@section('title', 'Categorias')
@section('page-title', 'Categorias')

@section('page-actions')
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Nova Categoria
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @forelse($categories as $category)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $category->name }}</h6>
                            <small class="text-muted">
                                {{ $category->tasks_count }} tarefa(s) associada(s)
                            </small>
                        </div>
                        <div class="d-flex gap-1">
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria? As tarefas associadas não serão excluídas.')">
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
                        <i class="bi bi-tags display-1 text-muted"></i>
                        <h4 class="mt-3">Nenhuma categoria encontrada</h4>
                        <p class="text-muted">Organize suas tarefas criando categorias!</p>
                        <a href="{{ route('categories.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Criar Categoria
                        </a>
                    </div>
                @endforelse

                @if($categories->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
