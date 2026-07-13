<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Enums\RoleName;

final class RegisterAction
{
    /**
     * Register a user, assign a role, generate an access token.
     *
     * @param array<string, mixed> $data
     */
    public function execute(array $data): User
    {
        $role = Role::query()->firstOrCreate(['name' => RoleName::User->value]);

        $file = request()->file('file');

        $filePath = $file instanceof \Illuminate\Http\UploadedFile
            ? $file->store('avatars', 'public')
            : null;

        return User::query()->create([
            'name' => $data['name'],
            'role_id' => $role->id,
            'email' => $data['email'],
            'password' => Hash::make((string) $data['password']),
            'file' => $filePath,
        ]);
    }
}
