<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurvivalTestResult extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'score', 'total_questions', 'percentage', 'session_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}