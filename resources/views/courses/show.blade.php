@extends('layouts.app')

@section('content')
<div class="container course-page-container">
    <div class="course-card">
        <h1>{{ $course->title }}</h1>

        @php
            $now = \Carbon\Carbon::now();
            $start = \Carbon\Carbon::parse($course->start_date);
            $end = \Carbon\Carbon::parse($course->end_date);
            $isOngoing = $now->between($start, $end);
            $isCompleted = $now->gt($end);
        @endphp

        @if($course->is_repeated)
            <div class="alert alert-info">
                Этот курс повторяется регулярно.
            </div>
        @endif

        @if($isCompleted)
            <div class="alert alert-warning">
                Курс завершён {{ $end->format('d.m.Y') }}.
                @if($course->has_future_repeat)
                    <br>Скоро будет новый набор на этот курс — следите за обновлениями!
                @endif
            </div>
        @elseif($isOngoing)
            <div class="alert alert-info">
                Курс идёт с {{ $start->format('d.m.Y') }} до {{ $end->format('d.m.Y') }}.
                Запись на данный курс закрыта, так как он уже начался.
            </div>
        @else
            <div class="alert alert-success">
                Курс стартует с {{ $start->format('d.m.Y') }} до {{ $end->format('d.m.Y') }}.
                Запись открыта.
            </div>
        @endif

        <div class="row">
            <!-- Основная информация о курсе -->
            <div class="col-md-8">
                @if($course->image_path)
                    <img src="{{ asset($course->image_path) }}" class="img-fluid mb-4" alt="{{ $course->title }}">
                @endif
                <p>{{ $course->description }}</p>
                <div class="course-info-section">
                    <ul>
                        <li><strong>Даты:</strong> {{ $start->format('d.m.Y') }} - {{ $end->format('d.m.Y') }}</li>
                        <li><strong>Количество участников:</strong> {{ $course->min_people }} - {{ $course->max_people }} человек</li>
                        <li><strong>Преподаватель:</strong>
                            @if($course->teacher)
                                <div class="teacher-info mt-2">
                                    @if($course->teacher->image_path)
                                        <img src="{{ asset($course->teacher->image_path) }}" class="rounded-circle me-3" width="50" height="50" alt="{{ $course->teacher->name }}">
                                    @endif
                                    <div>
                                        <h5>{{ $course->teacher->name }}</h5>
                                        @if($course->teacher->work_experience)
                                            <small class="text-muted">Опыт работы: {{ $course->teacher->work_experience }} лет</small>
                                        @endif
                                        @if($course->teacher->bio)
                                            <p>{{ $course->teacher->bio }}</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">Не назначен</span>
                            @endif
                        </li>
                        <li><strong>Животные:</strong> {{ $course->animals }}</li>
                        <li><strong>Цена:</strong> {{ $course->price ? number_format($course->price, 2, '.', ' ') . ' руб.' : 'Бесплатно' }}</li>
                    </ul>
                </div>
            </div>

            <!-- Форма записи на курс (показываем только если курс не завершен) -->
            @if(!$isCompleted)
                <div class="col-md-4">
                    <div class="enrollment-card">
                        <h5>Записаться на курс</h5>

                        @if($isOngoing)
                            <div class="alert alert-secondary">
                                Запись на данный курс закрыта.
                            </div>
                        @else
                            <form id="enrollmentForm" action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                
                                @auth
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                @endauth

                                <!-- Имя -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Ваше имя *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                        value="{{ auth()->check() ? auth()->user()->name : '' }}" required>
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Ваш email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                        value="{{ auth()->check() ? auth()->user()->email : '' }}" required>
                                </div>

                                <!-- Телефон -->
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Ваш телефон *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                        value="{{ auth()->check() ? (auth()->user()->phone ?? '') : '' }}" required>
                                </div>

                                <!-- Возраст -->
                                <div class="mb-3">
                                    <label for="age" class="form-label">Ваш возраст *</label>
                                    <input type="number" class="form-control" id="age" name="age" 
                                        value="{{ auth()->check() ? (auth()->user()->age ?? '') : '' }}" 
                                        min="12" max="100" required>
                                </div>

                                <!-- Посещали ли курсы ранее -->
                                <div class="mb-3">
                                    <label class="form-label">Посещали ли вы наши курсы ранее? *</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="attended_previous_courses" id="attended_yes" value="1">
                                            <label class="form-check-label" for="attended_yes">Да</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="attended_previous_courses" id="attended_no" value="0" checked>
                                            <label class="form-check-label" for="attended_no">Нет</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Сообщение -->
                                <div class="mb-3">
                                    <label for="message" class="form-label">Дополнительная информация</label>
                                    <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                                </div>

                                <!-- Кнопка отправки -->
                                <button type="submit" class="btn btn-primary w-100">Записаться</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Отзывы и форма отзыва (если курс завершен) -->
        <div class="row mt-5">
            <div class="col-12">
                @if($course->reviews->count())
                    <h2>Отзывы о курсе</h2>
                    @foreach($course->reviews as $review)
                        <div class="review-entry mb-3">
                            <h4>{{ $review->title }}</h4>
                            <p>{{ Str::limit($review->content, 150) }}</p>
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                                @endfor
                            </div>
                            <p class="text-muted">
                                Автор: {{ $review->user->name ?? 'Аноним' }}<br>
                                Дата: {{ $review->created_at->format('d.m.Y') }}
                            </p>
                        </div>
                    @endforeach
                @else
                    <p>Пока нет отзывов о курсе.</p>
                @endif

                <!-- Форма отзыва (показываем только если курс завершен и пользователь авторизован) -->
@if($isCompleted)
    @auth
        @if($course->canUserReview(auth()->user()))
            @if(!$course->reviews()->where('user_id', auth()->id())->exists())
                <div class="mt-5">
                    <h3>Оставить отзыв</h3>
                    <form action="{{ route('courses.reviews.store', $course) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="review_title" class="form-label">Заголовок отзыва *</label>
                            <input type="text" class="form-control" id="review_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="review_content" class="form-label">Содержание отзыва *</label>
                            <textarea class="form-control" id="review_content" name="content" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Оценка *</label>
                            <div class="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ $i == 5 ? 'checked' : '' }}>
                                    <label for="star{{ $i }}">★</label>
                                @endfor
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Отправить отзыв</button>
                    </form>
                </div>
            @else
                <div class="alert alert-info mt-4">
                    Вы уже оставили отзыв на этот курс.
                </div>
            @endif
        @else
            <div class="alert alert-warning mt-4">
                Вы не можете оставить отзыв, так как не завершили этот курс.
            </div>
        @endif
    @else
        <div class="alert alert-info mt-4">
            <a href="{{ route('login') }}">Войдите</a>, чтобы оставить отзыв (только для участников курса).
        </div>
    @endauth
@endif
            </div>
        </div>
    </div>
</div>

<style>
.review-entry {
    background-color: #1f1f1f;
    border: 1px solid #333;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0, 128, 0, 0.1);
    transition: transform 0.2s ease;
}

.review-entry:hover {
    transform: scale(1.02);
}

.review-entry h4 {
    color: #00ff88;
    margin-bottom: 0.5rem;
}

.review-entry p {
    color: #ddd;
}

.review-entry .rating {
    margin: 0.5rem 0;
}

.review-entry .star {
    font-size: 1.2rem;
    color: #555;
    transition: color 0.3s ease;
}

.review-entry .star.filled {
    color: #ffc107; /* золотой цвет для заполненной звезды */
}

.review-entry .text-muted {
    font-size: 0.875rem;
    color: #999;
}

.rating-input {
    display: flex;
    direction: rtl;
    unicode-bidi: bidi-override;
}

.rating-input input {
    display: none;
}

.rating-input label {
    font-size: 2rem;
    color: #555;
    cursor: pointer;
    transition: color 0.3s;
}

.rating-input input:checked ~ label,
.rating-input label:hover,
.rating-input label:hover ~ label {
    color: #ffc107;
}

.rating-input input:checked + label:hover,
.rating-input input:checked ~ label:hover,
.rating-input label:hover ~ input:checked ~ label,
.rating-input input:checked ~ label:hover ~ label {
    color: #ffc107;
}
</style>
@endsection