@auth
<link rel="stylesheet" href="{{ asset('css/form-styles.css') }}">

<form id="enrollmentForm" action="{{ route('courses.enroll', $course) }}" method="POST">
    @csrf
    <input type="hidden" name="course_id" value="{{ $course->id }}">
    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

    <div class="mb-4">
        <h3>Записаться на курс: <strong><br>{{ $course->title }}</strong></h3>
        <p class="text-muted">С {{ $course->start_date }} по {{ $course->end_date }}</p>
    </div>

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

    <button type="submit" class="btn btn-primary w-100">Записаться</button>
</form>

<script>
document.getElementById('enrollmentForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            form.reset();
        } else {
            alert(data.message || 'Ошибка при записи');
        }
    } catch (error) {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при отправке формы');
    }
});
</script>
@endauth
