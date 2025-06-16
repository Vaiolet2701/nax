@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-light">Список походов</h1>
                @if(auth()->user()->laravel_level === 'Продвинутый')
                    <a href="{{ route('trips.create') }}" class="btn btn-primary">Создать поход</a>
                @endif
            </div>

            @foreach($trips as $trip)
                <div class="trip-card mb-4">
                    <div class="trip-card-body">
                        <h3 class="trip-card-title">
                            <a href="{{ route('trips.show', $trip) }}">{{ $trip->title }}</a>
                        </h3>
                        <p class="trip-card-text">{{ Str::limit($trip->description, 200) }}</p>
                        <div class="trip-card-footer">
                            <div class="trip-card-meta">
                                <span class="trip-card-meta-item">Организатор: {{ $trip->user->name }}</span>
                                <span class="trip-card-meta-item">Даты: {{ \Carbon\Carbon::parse($trip->start_date)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('d.m.Y') }}</span>
                                <span class="trip-card-meta-item">Место: {{ $trip->location }}</span>
                            </div>
                            <span class="trip-card-badge">{{ $trip->max_participants }} мест</span>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-4">
                {{ $trips->links() }}
            </div>
        </div>
    </div>
</div>
@endsection