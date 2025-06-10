@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактирование статьи</h1>
        <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Название статьи</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $article->title }}" required>
            </div>
            <div class="form-group">
                <label for="content">Содержание статьи</label>
                <textarea name="content" id="content" class="form-control" rows="5" required>{{ $article->content }}</textarea>
            </div>
            <div class="form-group">
            <label for="category_id">Категория статьи</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="" disabled selected>Выберите категорию</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
            <div class="form-group">
                <label for="image">Изображение (по желанию)</label>
                <input type="file" name="image" id="image" class="form-control">
                @if($article->image_path)
                    <img src="{{ asset($article->image_path) }}" alt="{{ $article->title }}" class="img-thumbnail mt-2" style="max-width: 200px;">
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
@endsection