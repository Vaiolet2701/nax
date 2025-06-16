@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12"> <!-- Изменил col-md-8 на col-md-12 для лучшего использования пространства -->
            <div class="mb-3">
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-success">Добавить нового преподавателя</a>
            </div>


                <div class="card-header">Список преподавателей</div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover admin-table">
                            <thead class="d-none d-md-table-header-group">
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
                                <tr class="d-flex flex-column d-md-table-row mb-3">
                                    <td data-label="Логин">
                                        <span class="d-md-none fw-bold">Логин: </span>
                                        {{ $teacher->name }}
                                    </td>
                                    <td data-label="ФИО">
                                        <span class="d-md-none fw-bold">ФИО: </span>
                                        {{ $teacher->full_name }}
                                    </td>
                                    <td data-label="Email">
                                        <span class="d-md-none fw-bold">Email: </span>
                                        {{ $teacher->email }}
                                    </td>
                                    <td data-label="Возраст">
                                        <span class="d-md-none fw-bold">Возраст: </span>
                                        {{ $teacher->age }}
                                    </td>
                                    <td data-label="Опыт работы">
                                        <span class="d-md-none fw-bold">Опыт работы: </span>
                                        {{ $teacher->work_experience }} лет
                                    </td>
                                    <td data-label="Действия">
                                        <div class="d-flex flex-md-row gap-2">
                                            <button 
                                                onclick="event.preventDefault(); if(confirm('Удалить преподавателя?')) document.getElementById('delete-teacher-{{ $teacher->id }}').submit();" 
                                                class="btn btn-danger btn-sm flex-grow-1">
                                                Удалить
                                            </button>

                                            <form id="delete-teacher-{{ $teacher->id }}" 
                                                  action="{{ route('admin.teachers.destroy', $teacher->id) }}" 
                                                  method="POST" 
                                                  style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
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