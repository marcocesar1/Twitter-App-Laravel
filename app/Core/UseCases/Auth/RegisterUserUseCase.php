<?php

namespace App\Core\UseCases\Auth;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

class RegisterUserUseCase {
    public function execute(array $userData): User
    {
        $passwordHash = Hash::make($userData['password']);

        $user = new User();
        $user->fill($userData);
        $user->password = $passwordHash;
        $user->save();

        return $user;
    }
}