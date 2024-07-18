<?php

namespace App\Core\UseCases\Post;

use App\Models\Post;
use App\Models\User;
use Exception;

class UpdatePostUseCase
{
    public function execute(array $postData, Post $post, User $user): Post
    {
        if($post->user_id != $user->id) {
            throw new Exception("You don't have permissions to edit this post");
        }

        $post->fill($postData);
        $post->save();

        if( count($postData['files']) ) {
            $post->files()->sync(
                $postData['files']
            );
        }

        $post->refresh();

        return $post;
    }
}