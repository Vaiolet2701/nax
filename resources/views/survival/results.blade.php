@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2>Результаты теста по выживанию</h2>
        </div>
        <div class="card-body">
            <div class="alert alert-{{ $percentage >= 70 ? 'success' : ($percentage >= 40 ? 'warning' : 'danger') }}">
                <h4 class="alert-heading">
                    Ваш результат: {{ $score }} из {{ $totalQuestions }} ({{ number_format($percentage, 1) }}%)
                </h4>
                <p>
                    @if($percentage >= 85)
                        Отличный результат! Вы хорошо подготовлены к выживанию в диких условиях.
                    @elseif($percentage >= 60)
                        Хороший результат, но есть куда стремиться.
                    @elseif($percentage >= 40)
                        Средний результат. Рекомендуем изучить больше материалов по выживанию.
                    @else
                        Низкий результат. Вам стоит серьезно подготовиться перед походом в дикую природу.
                    @endif
                </p>
            </div>

            @if(!$isLoggedIn)
                <div class="alert alert-info">
                    <p>Вы не авторизованы. Чтобы сохранить результаты теста в вашем профиле, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a>.</p>
                </div>
            @endif

            <h3 class="mt-4">Детализация ответов:</h3>
            
            @foreach($results as $result)
            <div class="card mb-3">
                <div class="card-header {{ $result['is_correct'] ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    {{ $result['is_correct'] ? 'Правильно' : 'Неправильно' }}
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $result['question'] }}</h5>
                    <p class="card-text">
                        <strong>Ваш ответ:</strong> {{ $result['user_answer'] }}<br>
                        <strong>Правильный ответ:</strong> {{ $result['correct_answer'] }}
                    </p>
                    @if($result['explanation'])
                    <div class="alert alert-secondary">
                        <strong>Пояснение:</strong> {{ $result['explanation'] }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            
            <div class="mt-4">
                <a href="{{ route('survival.test') }}" class="btn btn-primary">Попробовать снова</a>
                <a href="{{ route('survival.results') }}" class="btn btn-secondary">Мои результаты</a>
            </div>
        </div>
    </div>
</div>
@endsection