@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создание статьи</h1>
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Название статьи</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="content">Содержание статьи</label>
                <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
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
            </div>
            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection