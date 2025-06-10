<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'description', 'description', 
        'discount', 'start_date', 'end_date',
        'is_active'
    ];
    // Другие свойства и методы модели

    /**
     * Получить все записи о зачислениях, связанные с этой акцией.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    
    public function courses()
    {
        return $this->belongsToMany(Course::class); 
    }
    
}