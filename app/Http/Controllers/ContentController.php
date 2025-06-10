<?php

namespace App\Http\Controllers;

use App\Models\AdminArticle;
use App\Models\UserArticle;
use App\Models\Video;
use App\Models\ArticleCategory;

class ContentController extends Controller
{
public function index()
{
    // Получаем все категории из базы
    $categories = ArticleCategory::all();

    // Получаем статьи пользователей и админа
    $userArticles = UserArticle::where('is_approved', true)->with('category', 'user')->get();
    $adminArticles = AdminArticle::with('category')->get();

    $allArticles = $userArticles->map(function($item) {
        $item->author_name = $item->user->name ?? 'Неизвестный автор';
        $item->type = 'user';
        return $item;
    })->concat(
        $adminArticles->map(function($item) {
            $item->author_name = 'Администратор';
            $item->type = 'admin';
            return $item;
        })
    );

    $videos = Video::all();

    return view('content.index', compact('allArticles', 'categories', 'videos'));
}

}
