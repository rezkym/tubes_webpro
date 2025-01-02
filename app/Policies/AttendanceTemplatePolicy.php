<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AttendanceTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendanceTemplatePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, AttendanceTemplate $template)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('teacher') && 
               $template->teacher_profile_id === $user->teacherProfile->id;
    }

    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function update(User $user, AttendanceTemplate $template)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('teacher') && 
               $template->teacher_profile_id === $user->teacherProfile->id;
    }

    public function delete(User $user, AttendanceTemplate $template)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('teacher') && 
               $template->teacher_profile_id === $user->teacherProfile->id;
    }
}