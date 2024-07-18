<?php

namespace App\Core\UseCases\Profile;

use App\Models\User;

class UpdateProfileUseCase
{
    public function execute(array $profileData, User $user): void
    {
        $user->fill($profileData);
        $user->save();
    }
}