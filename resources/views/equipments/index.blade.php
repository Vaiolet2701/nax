@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Доступное снаряжение</h1>
    <div class="row">
        @foreach ($equipment as $item)
            <div class="col-md-4 mb-4">
                <div class="equipment-card"> <!-- Изменил класс на equipment-card -->
                    @if($item->image_path)
                        <img src="{{ asset($item->image_path) }}" class="equipment-card-img" alt="{{ $item->name }}">
                    @endif
                    <div class="equipment-card-body">
                        <h5 class="equipment-card-title">{{ $item->name }}</h5>
                        <p class="equipment-card-text">{{ $item->description }}</p>
                        <a href="{{ route('equipment.rent', $item->id) }}" class="btn btn-success equipment-card-btn">Забронировать</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection