<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'is_approved',
        'image_path',
    ];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function category()
{
    return $this->belongsTo(ArticleCategory::class, 'category_id');
}

}