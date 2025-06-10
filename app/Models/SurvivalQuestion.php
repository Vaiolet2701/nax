<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurvivalQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'options', 'correct_option', 'explanation'];

    protected $casts = [
        'options' => 'array',
    ];
}