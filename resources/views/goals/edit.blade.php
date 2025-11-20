@extends('layouts.app')

@section('title', 'Editar Meta')
@section('page-title', 'Editar Meta')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('goals.update', $goal) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Título da Meta *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $goal->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $goal->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Atualizar Meta
                        </button>
                        <a href="{{ route('goals.show', $goal) }}" class="btn btn-secondary">
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
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Status da Meta</h6>
            </div>
            <div class="card-body">
                @php
                    $totalTasks = $goal->tasks()->count();
                    $completedTasks = $goal->tasks()->whereNotNull('completed_at')->count();
                    $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    
                    // Ícone de humor baseado na porcentagem
                    $moodIcon = '😐'; // Padrão sério
                    if ($percentage >= 75) {
                        $moodIcon = '😄'; // Feliz
                    } elseif ($percentage >= 25) {
                        $moodIcon = '🙂'; // Neutro/OK
                    }
                @endphp

                <div class="text-center mb-3">
                    <div style="font-size: 3rem;">{{ $moodIcon }}</div>
                    <h4>{{ $percentage }}%</h4>
                    <p class="mb-0">{{ $completedTasks }} de {{ $totalTasks }} tarefas</p>
                </div>

                @if($totalTasks > 0)
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                    </div>
                @endif

                <table class="table table-sm">
                    <tr>
                        <td><strong>Tarefas totais:</strong></td>
                        <td>{{ $totalTasks }}</td>
                    </tr>
                    <tr>
                        <td><strong>Concluídas:</strong></td>
                        <td>{{ $completedTasks }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pendentes:</strong></td>
                        <td>{{ $totalTasks - $completedTasks }}</td>
                    </tr>
                    <tr>
                        <td><strong>Criada em:</strong></td>
                        <td>{{ $goal->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightbulb"></i> Dicas para Edição</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-check text-success"></i>
                        <strong>Título claro:</strong> Use um título que descreva bem seu objetivo.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-text-paragraph text-info"></i>
                        <strong>Descrição detalhada:</strong> Explique o que você quer alcançar e por quê.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-list-task text-warning"></i>
                        <strong>Tarefas específicas:</strong> Após salvar, crie tarefas específicas para esta meta.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
