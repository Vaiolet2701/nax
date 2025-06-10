@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Заблокированные пользователи</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>Пользователь</th>
                <th>Заблокировал</th>
                <th>Причина</th>
                <th>Тип блокировки</th>
                <th>Дата окончания</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bannedUsers as $ban)
                <tr>
                    <td>{{ $ban->user->name }}</td>
                    <td>{{ $ban->admin->name }}</td>
                    <td>{{ $ban->reason }}</td>
                    <td>{{ $ban->permanent ? 'Навсегда' : 'Временная' }}</td>
                    <td>{{ $ban->expires_at?->format('d.m.Y H:i') ?? '-' }}</td>
                    <td>
                        <form action="{{ route('admin.bans.destroy', $ban) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-success">Разблокировать</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $bannedUsers->links() }}
</div>
@endsection