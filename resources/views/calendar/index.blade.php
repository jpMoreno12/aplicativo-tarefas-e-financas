@extends('layouts.app')

@section('title', 'Calendário')
@section('page-title', 'Calendário')

@section('content')
<!-- Month Navigation -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('calendar.index', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" 
                       class="btn btn-outline-primary">
                        <i class="bi bi-chevron-left"></i> {{ $prevMonth->format('M Y') }}
                    </a>
                    
                    <h4 class="mb-0">{{ $startDate->format('F Y') }}</h4>
                    
                    <a href="{{ route('calendar.index', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" 
                       class="btn btn-outline-primary">
                        {{ $nextMonth->format('M Y') }} <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calendar -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Domingo</th>
                                <th class="text-center">Segunda</th>
                                <th class="text-center">Terça</th>
                                <th class="text-center">Quarta</th>
                                <th class="text-center">Quinta</th>
                                <th class="text-center">Sexta</th>
                                <th class="text-center">Sábado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calendarData as $week)
                                <tr>
                                    @foreach($week as $day)
                                        <td class="calendar-day {{ !$day['is_current_month'] ? 'other-month' : '' }} {{ $day['is_today'] ? 'today' : '' }}" 
                                            style="width: 14.28%; vertical-align: top;">
                                            <div class="p-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-bold">{{ $day['day'] }}</span>
                                                    @if($day['is_current_month'])
                                                        <small class="text-muted">{{ $day['tasks']->count() }}</small>
                                                    @endif
                                                </div>
                                                
                                                @if($day['is_current_month'] && $day['tasks']->count() > 0)
                                                    @foreach($day['tasks']->take(3) as $task)
                                                        <div class="task-badge badge {{ $task->isCompleted() ? 'bg-success' : ($task->due_date->isPast() ? 'bg-danger' : 'bg-primary') }} d-block text-start mb-1" 
                                                             title="{{ $task->title }}">
                                                            <small>
                                                                @if($task->isCompleted())
                                                                    <i class="bi bi-check"></i>
                                                                @elseif($task->due_date->isPast())
                                                                    <i class="bi bi-exclamation"></i>
                                                                @endif
                                                                {{ Str::limit($task->title, 15) }}
                                                            </small>
                                                        </div>
                                                    @endforeach
                                                    
                                                    @if($day['tasks']->count() > 3)
                                                        <small class="text-muted">+{{ $day['tasks']->count() - 3 }} mais</small>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tasks List for Selected Month -->
@if($tasks->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-task"></i> Tarefas do Mês</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($tasks->groupBy(function($task) { return $task->due_date->format('Y-m-d'); }) as $date => $dayTasks)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">
                                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                        <small class="text-muted">({{ \Carbon\Carbon::parse($date)->format('l') }})</small>
                                    </h6>
                                </div>
                                <div class="card-body py-2">
                                    @foreach($dayTasks as $task)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="flex-grow-1">
                                                <span class="{{ $task->isCompleted() ? 'task-completed' : '' }}">
                                                    {{ $task->title }}
                                                </span>
                                                <div>
                                                    @if($task->category)
                                                        <span class="badge bg-secondary">{{ $task->category->name }}</span>
                                                    @endif
                                                    @if($task->goal)
                                                        <span class="badge bg-info">{{ $task->goal->title }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $task->isCompleted() ? 'btn-outline-success' : 'btn-success' }}">
                                                        <i class="bi bi-check"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
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

<!-- Legend -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-2">
                <div class="d-flex justify-content-center gap-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">&nbsp;</span>
                        <small>Tarefas Pendentes</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">&nbsp;</span>
                        <small>Tarefas Concluídas</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger me-2">&nbsp;</span>
                        <small>Tarefas Atrasadas</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="calendar-day today me-2" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                        <small>Hoje</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
