<?php

namespace App\Core\UseCases\User;

use App\Models\User;

class UnfollowUserUseCase
{
    public function execute(User $currentUser, User $user)
    {
        $currentUser = new User();
        $currentUser->following()->detach($user->id);
    }
}