<?php

namespace App\Core\UseCases\Auth;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

class ChangeUserPasswordUseCase {
    public function execute(array $userData, User $user)
    {
        $passwordHash = Hash::make($userData['password']);

        $user->password = $passwordHash;
        $user->save();
    }
}