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
        'max_participants',
        'status'
    ];
const STATUS_PENDING = 'pending';
const STATUS_APPROVED = 'approved';
const STATUS_REJECTED = 'rejected';

public static function getStatuses()
{
    return [
        self::STATUS_PENDING => 'На рассмотрении',
        self::STATUS_APPROVED => 'Одобрено',
        self::STATUS_REJECTED => 'Отклонено',
    ];
}
    // Добавьте это свойство для автоматического преобразования дат
    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
public function getDateAttribute()
{
    return $this->start_date; // или другая логика
}
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