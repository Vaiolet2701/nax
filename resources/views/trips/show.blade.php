@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="trip-detail-card">
                <div class="trip-detail-header">
                    <h2 class="trip-detail-title">{{ $trip->title }}</h2>
                    @can('update', $trip)
                        <div class="trip-detail-actions">
                            <a href="{{ route('trips.edit', $trip) }}" class="btn btn-sm btn-primary">Редактировать</a>
                            <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                            </form>
                        </div>
                    @endcan
                </div>

                <div class="trip-detail-body">
                    <div class="trip-detail-section">
                        <span class="trip-detail-label">Организатор:</span>
                        <span class="trip-detail-value">{{ $trip->user->name }}</span>
                    </div>
                    
                    <div class="trip-detail-section">
                        <span class="trip-detail-label">Описание:</span>
                        <p class="trip-detail-text">{{ $trip->description }}</p>
                    </div>
                    
                    <div class="trip-detail-row">
                        <div class="trip-detail-col">
                            <span class="trip-detail-label">Дата начала:</span>
                            <span class="trip-detail-value">
                                {{ is_string($trip->start_date) ? \Carbon\Carbon::parse($trip->start_date)->format('d.m.Y') : $trip->start_date->format('d.m.Y') }}
                            </span>
                        </div>
                        <div class="trip-detail-col">
                            <span class="trip-detail-label">Дата окончания:</span>
                            <span class="trip-detail-value">
                                {{ is_string($trip->end_date) ? \Carbon\Carbon::parse($trip->end_date)->format('d.m.Y') : $trip->end_date->format('d.m.Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="trip-detail-section">
                        <span class="trip-detail-label">Место проведения:</span>
                        <span class="trip-detail-value">{{ $trip->location }}</span>
                    </div>
                    
                    <div class="trip-detail-section">
                        <span class="trip-detail-label">Максимальное количество участников:</span>
                        <span class="trip-detail-value">{{ $trip->max_participants }}</span>
                    </div>
                    
                    @auth
                        @if(auth()->user()->id !== $trip->user_id)
                        <div class="trip-join-form">
                            <h4 class="trip-join-title">Заявка на участие</h4>
                            <form action="{{ route('trips.join', $trip) }}" method="POST">
                                @csrf
                                
                                <div class="trip-form-group">
                                    <label for="name" class="trip-form-label">Ваше имя</label>
                                    <input type="text" class="trip-form-control" id="name" name="name" 
                                           value="{{ auth()->user()->name }}" required>
                                </div>
                                
                                <div class="trip-form-group">
                                    <label for="age" class="trip-form-label">Возраст</label>
                                    <input type="number" class="trip-form-control" id="age" name="age" 
                                           min="12" max="100" value="{{ auth()->user()->age ?? '' }}" required>
                                </div>
                                
                                <div class="trip-form-group">
                                    <label for="phone" class="trip-form-label">Телефон</label>
                                    <input type="tel" class="trip-form-control" id="phone" name="phone" 
                                           value="{{ auth()->user()->phone ?? '' }}" required>
                                </div>
                                
                                <div class="trip-form-group">
                                    <label for="notes" class="trip-form-label">Дополнительная информация</label>
                                    <textarea class="trip-form-control" id="notes" name="notes" rows="3"></textarea>
                                </div>
                                
                                <button type="submit" class="trip-submit-btn">Подать заявку</button>
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