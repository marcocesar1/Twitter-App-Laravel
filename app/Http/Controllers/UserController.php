<?php

namespace App\Http\Controllers;

use App\Core\UseCases\User\FollowUserUseCase;
use App\Core\UseCases\User\UnfollowUserUseCase;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function follow(User $user, FollowUserUseCase $usecase)
    {
        try {
            $currentUser = Auth::user();

            $usecase->execute(
                currentUser: $currentUser,
                user: $user,
            );

            return new ApiSuccessResponse(
                message: 'You are now following'
            );
        } catch (Exception $e) {
            return new ApiErrorResponse(
                message: "Something went wrong",
                exception: $e,
            );
        }
    }
    
    public function unfollow(User $user, UnfollowUserUseCase $usecase)
    {
        try {
            $currentUser = Auth::user();

            $usecase->execute(
                currentUser: $currentUser,
                user: $user,
            );

            return new ApiSuccessResponse(
                message: 'You have unfollowed'
            );
        } catch (Exception $e) {
            return new ApiErrorResponse(
                message: "Something went wrong",
                exception: $e,
            );
        }
    }
}
