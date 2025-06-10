<link rel="stylesheet" href="{{ asset('css/form-styles.css') }}">

<form action="{{ route('promotions.enroll', $promotion) }}" method="POST" id="promotionEnrollmentForm">
    @csrf
        <input type="hidden" name="promotion_id" value="{{ $promotion->id }}">

        <div class="mb-4">
            <h3>Запись по акции: <strong>{{ $promotion->title }}</strong></h3>
            <p class="text-muted">Скидка: {{ $promotion->discount }}%</p>
        </div>

        <div class="mb-3">
            <label for="course_id" class="form-label">Выберите курс *</label>
            <select name="course_id" id="course_id" class="form-control" required>
                <option value="">-- Выберите курс --</option>
                @forelse($availableCourses as $availableCourse)
                    <option value="{{ $availableCourse->id }}" 
                            data-price="{{ $availableCourse->price }}"
                            data-discount="{{ $promotion->discount }}">
                        {{ $availableCourse->title }}
                        {{ \Carbon\Carbon::parse($availableCourse->start_date)->format('d.m.Y') }} - 
                        {{ \Carbon\Carbon::parse($availableCourse->end_date)->format('d.m.Y') }}                        
                        @if($availableCourse->price)
                            {{ number_format($availableCourse->price * (1 - $promotion->discount / 100), 2, '.', ' ') }} руб.
                            <small>(скидка {{ $promotion->discount }}%)</small>
                        @else
                            Бесплатно
                        @endif
                    </option>
                @empty
                    <option value="" disabled>Нет доступных курсов</option>
                @endforelse
            </select>
        </div>

        @if($availableCourses->isEmpty())
            <div class="alert alert-warning">Нет доступных курсов для этой акции.</div>
        @endif

        <!-- Личные данные -->
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

        <div class="mb-3">
            <label for="name" class="form-label">Ваше имя *</label>
            <input type="text" name="name" id="name" class="form-control" 
                   value="{{ auth()->user()->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Ваш email *</label>
            <input type="email" name="email" id="email" class="form-control" 
                   value="{{ auth()->user()->email }}" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Ваш телефон *</label>
            <input type="tel" name="phone" id="phone" class="form-control" 
                   value="{{ auth()->user()->phone ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label for="age" class="form-label">Ваш возраст *</label>
            <input type="number" name="age" id="age" class="form-control" 
                   value="{{ auth()->user()->age ?? '' }}" required min="12" max="100">
        </div>

        <div class="mb-3">
            <label for="attended_previous_courses" class="form-label">Посещали ли вы курсы ранее? *</label>
            <select name="attended_previous_courses" id="attended_previous_courses" class="form-control" required>
                <option value="1">Да</option>
                <option value="0" selected>Нет</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Дополнительное сообщение</label>
            <textarea name="message" id="message" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success w-100">Отправить заявку</button>
    </form>

