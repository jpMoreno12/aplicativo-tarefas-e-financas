@extends('layouts.app')

@section('title', 'Finanças')
@section('page-title', 'Finanças')

@section('content')
<!-- Month Navigation -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        {{ \Carbon\Carbon::create($selectedYear, $selectedMonth)->format('F Y') }}
                    </h5>
                    <div class="btn-group">
                        @foreach($recentMonths->take(6) as $month)
                            <a href="{{ route('finances.index', ['month' => $month['month'], 'year' => $month['year']]) }}" 
                               class="btn btn-sm {{ $month['month'] == $selectedMonth && $month['year'] == $selectedYear ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $month['short_name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Summary -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">Renda Mensal</h5>
                <h3 class="text-success">R$ {{ number_format($monthlyIncome, 2, ',', '.') }}</h3>
                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#incomeModal">
                    <i class="bi bi-pencil"></i> Editar
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-danger">Total de Despesas</h5>
                <h3 class="text-danger">R$ {{ number_format($totalExpenses, 2, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">Saldo</h5>
                <h3 class="{{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                    R$ {{ number_format($balance, 2, ',', '.') }}
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-info">Total de Gastos</h5>
                <h3 class="text-info">{{ $expenses->count() }}</h3>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#expenseModal">
                    <i class="bi bi-plus"></i> Adicionar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Expenses by Category -->
@if($expensesByCategory->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Despesas por Categoria</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($expensesByCategory as $categoryData)
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h6>{{ $categoryData['category'] }}</h6>
                                <p class="mb-1">
                                    <strong>R$ {{ number_format($categoryData['total'], 2, ',', '.') }}</strong>
                                </p>
                                <small class="text-muted">{{ $categoryData['count'] }} gasto(s)</small>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar" style="width: {{ $totalExpenses > 0 ? ($categoryData['total'] / $totalExpenses) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Expenses List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list"></i> Lista de Despesas</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#expenseModal">
                    <i class="bi bi-plus"></i> Nova Despesa
                </button>
            </div>
            <div class="card-body">
                @forelse($expenses as $expense)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-3 border rounded">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $expense->name }}</h6>
                            <div>
                                @if($expense->category)
                                    <span class="badge bg-secondary">{{ $expense->category }}</span>
                                @endif
                                <span class="badge bg-info">{{ $expense->date->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <strong class="text-danger">R$ {{ number_format($expense->amount, 2, ',', '.') }}</strong>
                            <form method="POST" action="{{ route('finances.expense.destroy', $expense) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta despesa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-currency-dollar display-1 text-muted"></i>
                        <h4 class="mt-3">Nenhuma despesa registrada</h4>
                        <p class="text-muted">Comece adicionando suas primeiras despesas!</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#expenseModal">
                            <i class="bi bi-plus"></i> Adicionar Despesa
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Income Modal -->
<div class="modal fade" id="incomeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Atualizar Renda Mensal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('finances.income.update') }}">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="monthly_income" class="form-label">Renda Mensal *</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="monthly_income" name="monthly_income" 
                                   value="{{ $monthlyIncome }}" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Expense Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Despesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('finances.expense.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome da Despesa *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="amount" class="form-label">Valor *</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   step="0.01" min="0.01" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="category" name="category" 
                               placeholder="Ex: Alimentação, Transporte, Lazer...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="date" class="form-label">Data *</label>
                        <input type="date" class="form-control" id="date" name="date" 
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Adicionar Despesa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
