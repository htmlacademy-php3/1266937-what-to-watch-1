<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Enums\RoleName;

class RegisterAction
{
    public function execute(array $data): User
    {
        $role = Role::where('name', RoleName::USER->value)->firstOrFail();

        return User::create([
            'name' => $data['name'],
            'role_id' => $role->id,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
