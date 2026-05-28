<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;

class RegisterService
{
    public function register(array $data): User
    {
        $user = User::create([
            'name'         => $data['name'],
            'email'        => strtolower($data['email']),
            'password'     => bcrypt($data['password']),
            'primary_role' => Role::where('id', $data['primary_role'])->first()?->id,
        ]);

        return $user;
    }
}