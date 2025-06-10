@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создание категории</h1>
        <form action="{{ route('admin.course-categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Название категории</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection