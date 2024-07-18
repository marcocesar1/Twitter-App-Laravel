<?php

namespace App\Core\UseCases\Post;

use App\Models\Post;

class ShowPostUseCase
{
    public function execute(string $id): Post
    {
        $post = Post::with([
                    'files' => function($query){
                        $query->select('files.id', 'path');
                    },
                    'comments' => function($query){
                        $query->select('id', 'post_id', 'user_id','body', 'created_at');
                    },
                    'comments.user' => function($query){
                        $query->select('id', 'username', 'file_id');
                    },
                    'comments.user.profileImg' => function($query){
                        $query->select('id', 'path');
                    },
                ])
                ->findOrFail($id);

        return $post;
    }
}