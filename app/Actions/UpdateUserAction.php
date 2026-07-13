<?php

namespace App\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;

final class UpdateUserAction
{
    /**
     * Execute user profile update.
     */
    public function handle(User $user, array $data, ?UploadedFile $file = null): User
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($file) {
            filled($user->file) && Storage::disk('public')->delete($user->file);

            $data['file'] = $file->store('files', 'public');
        }

        $user->update($data);

        return $user->load('role');
    }
}
