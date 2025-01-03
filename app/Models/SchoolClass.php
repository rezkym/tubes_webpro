<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolClassFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function students()
    {
        return $this->hasManyThrough(
            User::class,
            StudentProfile::class,
            'school_class_id', // Foreign key di student_profiles
            'id', // Local key di users
            'id', // Local key di school_classes
            'user_id' // Foreign key yang menghubungkan student_profiles ke users
        )->whereHas('roles', function($query) {
            $query->where('name', 'student');
        });
    }

    public function teachers()
    {
        return $this->belongsToMany(TeacherProfile::class, 'class_teacher', 'class_id', 'teacher_id')
                    ->whereHas('user', function($query) {
                        $query->whereHas('roles', function($q) {
                            $q->where('name', 'teacher');
                        });
                    });
    }

    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class, 'school_class_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(SchoolSubject::class, 'class_subject');
    }
}
