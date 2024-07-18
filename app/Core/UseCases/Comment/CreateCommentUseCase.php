<?php

namespace App\Core\UseCases\Comment;

use App\Models\Comment;

class CreateCommentUseCase
{
    public function execute(array $commentData): Comment
    {
        $comment = new Comment($commentData);
        $comment->save();

        return $comment;
    }
}