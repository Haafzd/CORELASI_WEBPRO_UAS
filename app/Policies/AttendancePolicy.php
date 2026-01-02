<?php

namespace App\Policies;

use App\Models\AttendanceRecord;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Guru', 'Admin']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Guru', 'Admin']);
    }

    public function update(User $user, AttendanceRecord $record): bool
    {
        return in_array($user->role, ['Guru', 'Admin']);
    }
}
