@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Статьи пользователей на проверку</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
    <tr>
        <th>Название</th>
        <th>Автор</th>
        <th>Категория</th> <!-- Добавлено -->
        <th>Контент</th>
        <th>Изображение</th>
        <th>Действия</th>
    </tr>
</thead>
<tbody>
    @forelse($articles as $article)
        <tr>
            <td>{{ $article->title }}</td>
            <td>{{ $article->user->name }}</td>
            <td>{{ $article->category->name ?? 'Без категории' }}</td>
            <td>{{ Str::limit($article->content, 100) }}</td>
            <td>
                @if($article->image_path)
                    <img src="{{ asset($article->image_path) }}" alt="Изображение статьи" class="img-thumbnail" width="100">
                @else
                    Нет изображения
                @endif
            </td>
<td class="d-flex gap-2">
    <!-- Кнопка одобрить -->
    <button 
        onclick="event.preventDefault(); document.getElementById('approve-article-{{ $article->id }}').submit();" 
        class="btn btn-sm btn-success">
        Одобрить
    </button>

    <form id="approve-article-{{ $article->id }}" action="{{ route('admin.user-articles.approve', $article->id) }}" method="POST" style="display: none;">
        @csrf
        @method('PUT')
    </form>

    <!-- Кнопка удалить -->
    <button 
        onclick="event.preventDefault(); if(confirm('Вы уверены?')) document.getElementById('delete-article-{{ $article->id }}').submit();" 
        class="btn btn-sm btn-danger">
        Удалить
    </button>

    <form id="delete-article-{{ $article->id }}" action="{{ route('admin.user-articles.destroy', $article->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</td>


        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">Нет статей для модерации</td> <!-- colspan изменён на 6 -->
        </tr>
    @endforelse
</tbody>

            </table>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .img-thumbnail {
            max-height: 100px;
            object-fit: cover;
        }
        .d-flex {
            display: flex;
        }
        .mr-2 {
            margin-right: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <script>
        // Подтверждение удаления
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