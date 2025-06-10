@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@section('content')

<div class="container profile-page">
    <div class="profile-section">
        <h1>Профиль пользователя</h1>

        <!-- Форма для обновления данных -->
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Имя:</label>
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" 
                               value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Телефон:</label>
                        <input type="text" name="phone" id="phone" class="form-control" 
                               value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="form-group">
                        <label for="address">Адрес:</label>
                        <input type="text" name="address" id="address" class="form-control" 
                               value="{{ old('address', $user->address) }}">
                    </div>
                
                    <div class="form-group">
                        <label>Уровень:</label>
                        <input type="text" class="form-control" 
                               value="{{ ucfirst($user->laravel_level) }}">
                    </div>
               
                    @if($user->canHaveProfileFields())
                        <div class="form-group">
                            <label>Возраст</label>
                            <input type="number" name="age" class="form-control"
                                   value="{{ old('age', $user->age) }}">
                        </div>
                        
                        <div class="form-group">
                            <label>Опыт работы (лет)</label>
                            <input type="number" name="work_experience" class="form-control"
                                   value="{{ old('work_experience', $user->work_experience) }}">
                        </div>
                        
                        <div class="form-group">
                            <label>Биография</label>
                            <textarea name="bio" class="form-control">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Обновить данные</button>
        </form>
        
        @if($user->role === 'user')
            <div class="mt-5">

                <!-- Завершённые курсы -->
                <section class="section-block">
                    <h2>Завершённые курсы</h2>
                    <div class="card-grid" id="completedCoursesGrid">
                        @foreach($completedCourses ?? [] as $index => $course)
                            <div class="card course-card {{ $index >= 6 ? 'd-none' : '' }}">
                                <h5>{{ $course->title }}</h5>
                                <p>{{ Str::limit($course->description, 100) }}</p>
                                <p class="text-muted">
                                    Завершен: {{ \Carbon\Carbon::parse($course->pivot->completed_at)->format('d.m.Y') }}
                                </p>

                                <!-- Кнопка для показа формы -->
                                <button class="btn btn-outline-success mb-2" type="button" onclick="toggleReviewForm({{ $course->id }})">
                                    Оставить отзыв
                                </button>

                                <!-- Скрытая форма отзыва -->
                                <div id="review-form-{{ $course->id }}" class="review-form bg-dark text-white border p-3 rounded d-none">
                                    <form action="{{ route('course-reviews.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <div class="mb-2">
                                            <label for="title_{{ $course->id }}">Заголовок отзыва</label>
                                            <input type="text" name="title" id="title_{{ $course->id }}" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label for="content_{{ $course->id }}">Текст отзыва</label>
                                            <textarea name="content" id="content_{{ $course->id }}" class="form-control" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label for="rating_{{ $course->id }}">Оценка</label>
                                            <select name="rating" id="rating_{{ $course->id }}" class="form-control" required>
                                                @for($i = 5; $i >= 1; $i--)
                                                    <option value="{{ $i }}">{{ $i }} ★</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success">Отправить</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if(($completedCourses->count() ?? 0) > 6)
                        <button class="btn btn-link expand-btn" data-target="completedCoursesGrid">Показать все</button>
                    @endif
                </section>

                <!-- Отклонённые заявки -->
                <section class="section-block mt-4">
                    <h2>Отклонённые заявки на курсы</h2>
                    <div class="card-grid" id="rejectedCoursesGrid">
                        @foreach($rejectedCourses ?? [] as $index => $course)
                            <div class="card rejected-course-card {{ $index >= 6 ? 'd-none' : '' }}">
                                <h5 class="text-danger">{{ $course->title }} (Отклонено)</h5>
                                <p>{{ Str::limit($course->description, 100) }}</p>
                                <p class="text-danger">
                                    <strong>Причина отказа:</strong> 
                                    {{ $course->pivot->rejection_reason ?? 'Причина не указана' }}
                                </p>
                                <p class="text-muted">
                                    Дата подачи: {{ \Carbon\Carbon::parse($course->pivot->created_at)->format('d.m.Y') }}
                                </p>
                                <p class="text-muted">
                                    Дата отказа: {{ \Carbon\Carbon::parse($course->pivot->updated_at)->format('d.m.Y') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    @if(($rejectedCourses->count() ?? 0) > 6)
                        <button class="btn btn-link expand-btn" data-target="rejectedCoursesGrid">Показать все</button>
                    @endif
                </section>

                <!-- Отзывы -->
                <section class="section-block mt-5">
                    <h2>Мои отзывы</h2>
                    <div class="card-grid" id="reviewsGrid">
                        @foreach($reviews ?? [] as $index => $review)
                            <div class="card review-card {{ $index >= 6 ? 'd-none' : '' }}">
                                <h4>{{ $review->title }}</h4>
                                <p>{{ Str::limit($review->content, 150) }}</p>
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if(($reviews->count() ?? 0) > 6)
                        <button class="btn btn-link expand-btn" data-target="reviewsGrid">Показать все</button>
                    @endif
                </section>

                <!-- Статьи -->
                <section class="section-block mt-5">
                    <h2>Мои статьи</h2>
                    <div class="card-grid" id="articlesGrid">
                        @foreach($articles ?? [] as $index => $article)
                            <div class="card article-card {{ $index >= 3 ? 'd-none' : '' }}">
                                <h4>{{ $article->title }}</h4>
                                <p>{{ Str::limit($article->content, 150) }}</p>
                                <span class="badge badge-{{ $article->is_approved ? 'success' : 'warning' }}">
                                    {{ $article->is_approved ? 'Одобрено' : 'На модерации' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    @if(($articles->count() ?? 0) > 3)
                        <button class="btn btn-link expand-btn" data-target="articlesGrid">Показать все</button>
                    @endif
                </section>

            </div>



        @elseif($user->role === 'teacher')
            <!-- Курсы преподавателя -->
            <div class="mt-5">
                <h2>Мои курсы</h2>
                
                <h3>Активные курсы</h3>
                @forelse($activeCourses ?? [] as $course)
                    <div class="course-entry mb-3">
                        <h5>{{ $course->title }}</h5>
                        <p>Студентов: {{ $course->users->count() }}</p>
                        <p class="text-muted">
                            До {{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}
                        </p>
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-primary">
                           Страница курса
                        </a>
                    </div>
                @empty
                    <p>У вас нет активных курсов.</p>
                @endforelse
            </div>
        @endif
    </div>
</div>
<!-- Модальное окно для подробностей -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="detailsModalLabel">Детали</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body">
        <!-- Здесь будет динамический контент -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Функция открытия модалки с содержимым карточки
    function openDetailsModal(title, fullText, extraHtml = '') {
        const modalTitle = document.getElementById('detailsModalLabel');
        const modalBody = document.querySelector('#detailsModal .modal-body');

        modalTitle.textContent = title;
        modalBody.innerHTML = fullText + extraHtml;

        const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
        modal.show();
    }

    // Привязываем обработчики клика к карточкам
    document.querySelectorAll('.course-card, .review-card, .article-card').forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', () => {
            const title = card.querySelector('h4, h5').textContent.trim();
            let fullText = '';

            // Для курсов и статей у вас есть описание или контент
            if (card.classList.contains('course-card')) {
                fullText = card.querySelector('p').textContent.trim();
            } else if (card.classList.contains('review-card')) {
                fullText = card.querySelector('p').textContent.trim();
                // Можно добавить рейтинг звезд внутри модалки
                const ratingHtml = card.querySelector('.rating').outerHTML;
                openDetailsModal(title, fullText, ratingHtml);
                return;
            } else if (card.classList.contains('article-card')) {
                fullText = card.querySelector('p').textContent.trim();
                const badge = card.querySelector('.badge').outerHTML;
                openDetailsModal(title, fullText, `<div class="mt-2">${badge}</div>`);
                return;
            }

            openDetailsModal(title, fullText);
        });
    });
});

</script>

<script>
    function toggleReviewForm(courseId) {
        const form = document.getElementById('review-form-' + courseId);
        if (form.classList.contains('d-none')) {
            form.classList.remove('d-none');
        } else {
            form.classList.add('d-none');
        }
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.expand-btn').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const container = document.getElementById(targetId);
            if (!container) return;

            // Находим все карточки с классом d-none внутри контейнера
            const hiddenCards = container.querySelectorAll('.d-none');

            // Если есть скрытые карточки — показываем их, иначе прячем все кроме первых 6
            if (hiddenCards.length > 0) {
                hiddenCards.forEach(card => card.classList.remove('d-none'));
                button.textContent = 'Свернуть';
            } else {
                // Свернуть обратно, оставив видимыми первые 6 (или 3 для статей)
                const cards = container.querySelectorAll('.card');
                cards.forEach((card, index) => {
                    if (targetId === 'articlesGrid') {
                        card.classList.toggle('d-none', index >= 3);
                    } else {
                        card.classList.toggle('d-none', index >= 6);
                    }
                });
                button.textContent = 'Показать все';
            }
        });
    });
});

</script>
