@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Список снаряжения</h1>

    <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary mb-3">Добавить снаряжение</a>

    @if($equipments->isEmpty())
        <p>Снаряжение отсутствует.</p>
    @else
        <div class="admin-table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Город</th>
                        <th>Изображение</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipments as $item)
                        <tr>

                            <td data-label="Название">{{ $item->name }}</td>
                            <td data-label="Описание">{{ $item->description }}</td>
                            <td data-label="Город">{{ $item->city }}</td>
                            <td data-label="Изображение">
                                @if($item->image_path)
                                    <img src="{{ asset($item->image_path) }}" alt="{{ $item->name }}" width="100">
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection