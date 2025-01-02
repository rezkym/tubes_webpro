<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolSubject extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolSubjectFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'teacher_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'class_subject', 'subject_id', 'teacher_profile_id')
        ->whereHas('roles', function($q) {
            $q->where('name', 'teacher');
        });
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject', 'subject_id', 'class_id')
            ->withPivot('teacher_profile_id');
    }

    public function attendanceTemplates()
    {
        return $this->hasMany(AttendanceTemplate::class, 'school_subject_id');
    }

}
