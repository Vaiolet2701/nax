@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Заявки на аренду</h1>
    
    @if($rentals->isEmpty())
        <div class="alert alert-info">
            На данный момент заявок на аренду нет.
        </div>
    @else
        <div class="admin-table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Снаряжение</th>
                        <th>Срок аренды</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rentals as $rental)
                    <tr>
                        <td data-label="Пользователь">{{ $rental->user->name }}</td>
                        <td data-label="Снаряжение">{{ $rental->equipment->name }}</td>
                        <td data-label="Срок аренды">
                            {{ \Carbon\Carbon::parse($rental->start_date)->format('d.m.Y') }} – 
                            {{ \Carbon\Carbon::parse($rental->end_date)->format('d.m.Y') }}
                        </td>
                        <td data-label="Статус">
                            @if($rental->is_approved)
                                <span class="badge bg-success">Одобрено</span>
                            @else
                                <span class="badge bg-warning text-dark">Ожидает одобрения</span>
                            @endif
                        </td>
                        <td data-label="Действия">
                            <div class="btn-group">
                                @if (!$rental->is_approved)
                                    <form method="POST" action="{{ route('admin.rentals.approve', $rental->id) }}" class="me-2">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Одобрить
                                        </button>
                                    </form>
                                @endif
                                <button class="btn btn-danger btn-sm" 
                                        onclick="if(confirm('Удалить заявку?')) { document.getElementById('delete-rental-{{ $rental->id }}').submit(); }">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-rental-{{ $rental->id }}" 
                                      action="{{ route('admin.rentals.destroy', $rental->id) }}" 
                                      method="POST" 
                                      style="display: none;">
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
    @endif
</div>
@endsection