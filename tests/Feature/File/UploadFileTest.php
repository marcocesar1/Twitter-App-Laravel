<?php

namespace Tests\Feature\File;

use App\Models\File;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Storage;

class UploadFileTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;

    #[Test]
    public function a_authenticated_user_can_upload_file()
    {
        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.png');

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->postJson(
                            uri: route('store_files'),
                            data: ['image' => $file]
                        );

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                'id', 'path', 'mime_type'
            ]
        ]);
        $response->assertJson([
            'data' => [
                'mime_type' => 'image/png'
            ]
        ]);
    }

    #[Test]
    public function image_must_be_an_image()
    {
        $file = UploadedFile::fake()->create('mydoc.doc');

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->postJson(
                            uri: route('store_files'),
                            data: ['image' => $file]
                        );

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The image field must be an image.',
            'errors' => [
                'image' => ['The image field must be an image.']
            ]
        ]);
    }

    #[Test]
    public function image_must_be_required()
    {
        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token,
                        ])->postJson(
                            uri: route('store_files')
                        );

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The image field is required.',
            'errors' => [
                'image' => ['The image field is required.']
            ]
        ]);
    }

    #[Test]
    public function a_unauthenticated_user_cannot_upload_file()
    {
        $response = $this->postJson(
            uri: route('store_files')
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
