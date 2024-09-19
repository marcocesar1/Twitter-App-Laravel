<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function a_existing_user_can_login(): void
    {
        $data = [
            'email' => 'john@email.com',
            'password' => 'password',
        ];

        $user = User::factory()->create($data);

        $response = $this->postJson(
            uri: route('login'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['user', 'token']
        ]);
        $response->assertJson([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                ]
            ]
        ]);
    }
    
    #[Test]
    public function a_existing_user_with_invalid_password_cannot_login()
    {
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'password' => 'invalid_pasword',
        ];

        $response = $this->postJson(
            uri: route('login'),
            data: $data
        );

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonFragment([
            'message' => 'Invalid credentials'
        ]);
    }

     #[Test]
     public function a_non_existing_user_cannnot_login(): void
     {
         $data = [
             'email' => 'unknown_email@email.com',
             'password' => 'password',
         ];
 
         $response = $this->postJson(
             uri: route('login'),
             data: $data
         );
  
         $response->assertStatus(Response::HTTP_NOT_FOUND);
         $response->assertJsonStructure([
             'message'
         ]);
         $response->assertJsonFragment([
             'message' => 'User does not exist',
         ]);
     }

    #[Test]
    public function email_must_be_valid_format_email(): void
    {
        $data = [
            'email' => 'john@',
            'password' => 'password',
        ];

        $response = $this->postJson(
            uri: route('login'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The email field must be a valid email address.',
            'errors' => [
                'email' => ['The email field must be a valid email address.']
            ]
        ]);
    }

    #[Test]
    public function email_must_be_required(): void
    {
        $data = [
            'password' => 'password',
        ];

        $response = $this->postJson(
            uri: route('login'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The email field is required.',
            'errors' => [
                'email' => ['The email field is required.']
            ]
        ]);
    }

    #[Test]
    public function password_must_be_required(): void
    {
        $data = [
            'email' => 'john@email.com'
        ];

        $response = $this->postJson(
            uri: route('login'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The password field is required.',
            'errors' => [
                'password' => ['The password field is required.']
            ]
        ]);
    }
}
