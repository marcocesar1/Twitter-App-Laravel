<?php

namespace Tests\Feature\Post;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;

    #[Test]
    public function user_can_create_post_with_files()
    {
        $file = File::factory()->create();
        
        $data = [
            'body' => 'my first post',
            'files' => [$file->id]
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->postJson(
                            uri: route('posts'),
                            data: $data,
                        );

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data'
        ]);
        $response->assertJson([
            'data' => [
                'id' => 1,
                'body' => 'my first post'
            ]
        ]);
        $response->assertJsonIsArray('data.files');
        $response->assertJsonCount(1, 'data.files');
    }

    #[Test]
    public function user_can_create_post_without_files()
    {
        $data = [
            'body' => 'my first post',
            'files' => []
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->postJson(
                            uri: route('posts'),
                            data: $data,
                        );

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data'
        ]);
        $response->assertJson([
            'data' => [
                'id' => 1,
                'body' => 'my first post'
            ]
        ]);
    }

    #[Test]
    public function body_must_be_required()
    {
        $data = [
            'files' => []
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->postJson(
                            uri: route('posts'),
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
    public function files_array_must_be_present()
    {
        $data = [
            'body' => 'my first post'
        ];

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->postJson(
                            uri: route('posts'),
                            data: $data,
                        );

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The files field must be present.',
            'errors' => [
                'files' => ['The files field must be present.']
            ]
        ]);
    }

    #[Test]
    public function user_not_logged_cant_create_post()
    {
        $response = $this->postJson(
            uri: route('posts')
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
