<?php

namespace Tests\Feature\Post;

use App\Models\File;
use App\Models\Post;
use App\Models\User;
use Database\Factories\PostFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $token;

    #[Test]
    public function a_authenticated_user_can_delete_their_posts()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->deleteJson(
                            uri: route('delete_post', [
                                'post' => $post->id,
                            ]),
                        );

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => ['message']
        ]);
        $response->assertJsonFragment([
            'data' => [
                'message' => 'Post deleted'
            ]
        ]);
    }

    #[Test]
    public function a_authenticated_user_can_delete_only_their_posts()
    {
        $post = Post::factory()->withUser()->create();

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->deleteJson(
                            uri: route('delete_post', [
                                'post' => $post->id,
                            ]),
                        );

        $response->assertUnprocessable();
    }
    
    #[Test]
    public function a_authenticated_user_cannot_delete_a_post_with_invalid_id()
    {
        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->deleteJson(
                            uri: route('delete_post', [
                                'post' => 400,
                            ]),
                        );

        $response->assertNotFound();
    }
    
    #[Test]
    public function a_authenticated_user_cannot_see_a_post()
    {
        $response = $this->deleteJson(
                            uri: route('delete_post', [
                                'post' => 400,
                            ]),
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
        
        $this->user = User::factory()->create($userData);

        $response = $this->postJson(
            uri: route('login'),
            data: $userData
        );

        $this->token = $response->json('data.token');
    }
}
