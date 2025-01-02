<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentProfilePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, StudentProfile $student)
    {
        return $user->hasAnyRole(['admin', 'teacher']) || 
               $user->id === $student->user_id;
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, StudentProfile $student)
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, StudentProfile $student)
    {
        return $user->hasRole('admin');
    }
}