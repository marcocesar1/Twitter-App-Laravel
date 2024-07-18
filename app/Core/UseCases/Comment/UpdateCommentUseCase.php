<?php

namespace App\Core\UseCases\Comment;

use App\Models\Comment;

class UpdateCommentUseCase
{
    public function execute(array $commentData, Comment $comment): Comment
    {
        $comment->fill($commentData);
        $comment->save();

        return $comment;
    }
}