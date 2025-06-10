@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список курсов</h1>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary mb-3">Создать курс</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Период</th>
                    <th>Изображение</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->category->name }}</td>
                        <td>{{ $course->start_date }} - {{ $course->end_date }}</td>
                        <td>
                            @if($course->image_path)
                                <img src="{{ asset($course->image_path) }}" alt="Course Image" width="50">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-success btn-sm">Редактировать</a>
                           <button onclick="event.preventDefault(); if(confirm('Удалить курс?')) document.getElementById('delete-course-{{ $course->id }}').submit();" 
        class="btn btn-danger btn-sm">
    Удалить
</button>

<form id="delete-course-{{ $course->id }}" action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection