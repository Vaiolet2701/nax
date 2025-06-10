@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Добавление видео</h1>
        <form action="{{ route('admin.videos.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Название видео</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="url">Ссылка на видео</label>
                <input type="url" name="url" id="url" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>
@endsection