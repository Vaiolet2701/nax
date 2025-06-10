@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создание курса</h1>
        <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Название курса</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Описание курса</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="course_category_id">Категория</label>
                <select name="course_category_id" id="course_category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <a href="{{ route('admin.course-categories.create') }}" class="btn btn-sm btn-success mt-2">Добавить категорию</a>
            </div>
            <div class="form-group form-check mt-3">
                <input type="checkbox" name="is_repeated" id="is_repeated" class="form-check-input" value="1" {{ old('is_repeated') ? 'checked' : '' }}>
                <label for="is_repeated" class="form-check-label">Курс будет повторяться</label>
            </div>
            <div class="form-group">
                <label for="start_date">Период с</label>
                <input type="date" name="start_date" id="start_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="end_date">Период по</label>
                <input type="date" name="end_date" id="end_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="image">Изображение</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <div class="form-group">
                <label for="min_people">Минимальное количество людей</label>
                <input type="number" name="min_people" id="min_people" class="form-control">
            </div>
            <div class="form-group">
                <label for="max_people">Максимальное количество людей</label>
                <input type="number" name="max_people" id="max_people" class="form-control">
            </div>
            <div class="form-group">
                <label>Назначить преподавателя</label>
                <div class="row">
                    <div class="col-md-6">
                        <select name="teacher_id" id="teacher_id" class="form-control">
                            <option value="">-- Не назначено --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#inviteTeacherModal">
                            Пригласить нового преподавателя
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Модальное окно для приглашения -->
            <div class="modal fade" id="inviteTeacherModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Пригласить преподавателя</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Email преподавателя</label>
                                <input type="email" id="teacher-email" class="form-control" placeholder="Введите email">
                            </div>
                            <div class="form-group mt-3">
                                <label>Сообщение</label>
                                <textarea id="invitation-message" class="form-control" rows="3" 
                                          placeholder="Напишите сообщение для преподавателя"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="button" class="btn btn-primary" id="send-invitation">Отправить приглашение</button>
                        </div>
                    </div>
                </div>
            </div>
            
            @push('scripts')
   
            @endpush
            </div>
            <div class="form-group">
                <label for="price">Цена курса (руб.)</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $course->price ?? '') }}" step="0.01" min="0">
            </div>
            <button type="submit" class="btn btn-primary">Создать</button>
        </form>

      
        <h1>Создание категории</h1>
        <form action="{{ route('admin.course-categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Название категории</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Создать</button>
        </form>

    </div>
@endsection
<script>
    document.getElementById('send-invitation').addEventListener('click', function() {
        const email = document.getElementById('teacher-email').value;
        const message = document.getElementById('invitation-message').value;
        
        fetch('{{ route("admin.courses.invite-teacher") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                email: email,
                message: message,
                course_id: {{ $course->id ?? 'null' }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Приглашение отправлено!');
                $('#inviteTeacherModal').modal('hide');
            } else {
                alert(data.message);
            }
        });
    });
    </script>