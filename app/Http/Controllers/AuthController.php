<?php

namespace App\Http\Controllers;

use App\Core\UseCases\Auth\{
    LoginUserUseCase,
    RegisterUserUseCase,
    ChangeUserPasswordUseCase
};

use App\Http\Requests\Auth\{
    LoginRequest,
    RegisterRequest,
    ChangePasswordRequest
};

use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;

use Exception;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request, LoginUserUseCase $usecase)
    {
        $userInfo = $usecase->execute(
            email: $request->email,
            password: $request->password,
        );

        return new ApiSuccessResponse(
            data: $userInfo
        );
    }

    public function register(RegisterRequest $request, RegisterUserUseCase $usecase) 
    {
        $user = $usecase->execute(
            userData: $request->validated()
        );

        return new ApiSuccessResponse(
            data: $user
        );
    }
    
    public function changePassword(ChangePasswordRequest $request, ChangeUserPasswordUseCase $usecase) 
    {
        try {
            $usecase->execute(
                userData: $request->validated(),
                user: Auth::user(),
            );

            return new ApiSuccessResponse(
                message: 'Password updated'
            );
        } catch (Exception $e) {
            return new ApiErrorResponse(
                message: "Errror updating user",
                exception: $e,
            );
        }
    }
}
