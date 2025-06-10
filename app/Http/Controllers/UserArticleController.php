<?php
namespace App\Http\Controllers;

use App\Models\UserArticle;
use App\Models\AdminArticle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ArticleCategory;

class UserArticleController extends Controller
{
    // Показать все статьи (пользователей и администратора)
    public function index()
    {
        $userArticles = UserArticle::where('is_approved', true)->get();
        $adminArticles = AdminArticle::all();

        return view('articles.index', [
            'userArticles' => $userArticles,
            'adminArticles' => $adminArticles
        ]);
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Для создания статьи необходимо авторизоваться.');
        }
        
        // Проверяем уровень пользователя
        if (!auth()->user()->canCreateArticles()) {
            return redirect()->back()->with('error', 'Создание статей доступно только пользователям со средним или продвинутым уровнем.');
        }
        $categories = ArticleCategory::all();

    return view('articles.create', compact('categories'));
    }

    // Сохранение статьи
    public function store(Request $request)
    {
        // Проверяем уровень пользователя перед сохранением
        if (!auth()->user()->canCreateArticles()) {
            return redirect()->back()->with('error', 'Создание статей доступно только пользователям со средним или продвинутым уровнем.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:article_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/articles', 'public');
        }
    
        UserArticle::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
            'is_approved' => false, 
            'category_id' => $request->category_id,
            'image_path' => $imagePath,
        ]);
    
        return redirect()->back()->with('success', 'Статья отправлена на модерацию!');
    }
}