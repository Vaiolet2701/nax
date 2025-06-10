@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $course->title }}</h1>
        <p>{{ $course->description }}</p>
        <p>Категория: {{ $course->category->name }}</p>
        <p>Период: {{ $course->start_date }} - {{ $course->end_date }}</p>
        @if($course->image_path)
            <img src="{{ asset($course->image_path) }}" alt="Course Image" width="200">
        @endif
        <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-warning">Редактировать</a>
        <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Удалить</button>
        </form>
    </div>
@endsection