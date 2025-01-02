<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, Attendance $attendance)
    {
        return $user->hasRole('admin') || 
               $user->hasRole('teacher') && $attendance->teacher_profile_id === $user->teacherProfile->id;
    }

    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function update(User $user, Attendance $attendance)
    {
        return $user->hasRole('admin') || 
               $user->hasRole('teacher') && $attendance->teacher_profile_id === $user->teacherProfile->id;
    }

    public function delete(User $user, Attendance $attendance)
    {
        return $user->hasRole('admin');
    }

    public function takeAttendance(User $user, $classId, $subjectId)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('teacher')) {
            return $user->teacherProfile->subjects()
                       ->where('id', $subjectId)
                       ->whereHas('classes', function($query) use ($classId) {
                           $query->where('school_classes.id', $classId);
                       })
                       ->exists();
        }

        return false;
    }
}