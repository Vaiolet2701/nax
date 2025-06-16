@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список видео</h1>
        <a href="{{ route('admin.videos.create') }}" class="btn btn-primary mb-3">Добавить видео</a>
        
        <div class="table-responsive-md">
            <table class="table table-hover">
                <thead class="d-none d-md-table-header-group">
                    <tr>
                        <th>Название</th>
                        <th>Ссылка</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($videos as $video)
                        <tr class="d-flex flex-column d-md-table-row mb-3">
                            <td>
                                <span class="d-md-none fw-bold">Название: </span>
                                {{ $video->title }}
                            </td>
                            <td>
                                <span class="d-md-none fw-bold">Ссылка: </span>
                                <a href="{{ $video->url }}" target="_blank">{{ $video->url }}</a>
                            </td>
                            <td>
                                <div class="d-flex flex-md-row gap-2">
                                    <a href="{{ route('admin.videos.edit', $video->id) }}" 
                                       class="btn btn-success btn-sm flex-grow-1">
                                       Редактировать
                                    </a>
                                    <button onclick="event.preventDefault(); if(confirm('Удалить видео?')) document.getElementById('delete-video-{{ $video->id }}').submit();" 
                                            class="btn btn-danger btn-sm flex-grow-1">
                                        Удалить
                                    </button>
                                    <form id="delete-video-{{ $video->id }}" 
                                          action="{{ route('admin.videos.destroy', $video->id) }}" 
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
@endsection