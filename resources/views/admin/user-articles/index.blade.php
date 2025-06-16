@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Статьи пользователей на проверку</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-table-responsive">
            <table class="table admin-table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Название</th>
                        <th>Автор</th>
                        <th>Категория</th>
                        <th>Контент</th>
                        <th>Изображение</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td data-label="Название">{{ $article->title }}</td>
                            <td data-label="Автор">{{ $article->user->name }}</td>
                            <td data-label="Категория">{{ $article->category->name ?? 'Без категории' }}</td>
                            <td data-label="Контент">{{ Str::limit($article->content, 100) }}</td>
                            <td data-label="Изображение">
                                @if($article->image_path)
                                    <img src="{{ asset($article->image_path) }}" alt="Изображение статьи" class="img-thumbnail" width="100">
                                @else
                                    Нет изображения
                                @endif
                            </td>
                            <td data-label="Действия">
                                <div class="btn-group">
                                    <button 
                                        onclick="event.preventDefault(); document.getElementById('approve-article-{{ $article->id }}').submit();" 
                                        class="btn btn-sm btn-success">
                                        Одобрить
                                    </button>

                                    <form id="approve-article-{{ $article->id }}" action="{{ route('admin.user-articles.approve', $article->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form>

                                    <button 
                                        onclick="event.preventDefault(); if(confirm('Вы уверены?')) document.getElementById('delete-article-{{ $article->id }}').submit();" 
                                        class="btn btn-sm btn-danger">
                                        Удалить
                                    </button>

                                    <form id="delete-article-{{ $article->id }}" action="{{ route('admin.user-articles.destroy', $article->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Нет статей для модерации</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[method="DELETE"]');
            
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Вы уверены, что хотите удалить эту статью?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush