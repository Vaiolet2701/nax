<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    protected $fillable = ['name'];

    public function userArticles()
    {
        return $this->hasMany(UserArticle::class, 'category_id');
    }

    public function adminArticles()
    {
        return $this->hasMany(AdminArticle::class, 'category_id');
    }
}