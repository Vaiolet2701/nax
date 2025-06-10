@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #0f1c16;
        color: #e1ffe1;
    }

    .test-container {
        padding-top: 3rem;
        padding-bottom: 3rem;
        max-width: 800px;
        margin: 0 auto;
    }

    h1 {
        color: #00ff66;
        font-weight: bold;
        margin-bottom: 2rem;
        text-shadow: 0 0 5px #00ff66;
        text-align: center;
    }

    .question-block {
        margin-bottom: 2.5rem;
        padding: 1.5rem;
        background-color: #1c2a23;
        border-left: 5px solid #00ff66;
        border-radius: 12px;
        box-shadow: inset 0 0 0 1px #00ff6622;
    }

    .question-title {
        font-size: 1.2rem;
        color: #00ff66;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .form-check-label {
        color: #d5fcd5;
    }

    .form-check-input:checked {
        background-color: #00ff66;
        border-color: #00ff66;
    }

    .btn-success {
        background-color: #2d8659;
        border-color: #2d8659;
        font-size: 1.25rem;
        border-radius: 10px;
    }

    .btn-success:hover {
        background-color: #256f49;
        border-color: #256f49;
    }
</style>

<div class="test-container">
    <h1>Тест по выживанию в диких условиях</h1>
    
    <form action="{{ route('survival.submit') }}" method="POST">
        @csrf

        @foreach($questions as $index => $question)
        <div class="question-block">
            <div class="question-title">
                Вопрос {{ $index + 1 }}: {{ $question->question }}
            </div>

            @foreach($question->options as $key => $option)
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" 
                       name="answers[{{ $question->id }}]" 
                       id="question_{{ $question->id }}_option_{{ $key }}" 
                       value="{{ $key }}" required>
                <label class="form-check-label" for="question_{{ $question->id }}_option_{{ $key }}">
                    {{ $option }}
                </label>
            </div>
            @endforeach
        </div>
        @endforeach

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-success btn-lg">Завершить тест</button>
        </div>
    </form>
</div>
@endsection
