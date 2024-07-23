<?php

namespace Tests\Feature\Post;

use App\Models\File;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;

    #[Test]
    public function a_authenticated_user_can_create_comment()
    {
        $user = User::first();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $data = [
            'body' => 'My comment',
            'post_id' => $post->id
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->postJson(
                            uri: route('store_comment'),
                            data: $data,
                        );

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data'
        ]);
        $response->assertJson([
            'data' => [
                'body' => 'My comment',
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]
        ]);
    }

    #[Test]
    public function post_id_must_be_valid_id()
    {
        $data = [
            'body' => 'My comment',
            'post_id' => 752
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->postJson(
                            uri: route('store_comment'),
                            data: $data,
                        );

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The selected post id is invalid.',
            'errors' => [
                'post_id' => ['The selected post id is invalid.']
            ]
        ]);
    }

    #[Test]
    public function post_id_must_be_required()
    {
        $data = [
            'body' => 'My comment'
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->postJson(
                            uri: route('store_comment'),
                            data: $data,
                        );

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The post id field is required.',
            'errors' => [
                'post_id' => ['The post id field is required.']
            ]
        ]);
    }
    
    #[Test]
    public function body_must_be_required()
    {
        $user = User::first();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $data = [
            'post_id' => $post->id,
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->postJson(
                            uri: route('store_comment'),
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
    public function a_unauthenticated_user_cannot_create_comment()
    {
        $response = $this->postJson(
            uri: route('store_comment')
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
