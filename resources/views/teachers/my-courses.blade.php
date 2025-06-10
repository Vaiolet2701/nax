@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Мои курсы</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @foreach($courses as $course)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->title }}</h5>
                        <p class="card-text">{{ $course->description }}</p>
                        
                        <h6>Участники ({{ $course->users->count() }}):</h6>
<ul class="list-group mb-3">
    @forelse($course->users as $user)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ $user->name }} ({{ $user->email }})
            <span class="badge bg-secondary me-2">
                {{ $user->pivot->status }}
            </span>
            <!-- Ссылка на страницу посещаемости -->
            <a href="{{ route('teachers.attendance', ['courseUser' => $user->pivot->id]) }}" class="btn btn-sm btn-primary">
                Отметить посещаемость
            </a>
        </li>
    @empty
        <li class="list-group-item">Нет участников</li>
    @endforelse
</ul>

    
                        <form action="{{ route('teachers.leave-course', $course->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите покинуть этот курс?')">
                                Покинуть курс
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection