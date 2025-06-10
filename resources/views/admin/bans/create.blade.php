@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Блокировка пользователя: {{ $user->name }}</h1>
    
    <form action="{{ route('admin.bans.store', ['user' => $user->id]) }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="reason">Причина блокировки</label>
            <textarea name="reason" id="reason" class="form-control" required></textarea>
        </div>
        
        <div class="form-group">
            <label>Тип блокировки</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="permanent" value="permanent" checked>
                <label class="form-check-label" for="permanent">
                    Навсегда
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="temporary" value="temporary">
                <label class="form-check-label" for="temporary">
                    Временная
                </label>
            </div>
        </div>
        
        <div class="form-group" id="days-group" style="display: none;">
            <label for="days">Количество дней</label>
            <input type="number" name="days" id="days" class="form-control" min="1" value="7" required>
        </div>
        
        <button type="submit" class="btn btn-danger">Заблокировать</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const temporaryRadio = document.getElementById('temporary');
    const daysGroup = document.getElementById('days-group');
    const daysInput = document.getElementById('days');
    
    temporaryRadio.addEventListener('change', function() {
        daysGroup.style.display = this.checked ? 'block' : 'none';
        daysInput.required = this.checked;
    });
    
    document.getElementById('permanent').addEventListener('change', function() {
        daysGroup.style.display = this.checked ? 'none' : 'block';
        daysInput.required = !this.checked;
    });
});
</script>
@endsection