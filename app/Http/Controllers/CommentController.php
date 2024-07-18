<?php

namespace App\Http\Controllers;

use App\Models\Comment;

use App\Core\UseCases\Comment\CreateCommentUseCase;
use App\Core\UseCases\Comment\DeleteCommentUseCase;
use App\Core\UseCases\Comment\UpdateCommentUseCase;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;

use Exception;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, CreateCommentUseCase $usecase)
    {
        try {
            $post = $usecase->execute(
                commentData: $request->all(),
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment, UpdateCommentUseCase $usecase)
    {
        try {
            $post = $usecase->execute(
                commentData: $request->all(),
                comment: $comment
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
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment, DeleteCommentUseCase $usecase)
    {
        try {
            $post = $usecase->execute(
                comment: $comment
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
}