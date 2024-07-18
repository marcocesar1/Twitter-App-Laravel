<?php

namespace App\Core\UseCases\User;

use App\Models\User;

class FollowUserUseCase
{
    public function execute(User $currentUser, User $user)
    {
        $currentUser = new User();
        $currentUser->following()->attach($user->id);
    }
}