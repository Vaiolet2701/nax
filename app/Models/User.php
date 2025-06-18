<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address', 'full_name',
        'age', 'work_experience', 'bio'
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isContentManager(): bool
    {
        return $this->role === 'content_manager';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

public function canCreateTrips(): bool
{
    return in_array($this->laravel_level, ['Продвинутый', 'Эксперт', 'Средний']);
}


public function canCreateArticles(): bool
{
    return in_array($this->laravel_level, ['Средний', 'Продвинутый', 'Эксперт']);
}

public function taughtCourses()
{
    return $this->hasMany(Course::class, 'teacher_id');
}
public function invitations()
{
    return $this->hasMany(CourseInvitation::class);
}

public function pendingInvitations()
{
    return $this->invitations()->where('status', 'pending');
}

public function canHaveProfileFields()
{
    return in_array($this->role, ['teacher']);
 
}
public function courses()
{
    return $this->belongsToMany(Course::class)
                ->withPivot([
                    'status',
                    'progress',
                    'phone',
                    'age',
                    'attended_previous_courses',
                    'message',
                    'rejection_reason',
                    'completed_at',
                    'original_price',
                    'discounted_price',
                ])
                ->withTimestamps();
}

    public function pendingCourses()
    {
        return $this->courses()->wherePivot('status', 'pending');
    }
    
    public function approvedCourses()
    {
        return $this->courses()->wherePivot('status', 'approved');
    }
    
    public function rejectedCourses()
    {
        return $this->courses()->wherePivot('status', 'rejected');
    }
    
    public function coursesInProgress()
    {
        return $this->courses()->wherePivot('status', 'in_progress');
    }
    
    public function completedCourses()
    {
        return $this->courses()->wherePivot('status', 'completed');
    }
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
    
    public function participatingTrips()
    {
        return $this->belongsToMany(Trip::class, 'trip_participants')
                    ->withPivot(['name', 'age', 'phone', 'notes']);
    }
    
        public function bans()
        {
            return $this->hasMany(Ban::class);
        }

        public function activeBan()
        {
            return $this->bans()
                ->where(function ($query) {
                    $query->where('permanent', true)
                        ->orWhere('expires_at', '>', now());
                })
                ->latest()
                ->first();
        }

        public function isBanned(): bool
        {
            return $this->activeBan() !== null;
        }
public function courseReviews()
{
    return $this->hasMany(CourseReview::class);
}

}