@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Список снаряжения</h1>

    <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary mb-3">Добавить снаряжение</a>

    @if($equipments->isEmpty())
        <p>Снаряжение отсутствует.</p>
    @else
        <div class="row">
            @foreach($equipments as $item)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        @if($item->image_path)
                            <img src="{{ asset($item->image_path) }}" class="card-img-top" alt="{{ $item->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->name }}</h5>
                            <p class="card-text">{{ $item->description }}</p>
                            <p><strong>Город:</strong> {{ $item->city }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
