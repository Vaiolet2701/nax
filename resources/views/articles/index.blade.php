<div class="container">
    <h1>Статьи</h1>
    @foreach($articles as $article)
        <div class="card mb-3">
            <div class="card-body">
                <h2>{{ $article->title }}</h2>
                @if($article->image_path)
                    <img src="{{ asset($article->image_path) }}" alt="{{ $article->title }}" class="img-fluid mb-3">
                @endif
                <p>{{ $article->content }}</p>
                <p>Автор: {{ optional($article->user)->name ?? 'Неизвестный автор' }}</p>
            </div>
        </div>
    @endforeach
</div>