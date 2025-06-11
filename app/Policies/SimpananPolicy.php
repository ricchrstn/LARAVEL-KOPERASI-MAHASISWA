<?php

namespace App\Policies;

use App\Models\Simpanan;
use App\Models\User;

class SimpananPolicy
{
    public function view(User $user, Simpanan $simpanan)
    {
        return $user->isAdmin() || $user->id === $simpanan->user_id;
    }

    public function update(User $user, Simpanan $simpanan)
    {
        return $user->isAdmin() || $user->id === $simpanan->user_id;
    }

    public function delete(User $user, Simpanan $simpanan)
    {
        return $user->isAdmin() || $user->id === $simpanan->user_id;
    }
}
