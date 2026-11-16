<?php

namespace App\Policies;

use App\Models\Pegawai;
use App\Models\User;

class PegawaiPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->pegawai === null;
    }

    public function update(User $user, Pegawai $pegawai): bool
    {
        return $user->isAdmin() || $user->id === $pegawai->user_id;
    }

    public function delete(User $user, Pegawai $pegawai): bool
    {
        return $user->isAdmin();
    }
}