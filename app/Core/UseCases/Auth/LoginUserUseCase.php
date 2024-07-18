<?php

namespace App\Core\UseCases\Auth;

use App\Models\User;

use Exception;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginUserUseCase {
    public function execute(array $userData): array
    {
        $user = User::where('email', $userData['email'])->first();
        if(!$user) {
             throw new UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        if(!Hash::check($userData['password'], $user->password)) {
             throw new UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        $token = $user->createToken('api');
        $plainTextToken = $token->plainTextToken;

        return [
            'user' => $user,
            'token' => $plainTextToken,
        ];
    }
}