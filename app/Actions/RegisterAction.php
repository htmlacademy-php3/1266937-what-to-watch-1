<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Enums\RoleName;

class RegisterAction
{
    public function execute(array $data): User
    {
        $role = Role::firstOrCreate(['name' => RoleName::User->value]);

        $filePath = request()->hasFile('file')
            ? request()->file('file')->store('avatars', 'public')
            : null;

        return User::create([
            'name' => $data['name'],
            'role_id' => $role->id,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'file' => $filePath,
        ]);
    }
}
