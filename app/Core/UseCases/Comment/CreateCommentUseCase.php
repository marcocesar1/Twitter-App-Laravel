<?php

namespace App\Core\UseCases\Comment;

use App\Models\Comment;
use App\Models\User;

class CreateCommentUseCase
{
    public function execute(array $commentData, User $user): Comment
    {
        $comment = new Comment($commentData);
        $comment->user_id = $user->id;
        $comment->save();

        return $comment;
    }
}