<?php

namespace App\Core\UseCases\Comment;

use App\Models\Comment;
use App\Models\User;
use Exception;

class DeleteCommentUseCase
{
    public function execute(Comment $comment, User $user): void
    {
        if($comment->user_id != $user->id) {
            throw new Exception("You don't have permissions to delete this comment");
        }

        $comment->delete();
    }
}