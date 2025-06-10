@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список статей</h1>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary mb-3">Создать статью</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                    <tr>
                        <td>{{ $article->title }}</td>
                        <td class="d-flex gap-2">
    <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-sm btn-success">
        Редактировать
    </a>

    <button 
        onclick="event.preventDefault(); if(confirm('Удалить статью?')) document.getElementById('delete-article-{{ $article->id }}').submit();" 
        class="btn btn-sm btn-danger">
        Удалить
    </button>

    <form id="delete-article-{{ $article->id }}" action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" style="display: none;">
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