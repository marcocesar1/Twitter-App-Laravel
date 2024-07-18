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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function login(LoginRequest $request, LoginUserUseCase $usecase)
    {
        try {
            $userInfo = $usecase->execute(
                userData: $request->validated()
            );

            return new ApiSuccessResponse(
                data: $userInfo
            );
        } catch (UnauthorizedHttpException $e) {
            return new ApiErrorResponse(
                message: $e->getMessage(),
                exception: $e,
                statusCode: $e->getStatusCode()
            );
        } catch (Exception $e) {
            return new ApiErrorResponse(
                message: "Something went wrong",
                exception: $e,
                statusCode: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    public function register(RegisterRequest $request, RegisterUserUseCase $usecase) 
    {
        try {
            $user = $usecase->execute(
                userData: $request->validated()
            );

            return new ApiSuccessResponse(
                data: $user
            );
        } catch (Exception $e) {
            return new ApiErrorResponse(
                message: "Errror creating user",
                exception: $e,
            );
        }
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
