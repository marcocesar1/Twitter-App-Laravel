<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Core\UseCases\Profile\ListProfilePostsUseCase;
use App\Core\UseCases\Profile\UpdateProfileUseCase;

use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Http\Requests\Profile\UpdateProfileRequest;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load('profileImg');

        return new ApiSuccessResponse(
            data: $user
        );
    }
    
    public function update(UpdateProfileRequest $request, UpdateProfileUseCase $usecase)
    {
        try {
            $post = $usecase->execute(
                profileData: $request->validated(),
                user: Auth::user(),
            );

            return new ApiSuccessResponse(
                data: $post
            );
        } catch (Exception $e) {
            return new ApiErrorResponse(
                message: "Something went wrong",
                exception: $e,
            );
        }
    }

    public function posts(Request $request, User $user, ListProfilePostsUseCase $usecase)
    {
        try {
            $posts = $usecase->execute(
                filters: $request->all(),
                user: $user,
            );

            return new ApiSuccessResponse(
                data: $posts
            );
        } catch (Exception $e) {
            return new ApiErrorResponse(
                message: "Something went wrong",
                exception: $e,
            );
        }
    }
}
