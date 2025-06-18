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
                            value="{{ ucfirst($user->laravel_level) }}"
                            readonly
                            onselectstart="return false"
                            onmousedown="return false"
                            style="background-color: #222; color: #ccc; user-select: none;">
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
                <!-- Результаты теста выживания -->
                <section class="section-block mt-5">
                    <h2>Результаты теста выживания</h2>
                    
                    @if($survivalTestResults->count() > 0)
                        <div class="card-grid" id="testResultsGrid">
                            @foreach($survivalTestResults ?? [] as $index => $result)
                                <div class="card test-result-card {{ $index >= 3 ? 'd-none' : '' }}">
                                    <div class="card-header bg-dark">
                                        <h5 class="mb-0">Тест от {{ $result->created_at->format('d.m.Y') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="progress mb-3">
                                            <div class="progress-bar bg-{{ $result->percentage >= 80 ? 'success' : ($result->percentage >= 50 ? 'warning' : 'danger') }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $result->percentage }}%" 
                                                 aria-valuenow="{{ $result->percentage }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ $result->percentage }}%
                                            </div>
                                        </div>
                                        <p class="mb-1"><strong>Баллы:</strong> {{ $result->score }}/{{ $result->total_questions }}</p>
                                        <p class="mb-1"><strong>Результат:</strong> 
                                            <span class="badge badge-{{ $result->percentage >= 80 ? 'success' : ($result->percentage >= 50 ? 'warning' : 'danger') }}">
                                                {{ $result->percentage >= 80 ? 'Отлично' : ($result->percentage >= 50 ? 'Удовлетворительно' : 'Неудовлетворительно') }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if(($survivalTestResults->count() ?? 0) > 3)
                            <button class="btn btn-link expand-btn" data-target="testResultsGrid">Показать все</button>
                        @endif
                    @else
                        <div class="alert alert-info">
                            Вы еще не проходили тест выживания.
                        </div>
                    @endif
                    
                    <a href="{{ route('survival.test') }}" class="btn btn-primary mt-3">
                        Пройти тест выживания
                    </a>
                </section>
<!-- Курсы в ожидании -->
<section class="section-block">
    <h2>Курсы в ожидании</h2>
    <div class="card-grid" id="pendingCoursesGrid">
        @foreach($pendingCourses ?? [] as $index => $course)
            <div class="card course-card {{ $index >= 3 ? 'd-none' : '' }}">
                <h5>{{ $course->title }}</h5>
                <p>{{ Str::limit($course->description, 100) }}</p>
                <p class="text-warning">
                    <i class="fas fa-clock"></i> На рассмотрении
                </p>
                <p class="text-muted">
                    Дата подачи: {{ $course->pivot->created_at->format('d.m.Y') }}
                </p>
            </div>
        @endforeach
    </div>
    @if(($pendingCourses->count() ?? 0) > 3)
        <button class="btn btn-link expand-btn" data-target="pendingCoursesGrid">Показать все</button>
    @endif
</section>

<!-- Курсы в процессе -->
<section class="section-block">
    <h2>Курсы в процессе</h2>
    <div class="card-grid" id="inProgressCoursesGrid">
        @foreach($coursesInProgress ?? [] as $index => $course)
            <div class="card course-card {{ $index >= 3 ? 'd-none' : '' }}">
                <h5>{{ $course->title }}</h5>
                <p>{{ Str::limit($course->description, 100) }}</p>

                <p class="text-info">
                    <i class="fas fa-spinner"></i> В процессе
                </p>

            </div>
        @endforeach
    </div>
    @if(($coursesInProgress->count() ?? 0) > 3)
        <button class="btn btn-link expand-btn" data-target="inProgressCoursesGrid">Показать все</button>
    @endif
</section>

                    </section>
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
                                <button class="btn btn-outline-success mb-2" type="button" 
                                        onclick="toggleReviewForm({{ $course->id }})">
                                    {{ $course->reviews()->where('user_id', auth()->id())->exists() ? 'Изменить отзыв' : 'Оставить отзыв' }}
                                </button>
                            </div>
                            
                            <!-- Форма отзыва (под карточкой) -->
                            <div id="review-form-container-{{ $course->id }}" class="col-12 d-none">
                                <div class="review-form bg-dark text-white border p-3 rounded mb-4">
                                    @if($course->reviews()->where('user_id', auth()->id())->exists())
                                        <div class="alert alert-info mb-3">
                                            Вы уже оставляли отзыв на этот курс. Отправка формы обновит ваш отзыв.
                                        </div>
                                    @endif
                                    
                                    <form action="{{ route('course-reviews.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        
                                        <div class="mb-3">
                                            <label for="title_{{ $course->id }}" class="form-label">Заголовок отзыва *</label>
                                            <input type="text" name="title" id="title_{{ $course->id }}" class="form-control" 
                                                   value="{{ optional($course->reviews()->where('user_id', auth()->id())->first())->title }}" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="content_{{ $course->id }}" class="form-label">Текст отзыва *</label>
                                            <textarea name="content" id="content_{{ $course->id }}" class="form-control" rows="5" required>
                                                {{ optional($course->reviews()->where('user_id', auth()->id())->first())->content }}
                                            </textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Оценка *</label>
                                            <div class="rating-input">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <input type="radio" id="star{{ $i }}_{{ $course->id }}" name="rating" 
                                                           value="{{ $i }}" 
                                                           {{ optional($course->reviews()->where('user_id', auth()->id())->first())->rating == $i ? 'checked' : '' }}>
                                                    <label for="star{{ $i }}_{{ $course->id }}">★</label>
                                                @endfor
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between">
                                            <button type="submit" class="btn btn-success">
                                                {{ $course->reviews()->where('user_id', auth()->id())->exists() ? 'Обновить отзыв' : 'Отправить отзыв' }}
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" 
                                                    onclick="toggleReviewForm({{ $course->id }})">
                                                Отмена
                                            </button>
                                        </div>
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

                @if($user->role === 'user')
                    <!-- Секция походов -->
                    <section class="section-block mt-5">
                        <h2>@if($is_advanced)Походы на модерации@elseМои походы@endif</h2>
                        
                        @if($is_advanced)
                            <!-- Для продвинутого пользователя -->
                           <div class="card-grid">
    @foreach($hikes_for_review as $hike)
        @foreach($hike->participants as $participant)
            <div class="card hike-card mb-4">
                <!-- Информация о походе -->
                <div class="card-header bg-light">
                    <h5>{{ $hike->title }}</h5>
                    <p class="mb-1">{{ Str::limit($hike->description, 80) }}</p>
                    <small class="text-muted">
                        Дата: {{ $hike->start_date?->format('d.m.Y') }} - {{ $hike->end_date?->format('d.m.Y') }}
                    </small>
                </div>
                
                <!-- Данные участника -->
                <div class="card-body">
                    <div class="participant-info">
                        <h6 class="text-primary">Данные участника:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Имя:</strong> {{ $participant->name }}</p>
                                <p><strong>Возраст:</strong> {{ $participant->age }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Телефон:</strong> {{ $participant->phone }}</p>
                                <p><strong>Статус:</strong> 
                                    <span class="badge bg-{{ 
                                        $participant->pivot->status === 'approved' ? 'success' : 
                                        ($participant->pivot->status === 'rejected' ? 'danger' : 'warning') 
                                    }}">
                                        {{ $participant->pivot->status === 'approved' ? 'Одобрено' : 
                                           ($participant->pivot->status === 'rejected' ? 'Отклонено' : 'На рассмотрении') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        @if($participant->pivot->notes)
                            <div class="notes-section mt-2">
                                <p class="mb-1"><strong>Примечания:</strong></p>
                                <p class="text-muted small">{{ $participant->pivot->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Кнопки управления -->
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <form action="{{ route('trips.approve', ['trip' => $hike->id, 'user' => $participant->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Одобрить
                            </button>
                        </form>
                        
                        <form action="{{ route('trips.reject', ['trip' => $hike->id, 'user' => $participant->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i> Отклонить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
</div>
                        @else
                            <!-- Для обычных пользователей -->
                            <div class="card-grid">
                                @foreach($user_hikes as $hike)
                                    <div class="card hike-card">
                                        <h5>{{ $hike->title }}</h5>
                                        <p>{{ Str::limit($hike->description, 100) }}</p>
                                        <p class="text-{{ 
                                            $hike->status === 'approved' ? 'success' : 
                                            ($hike->status === 'rejected' ? 'danger' : 'warning') 
                                        }}">
                                            Статус: {{ 
                                                $hike->status === 'approved' ? 'Одобрено' : 
                                                ($hike->status === 'rejected' ? 'Отклонено' : 'На рассмотрении') 
                                            }}
                                        </p>
                                        <p class="text-muted">
                                            Дата: {{ $hike->start_date ? $hike->start_date->format('d.m.Y') : '' }} - 
                                                 {{ $hike->end_date ? $hike->end_date->format('d.m.Y') : '' }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>
                @endif
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
<style>
/* Обновленные стили для карточек */
.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.card {
    background: #252525;
    border: 1px solid #333;
    border-radius: 10px;
    padding: 1.25rem;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    border-color: #2ecc71;
}

.card h4, .card h5 {
    color: #50fa7b;
    margin-bottom: 0.75rem;
    font-size: 1.1rem;
}

.card p {
    color: #ccc;
    margin-bottom: 1rem;
    flex-grow: 1;
    font-size: 0.95rem;
    line-height: 1.4;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.card .badge {
    align-self: flex-start;
    margin-top: auto;
}

/* Стили для кнопки "Показать все" */
.expand-btn {
    background: none;
    border: none;
    color: #50fa7b;
    font-weight: 600;
    padding: 0.5rem 1rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    border-radius: 6px;
}

.expand-btn:hover {
    background: rgba(80, 250, 123, 0.1);
    color: #2ecc71;
    text-decoration: none;
}

.expand-btn:after {
    content: '▼';
    font-size: 0.8rem;
    margin-left: 0.5rem;
    transition: transform 0.3s ease;
}

.expand-btn.expanded:after {
    content: '▲';
}

/* Стили для разных типов карточек */
.test-result-card {
    border-left: 4px solid #3498db;
}

.rejected-course-card {
    border-left: 4px solid #e74c3c;
}

.course-card {
    border-left: 4px solid #9b59b6;
}

.review-card {
    border-left: 4px solid #f1c40f;
}

.article-card {
    border-left: 4px solid #1abc9c;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для кнопок "Показать все"
    document.querySelectorAll('.expand-btn').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const container = document.getElementById(targetId);
            if (!container) return;
            
            const hiddenCards = container.querySelectorAll('.d-none');
            const isExpanded = this.classList.contains('expanded');
            
            if (isExpanded) {
                // Сворачиваем - скрываем все кроме первых 3/6 карточек
                const cards = container.querySelectorAll('.card');
                const showCount = targetId.includes('articlesGrid') || 
                                 targetId.includes('testResultsGrid') || 
                                 targetId.includes('pendingCoursesGrid') || 
                                 targetId.includes('inProgressCoursesGrid') ? 3 : 6;
                
                cards.forEach((card, index) => {
                    if (index >= showCount) {
                        card.classList.add('d-none');
                    }
                });
                
                this.classList.remove('expanded');
                this.textContent = 'Показать все';
            } else {
                // Разворачиваем - показываем все карточки
                hiddenCards.forEach(card => {
                    card.classList.remove('d-none');
                });
                
                this.classList.add('expanded');
                this.textContent = 'Свернуть';
            }
        });
    });
});
</script>
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
    const formContainer = document.getElementById(`review-form-container-${courseId}`);
    const button = document.querySelector(`.course-card button[onclick="toggleReviewForm(${courseId})"]`);
    
    if (formContainer.classList.contains('d-none')) {
        formContainer.classList.remove('d-none');
        button.textContent = 'Скрыть форму';
        formContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        formContainer.classList.add('d-none');
        button.textContent = button.textContent.includes('Изменить') ? 'Изменить отзыв' : 'Оставить отзыв';
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
@endpush