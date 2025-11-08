@extends('layouts.admin')

@section('title', 'Список заявок')

@section('content')
    <div class="container">
        <h1>Список заявок</h1>

        {{-- 1. Форма фильтров --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Фильтры</h5>
                <form action="{{ route('admin.tickets.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Статус</label>
                            <select id="status" name="status" class="form-select">
                                <option value="">Все</option>
                                <option value="new" {{ old('status', $filters['status'] ?? '') == 'new' ? 'selected' : '' }}>Новый</option>
                                <option value="in_progress" {{ old('status', $filters['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>В работе</option>
                                <option value="processed" {{ old('status', $filters['status'] ?? '') == 'processed' ? 'selected' : '' }}>Обработан</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email клиента</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $filters['email'] ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="phone" class="form-label">Телефон клиента</label>
                            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $filters['phone'] ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Дата от</label>
                            <input type="date" id="date_from" name="date_from" class="form-control" value="{{ old('date_from', $filters['date_from'] ?? '') }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Применить</button>
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">Сбросить</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- 2. Таблица заявок --}}
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Тема</th>
                        <th>Клиент</th>
                        <th>Статус</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ Str::limit($ticket->subject, 40) }}</td>
                            <td>{{ $ticket->customer?->name ?? 'Не указан' }}({{ $ticket->customer->email }})</td>
                            <td>
                                <span class="badge {{ $ticket->status == 'new' ? 'bg-info' : ($ticket->status == 'in_progress' ? 'bg-warning' : 'bg-success') }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td>{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                    Просмотр
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Заявки не найдены</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 3. Пагинация --}}
            @if ($tickets->hasPages())
                <div class="card-footer">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
