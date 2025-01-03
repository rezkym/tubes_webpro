<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherProfile extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherProfileFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_number',
        'full_name',
        'specialization',
        'phone_number',
        'address',
        'join_date',
        'education_level',
        'teaching_experience_years'
    ];

    protected $casts = [
        'join_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(SchoolSubject::class, 'class_subject', 'teacher_profile_id', 'subject_id');
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_teacher', 'teacher_id', 'class_id');
    }
}
