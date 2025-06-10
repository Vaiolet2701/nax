@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактирование видео</h1>
        <form action="{{ route('admin.videos.update', $video->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Название видео</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $video->title }}" required>
            </div>
            <div class="form-group">
                <label for="url">Ссылка на видео</label>
                <input type="url" name="url" id="url" class="form-control" value="{{ $video->url }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
@endsection