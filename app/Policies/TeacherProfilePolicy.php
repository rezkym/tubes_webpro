<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TeacherProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeacherProfilePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, TeacherProfile $teacher)
    {
        return $user->hasRole('admin') || 
               $user->id === $teacher->user_id;
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, TeacherProfile $teacher)
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, TeacherProfile $teacher)
    {
        return $user->hasRole('admin');
    }
}