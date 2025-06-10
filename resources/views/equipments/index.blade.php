@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Доступное снаряжение</h1>
    <div class="row">
        @foreach ($equipment as $item)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($item->image_path)
                        <img src="{{ asset($item->image_path) }}" class="card-img-top" alt="{{ $item->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">{{ $item->description }}</p>
                        <a href="{{ route('equipment.rent', $item->id) }}" class="btn btn-success">Забронировать</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
