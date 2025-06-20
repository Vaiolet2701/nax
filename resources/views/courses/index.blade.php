@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/cources.css') }}" rel="stylesheet">

<div class="container">
<!-- Верхний блок с фильтрами и сортировкой -->
<div class="d-flex align-items-center mb-3 flex-wrap gap-3">
    <!-- Кнопка открытия модального окна -->
    <button type="button" class="btn custom-filter-btn" data-bs-toggle="modal" data-bs-target="#filterModal">
        Открыть фильтры
    </button>

    <!-- Сортировка -->
<form action="{{ route('courses.index') }}" method="GET" class="filter-form d-flex align-items-center gap-2">
    @foreach(request()->except('sort') as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    <select name="sort" class="custom-filter-select" onchange="this.form.submit()">
        <option value="">Без сортировки</option>
        <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>По названию (А–Я)</option>
        <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>По названию (Я–А)</option>
        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Сначала новые</option>
    </select>
</form>

</div>


   <!-- Модальное окно фильтров -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background-color: #1c2a23; color: #f5f5e9;">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="filterModalLabel">Фильтры курсов</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('courses.index') }}" method="GET" class="filter-form">
                    <div class="d-flex flex-wrap align-items-end gap-3 mb-4">
                        <!-- Период -->
                        <div class="flex-grow-1">
                            <label for="start_date" class="form-label">Период с:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="flex-grow-1">
                            <label for="end_date" class="form-label">по:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        
                        <!-- Количество людей -->
                        <div class="flex-grow-1">
                            <label for="min_people" class="form-label">Мин. людей:</label>
                            <input type="number" name="min_people" id="min_people" class="form-control" value="{{ request('min_people') }}">
                        </div>
                        <div class="flex-grow-1">
                            <label for="max_people" class="form-label">Макс. людей:</label>
                            <input type="number" name="max_people" id="max_people" class="form-control" value="{{ request('max_people') }}">
                        </div>
                        
                        <!-- Категория -->
                        <div class="flex-grow-1">
                            <label for="category_id" class="form-label">Категория:</label>
                            <select name="category_id" id="category_id" class="form-select">
                                <option value="">Все категории</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap align-items-end gap-3 mb-4">
                        <!-- Цена -->
                        <div class="flex-grow-1">
                            <label for="min_price" class="form-label">Мин. цена:</label>
                            <input type="number" name="min_price" id="min_price" class="form-control" value="{{ request('min_price') }}">
                        </div>
                        <div class="flex-grow-1">
                            <label for="max_price" class="form-label">Макс. цена:</label>
                            <input type="number" name="max_price" id="max_price" class="form-control" value="{{ request('max_price') }}">
                        </div>
                        
                        <!-- Кнопки -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Применить</button>
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary">Сбросить</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <h1 class="text-light">Курсы</h1>

    {{-- Отфильтрованные курсы: текущие (не завершённые) --}}
    <div class="courses-grid mb-4">
        @php
            $currentCourses = $courses->filter(fn($course) => \Carbon\Carbon::parse($course->end_date)->gte(now()));
        @endphp

        @if($currentCourses->isEmpty())
            <p class="text-light">Текущих курсов нет.</p>
        @else
            @foreach($currentCourses as $course)
                <a href="{{ route('courses.show', $course->id) }}" class="course-card-link">
                    <div class="course-card">
                        <h2>{{ $course->title }}</h2>
                        <p class="description">{{ $course->description }}</p>
                        @if($course->image_path)
                            <img src="{{ asset($course->image_path) }}" alt="{{ $course->title }}">
                        @endif
                        <p>Период: {{ \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}</p>
                        <p>Количество людей: {{ $course->min_people }} - {{ $course->max_people }}</p>
                        <p>Животные: {{ $course->animals }}</p>
                        <p>Цена: {{ $course->price ? number_format($course->price, 2, '.', ' ') . ' ₽' : 'Не указано' }}</p>
                    </div>
                </a>
            @endforeach
        @endif
    </div>

    {{-- Кнопка и блок для завершённых курсов --}}
    @php
        $completedCourses = $courses->filter(fn($course) => \Carbon\Carbon::parse($course->end_date)->lt(now()));
    @endphp

    @if($completedCourses->isNotEmpty())
        <div class="mb-3 text-center">
            <button class="btn btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#completedCoursesCollapse" aria-expanded="false" aria-controls="completedCoursesCollapse">
                Показать завершённые курсы ({{ $completedCourses->count() }})
            </button>
        </div>

        <div class="collapse" id="completedCoursesCollapse">
            <div class="courses-grid">
                @foreach($completedCourses as $course)
                    <a href="{{ route('courses.show', $course->id) }}" class="course-card-link">
                        <div class="course-card">
                            <h2>{{ $course->title }}</h2>
                            <p class="description">{{ $course->description }}</p>
                            @if($course->image_path)
                                <img src="{{ asset($course->image_path) }}" alt="{{ $course->title }}">
                            @endif
                            <p>Период: {{ \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}</p>
                            <p>Количество людей: {{ $course->min_people }} - {{ $course->max_people }}</p>
                            <p>Животные: {{ $course->animals }}</p>
                            <p>Цена: {{ $course->price ? number_format($course->price, 2, '.', ' ') . ' ₽' : 'Не указано' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
