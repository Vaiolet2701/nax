@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Управление пользователями</h1>


<!-- Преподаватели -->
<h2 class="mt-4">Преподаватели</h2>
<div class="table-responsive mb-4">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
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
                    <td>{{ $teacher->id }}</td>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td>{{ $teacher->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($teacher->isBanned())
                            <span class="badge bg-danger">Заблокирован</span>
                        @else
                            <span class="badge bg-success">Активен</span>
                        @endif
                    </td>
                    <td>
                        @if(!$teacher->isBanned())
                            <a href="{{ route('admin.bans.create', ['user' => $teacher->id]) }}" class="btn btn-danger btn-sm">Заблокировать</a>
                        @else
                            <form action="{{ route('admin.bans.destroy', $teacher->activeBan()) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-success btn-sm">Разблокировать</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Нет преподавателей</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Пользователи -->
<h2>Пользователи</h2>
<div class="table-responsive mb-4">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
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
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($user->isBanned())
                            <span class="badge bg-danger">Заблокирован</span>
                        @else
                            <span class="badge bg-success">Активен</span>
                        @endif
                    </td>
                    <td>
                        @if(!$user->isBanned())
                            <a href="{{ route('admin.bans.create', ['user' => $user->id]) }}" class="btn btn-danger btn-sm">Заблокировать</a>
                        @else
                            <form action="{{ route('admin.bans.destroy', $user->activeBan()) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-success btn-sm">Разблокировать</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Нет пользователей</td></tr>
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