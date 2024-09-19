<?php

namespace App\Core\UseCases\Auth;

use App\Models\User;
use App\Exceptions\AuthException;

use Illuminate\Support\Facades\Hash;

class LoginUserUseCase {
    public function execute(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();
        if(!$user) {
             throw AuthException::UserDoesNotExist();
        }

        if(!Hash::check($password, $user->password)) {
             throw AuthException::InvalidCredentials();
        }

        $token = $user->createToken('api');
        $plainTextToken = $token->plainTextToken;

        $user->load('profileImg');

        return [
            'user' => $user,
            'token' => $plainTextToken,
        ];
    }
}