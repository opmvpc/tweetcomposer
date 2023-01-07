<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param mixed $user
     */
    public function delete($user)
    {
        Storage::deleteDirectory("public/users/{$user->id}");
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
    }
}
