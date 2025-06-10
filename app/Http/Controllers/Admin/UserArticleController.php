<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserArticle;
use Illuminate\Http\Request;

class UserArticleController extends Controller
{
public function index()
{
    $articles = UserArticle::with('category', 'user')
                ->where('is_approved', false)
                ->get();

    return view('admin.user-articles.index', compact('articles'));
}


    public function approve($id)
    {
        $article = UserArticle::findOrFail($id);
        $article->update(['is_approved' => true]);

        return redirect()->route('admin.user-articles.index')->with('success', 'Статья одобрена.');
    }

    public function destroy($id)
    {
        $article = UserArticle::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.user-articles.index')->with('success', 'Статья удалена.');
    }
}