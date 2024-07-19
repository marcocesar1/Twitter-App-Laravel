<?php

namespace App\Http\Controllers;

use App\Models\Post;

use App\Core\UseCases\Post\{
    ListPostsUseCase,
    CreatePostUseCase,
    UpdatePostUseCase,
    DeletePostUseCase,
    ShowPostUseCase
};
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ListPostsUseCase $usecase)
    {
        try {
            $posts = $usecase->execute(
                filters: $request->all(),
                user: Auth::user(),
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request, CreatePostUseCase $usecase)
    {
        try {
            $post = $usecase->execute(
                postData: $request->all(),
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

    /**
     * Display the specified resource.
     */
    public function show(string $id, ShowPostUseCase $usecase)
    {        
        $post = $usecase->execute(
            id: $id
        );
        
        return new ApiSuccessResponse(
            data: $post
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Post $post, UpdatePostRequest $request, UpdatePostUseCase $usecase)
    {
        try {
            $post = $usecase->execute(
                postData: $request->all(),
                post: $post,
                user: Auth::user(),
            );

            return new ApiSuccessResponse(
                data: $post
            );
        } catch (Exception $e) {
            return new ApiErrorResponse(
                message: "Something went wrong: {$e->getMessage()}",
                exception: $e,
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post, DeletePostUseCase $usecase)
    {
        try {
            $usecase->execute(
                post: $post,
                user: $request->user(),
            );

            return new ApiSuccessResponse(
                message: 'Post deleted'
            );
        } catch (Exception $e) {
            return new ApiErrorResponse(
                message: "Something went wrong",
                exception: $e,
            );
        }
    }
}
