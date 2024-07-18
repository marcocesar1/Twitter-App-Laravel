<?php

namespace App\Core\UseCases\Post;

use App\Models\Post;
use App\Models\User;
use Exception;

class DeletePostUseCase
{
    public function execute(Post $post, User $user): bool
    {
        if($post->user_id != $user->id) {
            throw new Exception("You don't have permissions to delete this post");
        }

        return $post->delete();
    }
}