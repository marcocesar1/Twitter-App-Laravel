<?php

namespace App\Core\UseCases\Auth;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginUserUseCase {
    public function execute(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();
        if(!$user) {
             throw new UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        if(!Hash::check($password, $user->password)) {
             throw new UnauthorizedHttpException('Basic', 'Invalid credentials.');
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