@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Список походов</h1>
                @if(auth()->user()->laravel_level === 'Продвинутый')
                    <a href="{{ route('trips.create') }}" class="btn btn-primary">Создать поход</a>
                @endif
            </div>

            @foreach($trips as $trip)
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="{{ route('trips.show', $trip) }}">{{ $trip->title }}</a>
                        </h3>
                        <p class="card-text">{{ Str::limit($trip->description, 200) }}</p>
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="text-muted">Организатор: {{ $trip->user->name }}</span> |
                                <span class="text-muted">Даты: {{ \Carbon\Carbon::parse($trip->start_date)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('d.m.Y') }}</span> |
                                <span class="text-muted">Место: {{ $trip->location }}</span>
                            </div>
                            <span class="badge bg-primary">{{ $trip->max_participants }} мест</span>
                        </div>
                    </div>
                </div>
            @endforeach

            {{ $trips->links() }}
        </div>
    </div>
</div>
@endsection