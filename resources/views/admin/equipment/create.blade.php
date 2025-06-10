@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Добавить снаряжение</h2>

    <form action="{{ route('admin.equipment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">Город</label>
            <input type="text" name="city" class="form-control" required value="Казань" readonly>
        </div>
        <div class="mb-3">
            <label for="image_path" class="form-label">Изображение</label>
            <input type="file" name="image_path" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
</div>
@endsection
