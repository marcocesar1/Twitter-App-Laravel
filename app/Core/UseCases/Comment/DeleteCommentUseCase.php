<?php

namespace App\Core\UseCases\Comment;

use App\Models\Comment;

class DeleteCommentUseCase
{
    public function execute(Comment $comment): void
    {
        $comment->delete();
    }
}