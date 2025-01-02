<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole(['admin', 'teacher']);
    }

    public function view(User $authenticatedUser, User $targetUser)
    {
        // Admin dapat melihat semua user
        if ($authenticatedUser->hasRole('admin')) {
            return true;
        }

        // Guru hanya bisa melihat profil siswa di kelasnya
        if ($authenticatedUser->hasRole('teacher')) {
            if ($targetUser->hasRole('student')) {
                return $targetUser->studentProfile->schoolClass
                    ->teachers()
                    ->where('users.id', $authenticatedUser->id)
                    ->exists();
            }
            return false;
        }

        // Siswa hanya bisa melihat profilnya sendiri
        return $authenticatedUser->id === $targetUser->id;
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $authenticatedUser, User $targetUser)
    {
        // Admin dapat mengupdate semua user
        if ($authenticatedUser->hasRole('admin')) {
            return true;
        }

        // User dapat mengupdate profilnya sendiri
        return $authenticatedUser->id === $targetUser->id;
    }

    public function delete(User $user)
    {
        return $user->hasRole('admin');
    }

    public function impersonate(User $authenticatedUser, User $targetUser)
    {
        // Hanya admin yang bisa impersonate, dan tidak bisa impersonate admin lain
        return $authenticatedUser->hasRole('admin') && 
               !$targetUser->hasRole('admin');
    }

    public function exportData(User $user)
    {
        return $user->hasRole(['admin', 'teacher']);
    }
}
