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
                        <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                        
                        <h6>Участники ({{ $course->users->count() }}):</h6>
                        <ul class="list-group mb-3">
                            @forelse($course->users as $user)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong>{{ $user->name }}</strong><br>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <span class="badge bg-{{ 
                                            $user->pivot->status === 'approved' ? 'success' : 
                                            ($user->pivot->status === 'pending' ? 'warning' : 'danger') 
                                        }}">
                                            {{ $user->pivot->status }}
                                        </span>
                                    </div>
                                    
                                    <!-- Форма отметки посещаемости -->
                                 <form method="POST" action="{{ route('teachers.attendance.quick-update') }}" class="attendance-form">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <input type="hidden" name="course_id" value="{{ $course->id }}">
    
    <select name="attended" class="form-select form-select-sm">
        <option value="1" @selected($user->pivot->attended == 1)>Присутствовал</option>
        <option value="0" @selected($user->pivot->attended == 0)>Отсутствовал</option>
    </select>
</form>
                                </li>
                            @empty
                                <li class="list-group-item">Нет участников</li>
                            @endforelse
                        </ul>

                        <form action="{{ route('teachers.leave-course', $course->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">
                                Покинуть курс
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
// Автоматическая отправка формы при изменении
document.querySelectorAll('select[name="attended"]').forEach(select => {
    select.addEventListener('change', function() {
        this.form.submit();
    });
});
</script>
@endsection