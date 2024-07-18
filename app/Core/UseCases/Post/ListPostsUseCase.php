<?php

namespace App\Core\UseCases\Post;

use App\Models\Post;
use App\Models\User;

class ListPostsUseCase
{
    //dashboard
    public function execute(array $filters, User $user)
    {
        $posts = Post::with([
                            'user' => function($q) {
                                return $q->select('id', 'name', 'username');
                            },
                            'files'
                        ])
                        ->withCount('comments')
                        ->where('user_id', '!=', $user->id)
                        ->paginate();

        return $posts;
    }
}