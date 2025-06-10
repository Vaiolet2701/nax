@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Заявки на аренду</h1>
    @foreach ($rentals as $rental)
        <div class="card mb-3">
            <div class="card-body">
                <p><strong>Пользователь:</strong> {{ $rental->user->name }}</p>
                <p><strong>Снаряжение:</strong> {{ $rental->equipment->name }}</p>
                <p><strong>Срок:</strong> {{ $rental->start_date }} – {{ $rental->end_date }}</p>
                <p><strong>Статус:</strong> {{ $rental->is_approved ? 'Одобрено' : 'Ожидает одобрения' }}</p>

                @if (!$rental->is_approved)
                    <form method="POST" action="{{ route('admin.rentals.approve', $rental->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success">Одобрить</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
