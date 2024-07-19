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

class ShowPostTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;

    #[Test]
    public function a_authenticated_can_see_a_post()
    {
        $post = Post::factory()->withUser()->create();

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->getJson(
                            uri: route('show_post', [
                                'post' => $post->id,
                            ]),
                        );

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data'
        ]);
        $response->assertJson([
            'data' => [
                'id' => 1,
                'body' => $post->body,
            ]
        ]);
    }
    
    #[Test]
    public function a_authenticated_user_cannot_see_a_post_with_invalid_id()
    {
        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->getJson(
                            uri: route('show_post', [
                                'post' => 400,
                            ]),
                        );

        $response->assertNotFound();
    }
    
    #[Test]
    public function a_unauthenticated_user_cannot_see_a_post()
    {
        $response = $this->getJson(
                            uri: route('show_post', [
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
        
        User::factory()->create($userData);

        $response = $this->postJson(
            uri: route('login'),
            data: $userData
        );

        $this->token = $response->json('data.token');
    }
}
