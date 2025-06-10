<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'author_name' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Создание отзыва
        Review::create([
            'author_name' => $request->input('author_name'),
            'content' => $request->input('content'),
            'rating' => $request->input('rating'),
           
        ]);

        // Редирект с сообщением об успехе
        return redirect()->back()->with('success', 'Отзыв успешно отправлен!');
    }
}