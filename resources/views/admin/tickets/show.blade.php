@extends('layouts.admin')

@section('title', "Заявка #{$ticket->id}: {$ticket->subject}")

@section('content')
    <div class="container">
        <div class="row">
            {{-- Левая колонка - Детали --}}
            <div class="col-md-8">
                <h1>{{ $ticket->subject }}</h1>
                <p class="text-muted">
                    Создана: {{ $ticket->created_at->format('d.m.Y H:i') }} |
                    Статус: <span class="badge {{ $ticket->status == 'new' ? 'bg-info' : ($ticket->status == 'in_progress' ? 'bg-warning' : 'bg-success') }}">{{ $ticket->status }}</span>
                </p>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Текст заявки --}}
                <div class="card mb-4">
                    <div class="card-header">Текст заявки</div>
                    <div class="card-body">
                        {!! nl2br(e($ticket->body)) !!}
                    </div>
                </div>

                {{-- Файлы --}}
                @if($ticket->media->isNotEmpty())
                    <div class="card mb-4">
                        <div class="card-header">Прикрепленные файлы</div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($ticket->getMedia('attachments') as $media)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $media->file_name }} ({{ $media->human_readable_size }})
                                        <a href="{{ $media->getFullUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            Скачать
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Правая колонка - Клиент и Статус --}}
            <div class="col-md-4">
                {{-- Смена статуса --}}
                <div class="card mb-4">
                    <div class="card-header">Управление</div>
                    <div class="card-body">
                        <form action="{{ route('admin.tickets.updateStatus', $ticket) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="status" class="form-label">Сменить статус</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="new" {{ $ticket->status == 'new' ? 'selected' : '' }}>Новый</option>
                                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>В работе</option>
                                    <option value="processed" {{ $ticket->status == 'processed' ? 'selected' : '' }}>Обработан</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Обновить</button>
                        </form>
                    </div>
                </div>

                {{-- Клиент --}}
                <div class="card mb-4">
                    <div class="card-header">Клиент</div>
                    <div class="card-body">
                        <p><strong>Имя:</strong> {{ $ticket->customer->name }}</p>
                        <p><strong>Email:</strong> {{ $ticket->customer->email }}</p>
                        <p><strong>Телефон:</strong> {{ $ticket->customer->phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
