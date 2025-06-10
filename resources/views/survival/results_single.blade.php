@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2>Результат теста от {{ $result->created_at->format('d.m.Y H:i') }}</h2>
        </div>
        <div class="card-body">
            <div class="alert alert-{{ $result->percentage >= 70 ? 'success' : ($result->percentage >= 40 ? 'warning' : 'danger') }}">
                <h4 class="alert-heading">
                    Результат: {{ $result->score }} из {{ $result->total_questions }} ({{ number_format($result->percentage, 1) }}%)
                </h4>
                <p>
                    @if($result->percentage >= 85)
                        Отличный результат! Вы хорошо подготовлены к выживанию в диких условиях.
                    @elseif($result->percentage >= 60)
                        Хороший результат, но есть куда стремиться.
                    @elseif($result->percentage >= 40)
                        Средний результат. Рекомендуем изучить больше материалов по выживанию.
                    @else
                        Низкий результат. Вам стоит серьезно подготовиться перед походом в дикую природу.
                    @endif
                </p>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('survival.results') }}" class="btn btn-secondary">Назад к истории тестов</a>
                <a href="{{ route('survival.test') }}" class="btn btn-primary">Пройти тест снова</a>
            </div>
        </div>
    </div>
</div>
@endsection