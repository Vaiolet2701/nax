@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2>История моих тестов по выживанию</h2>
        </div>
        <div class="card-body">
            @if($results->isEmpty())
                <div class="alert alert-info">
                    У вас пока нет сохраненных результатов тестов.
                </div>
            @else
                <div class="list-group">
                    @foreach($results as $result)
                    <a href="{{ route('survival.result', $result->id) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Тест от {{ $result->created_at->format('d.m.Y H:i') }}</h5>
                            <span class="badge bg-{{ $result->percentage >= 70 ? 'success' : ($result->percentage >= 40 ? 'warning' : 'danger') }} rounded-pill">
                                {{ $result->score }}/{{ $result->total_questions }} ({{ number_format($result->percentage, 1) }}%)
                            </span>
                        </div>
                        <p class="mb-1">
                            @if($result->percentage >= 85)
                                Отличный результат
                            @elseif($result->percentage >= 60)
                                Хороший результат
                            @elseif($result->percentage >= 40)
                                Средний результат
                            @else
                                Низкий результат
                            @endif
                        </p>
                    </a>
                    @endforeach
                </div>
            @endif
            
            <div class="mt-4">
                <a href="{{ route('survival.test') }}" class="btn btn-primary">Пройти тест снова</a>
                @auth
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">Вернуться в профиль</a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection