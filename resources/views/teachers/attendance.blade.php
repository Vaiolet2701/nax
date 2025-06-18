@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-gray-800 p-6 rounded-lg shadow text-white">
    <h2 class="text-xl font-bold mb-4">Отметка посещения</h2>

    <!-- Первая форма - обновление посещения -->
<form method="POST" action="{{ route('teachers.attendance.update', $user) }}">
    @csrf
    @method('PUT')

        <label class="block mb-2">Посетил ли курс:</label>
        <select name="attended" class="w-full p-2 rounded text-black mb-4">
            <option value="1" {{ $courseUser->attended ? 'selected' : '' }}>Да</option>
            <option value="0" {{ !$courseUser->attended ? 'selected' : '' }}>Нет</option>
        </select>

        <label class="block mb-2">Время прихода (если был):</label>
        <input type="datetime-local" name="checked_in_at" value="{{ $courseUser->checked_in_at ? $courseUser->checked_in_at->format('Y-m-d\TH:i') : '' }}" class="w-full p-2 rounded text-black mb-4">

        <label class="block mb-2">Комментарий преподавателя:</label>
        <textarea name="teacher_comment" class="w-full p-2 rounded text-black mb-4">{{ $courseUser->teacher_comment }}</textarea>

        <button type="submit" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded">Сохранить</button>
    </form>

    <hr class="my-6 border-gray-600">

    <!-- Вторая форма - жалоба администратору -->
    <h2 class="text-xl font-bold mb-4">Жалоба администратору</h2>

    <form method="POST" action="{{ route('attendance.issue') }}">
        @csrf

        <input type="hidden" name="course_user_id" value="{{ $courseUser->id }}">
        <input type="hidden" name="course_id" value="{{ $courseUser->course_id }}">
        <input type="hidden" name="user_id" value="{{ $courseUser->user_id }}">

        <label class="block mb-2">Тип жалобы:</label>
        <select name="type" class="w-full p-2 rounded text-black mb-4">
            <option value="discipline">Нарушение дисциплины</option>
            <option value="absence">Неявка</option>
            <option value="other">Другое</option>
        </select>

        <label class="block mb-2">Описание:</label>
        <textarea name="description" class="w-full p-2 rounded text-black mb-4" rows="4"></textarea>

        <button type="submit" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded">Отправить жалобу</button>
    </form>
</div>
@endsection