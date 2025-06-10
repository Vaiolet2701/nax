@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список отзывов</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Автор</th>
                    <th>Содержание</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>{{ $review->author_name }}</td>
                        <td>{{ $review->content }}</td>
                        <td>
                        <button 
                            onclick="event.preventDefault(); 
                                    if(confirm('Удалить отзыв?')) 
                                        document.getElementById('delete-review-{{ $review->id }}').submit();" 
                            class="btn btn-sm btn-danger">
                            Удалить
                        </button>

                        <form id="delete-review-{{ $review->id }}" action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection