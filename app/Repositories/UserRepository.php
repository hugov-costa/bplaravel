<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function update(array $data, User $user): bool
    {
        unset($data['password']);

        return $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
        ]);
    }
}
