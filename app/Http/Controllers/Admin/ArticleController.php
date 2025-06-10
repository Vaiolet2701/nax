<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminArticle;
use Illuminate\Http\Request;
use App\Models\ArticleCategory;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = AdminArticle::all();
        return view('admin.articles.index', compact('articles'));
    }

public function create()
{
    $categories = ArticleCategory::all();
    return view('admin.articles.create', compact('categories'));
}

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:article_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Обработка изображения
        ]);
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/articles', 'public'); // Сохраняем изображение
        }
    
        AdminArticle::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image_path' => $imagePath, // Сохраняем путь к изображению
        ]);
    
        return redirect()->route('admin.articles.index')->with('success', 'Статья успешно создана.');
    }
public function edit(AdminArticle $article)
{
    $categories = ArticleCategory::all();
    return view('admin.articles.edit', compact('article', 'categories'));
}
    public function update(Request $request, AdminArticle $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:article_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Обработка изображения
        ]);
    
        $imagePath = $article->image_path;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/articles', 'public'); // Обновляем изображение
        }
    
        $article->update([
            'title' => $request->title,
            'content' => $request->content,
             'category_id' => $request->category_id,
            'image_path' => $imagePath, 
        ]);
    
        return redirect()->route('admin.articles.index')->with('success', 'Статья успешно обновлена.');
    }

    public function destroy(AdminArticle $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Статья успешно удалена.');
    }
}