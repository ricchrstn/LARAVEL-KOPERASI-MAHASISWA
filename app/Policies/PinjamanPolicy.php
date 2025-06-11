<?php

namespace App\Policies;

use App\Models\Pinjaman;
use App\Models\User;

class PinjamanPolicy
{
    public function view(User $user, Pinjaman $pinjaman)
    {
        return $user->isAdmin() || $user->id === $pinjaman->user_id;
    }

    public function update(User $user, Pinjaman $pinjaman)
    {
        return $user->isAdmin() || $user->id === $pinjaman->user_id;
    }

    public function delete(User $user, Pinjaman $pinjaman)
    {
        return $user->isAdmin() || $user->id === $pinjaman->user_id;
    }
}
