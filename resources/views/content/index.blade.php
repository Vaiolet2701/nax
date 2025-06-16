@extends('layouts.app')
@section('content')
<link href="{{ asset('css/content.css') }}" rel="stylesheet">

<div class="container all-materials-page">

<h1>Все материалы</h1>

@auth
    <div class="mb-4">
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">Создать статью (админ)</a>
        @elseif(in_array(auth()->user()->laravel_level, ['Средний', 'Продвинутый']))
            <a href="{{ route('articles.create') }}" class="btn btn-primary">Создать статью</a>
        @endif
    </div>
@endauth

    <!-- Фильтр по категориям (только для статей) -->
    <div class="mb-4">
        <label for="categoryFilter">Фильтр по категории:</label>
        <select id="categoryFilter" class="form-select" style="max-width: 300px;">
            <option value="all" selected>Все категории</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Статьи -->
    <div class="row gy-4" id="articlesContainer">
        @foreach($allArticles as $article)
            <div class="col-md-6 article-card" 
                 data-category="{{ $article->category->id ?? '' }}" 
                 data-title="{{ $article->title }}" 
                 data-author="{{ $article->author_name }}" 
                 data-content="{{ $article->content }}" 
                 data-image="{{ $article->image_path ? asset($article->image_path) : '' }}"
                 style="cursor:pointer;"
            >
                <div class="card material-card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">{{ $article->title }}</h3>
                        <p class="text-muted">Категория: {{ $article->category->name ?? 'Без категории' }}</p>
                        <p class="text-muted">Автор: {{ $article->author_name }}</p>
                        @if($article->image_path)
                            <img src="{{ asset($article->image_path) }}" alt="{{ $article->title }}" class="img-fluid mb-3">
                        @endif
                        <p class="card-text text-truncate" style="max-height: 4.5em; overflow: hidden;">{{ $article->content }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Видео без фильтрации -->
    <div class="col-12 mt-5">
        <h2>Видео</h2>
        <div class="row">
            @forelse($videos as $video)
                <div class="col-md-6">
                    <div class="card material-card mb-4">
                        <div class="card-body">
                            <h3 class="card-title">{{ $video->title }}</h3>
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ $video->url }}" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>Нет доступных видео</p>
            @endforelse
        </div>
    </div>

</div>

<!-- Модальное окно -->
<div class="modal fade" id="articleModal" tabindex="-1" aria-labelledby="articleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="articleModalLabel">Заголовок статьи</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body">
        <p><strong>Автор:</strong> <span id="modalAuthor"></span></p>
        <p><strong>Категория:</strong> <span id="modalCategory"></span></p>
        <img id="modalImage" src="" alt="" class="img-fluid mb-3" style="display:none;">
        <div id="modalContent"></div>
      </div>
    </div>
  </div>
</div>


@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterSelect = document.getElementById('categoryFilter');
    const articles = document.querySelectorAll('.article-card');

    // Фильтрация по категориям — только статьи
    filterSelect.addEventListener('change', function () {
        const selectedCategory = this.value;
        articles.forEach(article => {
            if (selectedCategory === 'all' || article.dataset.category === selectedCategory) {
                article.style.display = '';
            } else {
                article.style.display = 'none';
            }
        });
    });

    // Модальное окно
    const modal = new bootstrap.Modal(document.getElementById('articleModal'));
    const modalTitle = document.getElementById('articleModalLabel');
    const modalAuthor = document.getElementById('modalAuthor');
    const modalCategory = document.getElementById('modalCategory');
    const modalContent = document.getElementById('modalContent');
    const modalImage = document.getElementById('modalImage');

    articles.forEach(article => {
        article.addEventListener('click', () => {
            modalTitle.textContent = article.dataset.title;
            modalAuthor.textContent = article.dataset.author;

            const categoryId = article.dataset.category;
            const categoryOption = document.querySelector(`#categoryFilter option[value="${categoryId}"]`);
            modalCategory.textContent = categoryOption ? categoryOption.text : 'Без категории';

            const imgSrc = article.dataset.image;
            if (imgSrc) {
                modalImage.src = imgSrc;
                modalImage.style.display = 'block';
            } else {
                modalImage.style.display = 'none';
            }

            modalContent.innerHTML = article.dataset.content.replace(/\n/g, '<br>');
            modal.show();
        });
    });
});
</script>
@endpush
