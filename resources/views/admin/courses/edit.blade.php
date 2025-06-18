@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактирование курса</h1>

        <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title">Название курса</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $course->title }}" required>
            </div>
            
            <div class="form-group">
                <label for="description">Описание курса</label>
                <textarea name="description" id="description" class="form-control" required>{{ $course->description }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="course_category_id">Категория</label>
                <select name="course_category_id" id="course_category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $course->course_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="start_date">Период с</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $course->start_date }}">
            </div>
            
            <div class="form-group">
                <label for="end_date">Период по</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $course->end_date }}">
            </div>
            
            <div class="form-group form-check mt-3">
                <input type="checkbox" name="is_repeated" id="is_repeated" class="form-check-input" value="1" {{ $course->is_repeated ? 'checked' : '' }}>
                <label for="is_repeated" class="form-check-label">Курс будет повторяться</label>
            </div>
            
            <div class="form-group">
                <label for="image">Изображение</label>
                <input type="file" name="image" id="image" class="form-control">
                @if($course->image_path)
                    <img src="{{ asset($course->image_path) }}" alt="Course Image" width="100">
                @endif
            </div>
            
            <div class="form-group">
                <label for="min_people">Минимальное количество людей</label>
                <input type="number" name="min_people" id="min_people" class="form-control" value="{{ $course->min_people }}">
            </div>
            
            <div class="form-group">
                <label for="max_people">Максимальное количество людей</label>
                <input type="number" name="max_people" id="max_people" class="form-control" value="{{ $course->max_people }}">
            </div>
            
            <div class="form-group">
                <label for="animals">Животные</label>
                <textarea name="animals" id="animals" class="form-control">{{ $course->animals }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Цена курса (руб.)</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $course->price ?? '') }}" step="0.01" min="0">
            </div>
            
            <div class="form-group">
                <label for="teacher_id">Текущий преподаватель</label>
                <select name="teacher_id" id="teacher_id" class="form-control">
                    <option value="">-- Не назначено --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" 
                            {{ $course->teacher_id == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                            @if($teacher->work_experience)
                                (Опыт: {{ $teacher->work_experience }} лет)
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">Обновить</button>
                
                @if($course->is_repeated)
                    <button type="button" class="btn btn-warning" onclick="repeatCourse()">
                        Создать повтор курса
                    </button>
                @endif
            </div>
        </form>
    </div>

    <script>
    function repeatCourse() {
        if (confirm('Вы уверены, что хотите создать повтор этого курса?')) {
            fetch('{{ route("admin.courses.repeat", $course->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Ошибка при создании повторного курса');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка');
            });
        }
    }
    </script>
@endsection