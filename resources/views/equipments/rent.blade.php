@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Аренда снаряжения: {{ $equipment->name }}</h1>

    <form action="{{ route('equipment.rent.store', $equipment->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="start_date">Дата начала аренды</label>
            <input type="date" id="start_date" name="start_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="end_date">Дата окончания аренды</label>
            <input type="date" id="end_date" name="end_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Отправить заявку</button>
    </form>
</div>
@endsection
