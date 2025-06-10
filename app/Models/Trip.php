<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'max_participants'
    ];

    // Добавьте это свойство для автоматического преобразования дат
    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


public function participants()
{
    return $this->belongsToMany(User::class, 'trip_participants')
                ->withPivot(['name', 'age', 'phone', 'notes', 'created_at', 'updated_at']);
}
}