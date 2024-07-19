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

class UpdatePostTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $token;

    #[Test]
    public function a_authenticated_user_can_update_their_posts()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $data = [
            'body' => 'My second post!',
            'files' => []
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->putJson(
                            uri: route('update_post', [
                                'post' => $post->id,
                            ]),
                            data: $data
                        );

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => ['id', 'body']
        ]);
        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'body' => 'My second post!',
            ]
        ]);
    }
    
    #[Test]
    public function body_must_be_required()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $data = [
            'files' => []
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->putJson(
                            uri: route('update_post', [
                                'post' => $post->id,
                            ]),
                            data: $data
                        );

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The body field is required.'
        ]);
    }
    
    #[Test]
    public function files_array_must_be_present()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $data = [
            'body' => 'My second post',
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->putJson(
                            uri: route('update_post', [
                                'post' => $post->id,
                            ]),
                            data: $data
                        );

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJson([
            'message' => 'The files field must be present.'
        ]);
    }

    #[Test]
    public function a_authenticated_user_can_update_only_their_posts()
    {
        $post = Post::factory()->withUser()->create();

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->putJson(
                            uri: route('update_post', [
                                'post' => $post->id,
                            ]),
                        );

        $response->assertUnprocessable();
    }
    
    #[Test]
    public function a_authenticated_user_cannot_update_a_post_with_invalid_id()
    {
        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->putJson(
                            uri: route('update_post', [
                                'post' => 400,
                            ]),
                        );

        $response->assertNotFound();
    }
    
    #[Test]
    public function a_authenticated_user_cannot_edit_a_post()
    {
        $response = $this->putJson(
                            uri: route('update_post', [
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
