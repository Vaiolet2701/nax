<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'content' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    // Проверка аутентификации
    if (!auth()->check()) {
        return redirect()->back()->with('error', 'Для отправки отзыва необходимо авторизоваться!');
    }

    Review::create([
        'user_id' => auth()->id(),
        'content' => $request->content,
        'rating' => $request->rating
    ]);

    return redirect()->back()->with('success', 'Отзыв успешно отправлен!');
}
}