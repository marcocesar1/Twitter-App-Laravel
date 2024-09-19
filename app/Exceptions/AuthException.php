<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class AuthException extends AppException
{
    static function UserDoesNotExist()
    {
        return new self(
            message: 'User does not exist',
            code: Response::HTTP_NOT_FOUND,
        );
    }

    static function InvalidCredentials()
    {
        return new self(
            message: 'Invalid credentials',
            code: Response::HTTP_UNAUTHORIZED,
        );
    }
}
