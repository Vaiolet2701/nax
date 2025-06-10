@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $trip->title }}</h2>
                    @can('update', $trip)
                        <div class="float-end">
                            <a href="{{ route('trips.edit', $trip) }}" class="btn btn-sm btn-primary">Редактировать</a>
                            <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                            </form>
                        </div>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>Организатор:</strong> {{ $trip->user->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Описание:</strong>
                        <p>{{ $trip->description }}</p>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Дата начала:</strong> 
                            {{ is_string($trip->start_date) ? \Carbon\Carbon::parse($trip->start_date)->format('d.m.Y') : $trip->start_date->format('d.m.Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Дата окончания:</strong> 
                            {{ is_string($trip->end_date) ? \Carbon\Carbon::parse($trip->end_date)->format('d.m.Y') : $trip->end_date->format('d.m.Y') }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Место проведения:</strong> {{ $trip->location }}
                    </div>
                    <div class="mb-3">
                        <strong>Максимальное количество участников:</strong> {{ $trip->max_participants }}
                    </div>
                    
                    @auth
                        @if(auth()->user()->id !== $trip->user_id)
                        <div class="join-form mt-4">
                            <h4>Заявка на участие</h4>
                            <form action="{{ route('trips.join', $trip) }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Ваше имя</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ auth()->user()->name }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="age" class="form-label">Возраст</label>
                                    <input type="number" class="form-control" id="age" name="age" 
                                           min="12" max="100" value="{{ auth()->user()->age ?? '' }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Телефон</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="{{ auth()->user()->phone ?? '' }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Дополнительная информация</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-success">Подать заявку</button>
                            </form>
                        </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection