@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Управление пользователями</h1>

    <!-- Преподаватели -->
    <h2 class="mt-4">Преподаватели</h2>
    <div class="admin-table-responsive mb-4">
        <table class="table admin-table table-striped">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Дата регистрации</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                    <tr>
                        <td data-label="Имя">{{ $teacher->name }}</td>
                        <td data-label="Email">{{ $teacher->email }}</td>
                        <td data-label="Дата регистрации">{{ $teacher->created_at->format('d.m.Y H:i') }}</td>
                        <td data-label="Статус">
                            @if($teacher->isBanned())
                                <span class="badge bg-danger">Заблокирован</span>
                            @else
                                <span class="badge bg-success">Активен</span>
                            @endif
                        </td>
                        <td data-label="Действия">
                            <div class="btn-group">
                                @if(!$teacher->isBanned())
                                    <a href="{{ route('admin.bans.create', ['user' => $teacher->id]) }}" class="btn btn-danger btn-sm">Заблокировать</a>
                                @else
                                    <form action="{{ route('admin.bans.destroy', $teacher->activeBan()) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-success btn-sm">Разблокировать</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" data-label="Пусто">Нет преподавателей</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Пользователи -->
    <h2>Пользователи</h2>
    <div class="admin-table-responsive mb-4">
        <table class="table admin-table table-striped">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Дата регистрации</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td data-label="Имя">{{ $user->name }}</td>
                        <td data-label="Email">{{ $user->email }}</td>
                        <td data-label="Дата регистрации">{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        <td data-label="Статус">
                            @if($user->isBanned())
                                <span class="badge bg-danger">Заблокирован</span>
                            @else
                                <span class="badge bg-success">Активен</span>
                            @endif
                        </td>
                        <td data-label="Действия">
                            <div class="btn-group">
                                @if(!$user->isBanned())
                                    <a href="{{ route('admin.bans.create', ['user' => $user->id]) }}" class="btn btn-danger btn-sm">Заблокировать</a>
                                @else
                                    <form action="{{ route('admin.bans.destroy', $user->activeBan()) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-success btn-sm">Разблокировать</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" data-label="Пусто">Нет пользователей</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}

    <div class="mt-3">
        <a href="{{ route('admin.bans.index') }}" class="btn btn-outline-secondary">Все блокировки</a>
    </div>
</div>
@endsection