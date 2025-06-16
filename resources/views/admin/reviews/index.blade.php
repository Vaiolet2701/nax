@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список отзывов</h1>
        
        <div class="admin-table-responsive">
            <table class="table admin-table">
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
                            <td data-label="Автор">{{ $review->author_name }}</td>
                            <td data-label="Содержание">
                                <div class="review-content">
                                    {{ Str::limit($review->content, 100) }}
                                    @if(strlen($review->content) > 100)
                                        <span class="review-more">...</span>
                                    @endif
                                </div>
                            </td>
                            <td data-label="Действия">
                                <div class="btn-group">
                                    <button 
                                        onclick="event.preventDefault(); 
                                                if(confirm('Удалить отзыв?')) 
                                                    document.getElementById('delete-review-{{ $review->id }}').submit();" 
                                        class="btn btn-sm btn-danger">
                                        Удалить
                                    </button>

                                    <form id="delete-review-{{ $review->id }}" 
                                          action="{{ route('admin.reviews.destroy', $review->id) }}" 
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