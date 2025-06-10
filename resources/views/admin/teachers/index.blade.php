@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Кнопка для перехода на страницу создания нового преподавателя -->
            <div class="mb-3">
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-success">Добавить нового преподавателя</a>
            </div>

            <!-- Секция: Список преподавателей -->
            <div class="card">
                <div class="card-header">Список преподавателей</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Логин</th>
                                <th>ФИО</th>
                                <th>Email</th>
                                <th>Возраст</th>
                                <th>Опыт работы</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                            <tr>
                                <td>{{ $teacher->name }}</td>
                                <td>{{ $teacher->full_name }}</td>
                                <td>{{ $teacher->email }}</td>
                                <td>{{ $teacher->age }}</td>
                                <td>{{ $teacher->work_experience }} лет</td>
                                <td>
                                    <button 
                                        onclick="event.preventDefault(); if(confirm('Удалить преподавателя?')) document.getElementById('delete-teacher-{{ $teacher->id }}').submit();" 
                                        class="btn btn-danger btn-sm">
                                        Удалить
                                    </button>

                                    <form id="delete-teacher-{{ $teacher->id }}" action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
