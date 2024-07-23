<?php

namespace Tests\Feature\Post;

use App\Models\Comment;
use App\Models\File;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UpdateCommentTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;

    #[Test]
    public function a_authenticated_user_can_update_comment()
    {
        $user = User::first();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        $data = [
            'body' => 'My comment updated'
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->putJson(
                            uri: route('update_comment', [
                                'comment' => $comment->id,
                            ]),
                            data: $data,
                        );

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data'
        ]);
        $response->assertJson([
            'data' => [
                'body' => 'My comment updated'
            ]
        ]);
    }
    
    #[Test]
    public function body_must_be_required()
    {
        $user = User::first();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        $data = [
            'post_id' => $post->id,
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->putJson(
                            uri: route('update_comment', [
                                'comment' => $comment->id,
                            ]),
                            data: $data,
                        );

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The body field is required.',
            'errors' => [
                'body' => ['The body field is required.']
            ]
        ]);
    }
    
    #[Test]
    public function comment_id_must_be_valid()
    {
        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->putJson(
                            uri: route('update_comment', [
                                'comment' => 345,
                            ]),
                            data: [],
                        );

        $response->assertNotFound();
    }

    #[Test]
    public function a_unauthenticated_user_cannot_update_comment()
    {
        $response = $this->putJson(
            uri: route('update_comment', [
                'comment' => 100
            ])
        );

        $response->assertUnauthorized();
    }
    
    protected function setUp(): void
    {
        parent::setUp();

        $userData =[
            'email' => 'john@email.test',
            'password' => '12345678',
        ];
        
        User::factory()->create($userData);

        $response = $this->postJson(
            uri: route('login'),
            data: $userData
        );

        $this->token = $response->json('data.token');
    }
}
