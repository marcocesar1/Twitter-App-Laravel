<?php

namespace App\Core\UseCases\Profile;

use App\Models\Post;
use App\Models\User;

class ListProfilePostsUseCase
{
    //my posts
    public function execute(array $filters, User $user)
    {
        $posts = Post::withCount('comments')
                        ->where('user_id', $user->id)
                        ->paginate();

        return $posts;
    }
}