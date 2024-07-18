<?php

namespace App\Core\UseCases\Post;

use App\Models\Post;
use App\Models\User;

class CreatePostUseCase
{
    public function execute(array $postData, User $user): Post
    {
        $post = new Post($postData);

        $user->posts()->save($post);

        if( count($postData['files']) ) {
            $post->files()->attach(
                $postData['files']
            );
        }

        $post->load('files');

        return $post;
    }
}