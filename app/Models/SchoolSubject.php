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

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject', 'school_subject_id', 'school_class_id');
    }

    // public function attendanceTemplates()
    // {
    //     return $this->hasMany(AttendanceTemplate::class, 'subject_id');
    // }

}
