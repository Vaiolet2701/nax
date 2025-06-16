@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Заблокированные пользователи</h1>
    
 <div class="admin-table-responsive">
    <table class="table admin-table">
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
                    <td data-label="Пользователь">>{{ $ban->user->name }}</td>
                    <td data-label="Заблокировал">>{{ $ban->admin->name }}</td>
                    <td data-label="Причина">>{{ $ban->reason }}</td>
                    <td data-label="Тип блокировки">>{{ $ban->permanent ? 'Навсегда' : 'Временная' }}</td>
                    <td data-label="Дата окончания">>{{ $ban->expires_at?->format('d.m.Y H:i') ?? '-' }}</td>
                    <td data-label="Действия">>
                        <div class="btn-group">
                        <form action="{{ route('admin.bans.destroy', $ban) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-success">Разблокировать</button>
                        </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
 </div>
    {{ $bannedUsers->links() }}
</div>
@endsection