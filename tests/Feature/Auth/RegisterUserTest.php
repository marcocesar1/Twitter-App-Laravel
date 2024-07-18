<?php
 
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
 
class RegisterUserTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function user_can_register(): void
    {
        $data = [
            'name' => 'John',
            'username' => 'john01',
            'email' => 'john01@email.test',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson(
            uri: route('register'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['id', 'email', 'name', 'username'],
        ]);
        $response->assertJson([
            'data' => [
                "name"=> "John",
                "email"=> "john01@email.test",
                "username"=> "john01",
            ]
        ]);
        
        $this->assertDatabaseHas('users', [
            'name' => 'John',
            'username' => 'john01',
            'email' => 'john01@email.test',
        ]);

    }

    #[Test]
    public function email_must_be_required(): void
    {
        $data = [
            'name' => 'John',
            'username' => 'john01',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson(
            uri: route('register'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The email field is required.',
            'errors' => [
                'email' => ['The email field is required.']
            ]
        ]);
    }
    
    #[Test]
    public function email_must_be_valid_format_email(): void
    {
        $data = [
            'name' => 'John',
            'email' => 'john@',
            'username' => 'john01',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson(
            uri: route('register'),
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
    public function email_must_be_unique_email(): void
    {
        User::factory()->create(['email' => 'john@email.test']);

        $data = [
            'name' => 'John',
            'email' => 'john@email.test',
            'username' => 'john01',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson(
            uri: route('register'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The email has already been taken.',
            'errors' => [
                'email' => ['The email has already been taken.']
            ]
        ]);
    }
    
    #[Test]
    public function name_must_be_required(): void
    {
        $data = [
            'email' => 'john@email.test',
            'username' => 'john01',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson(
            uri: route('register'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The name field is required.',
            'errors' => [
                'name' => ['The name field is required.']
            ]
        ]);
    }
    
    #[Test]
    public function username_must_be_required(): void
    {
        $data = [
            'name' => 'John',
            'email' => 'john@email.test',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson(
            uri: route('register'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The username field is required.',
            'errors' => [
                'username' => ['The username field is required.']
            ]
        ]);
    }
    
    #[Test]
    public function username_must_be_unique(): void
    {
        User::factory()->create(['username' => 'john01']);

        $data = [
            'name' => 'John',
            'email' => 'john@email.test',
            'username' => 'john01',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson(
            uri: route('register'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The username has already been taken.',
            'errors' => [
                'username' => ['The username has already been taken.']
            ]
        ]);
    }

    #[Test]
    public function password_must_be_required(): void
    {
        $data = [
            'name' => 'John',
            'username' => 'john01',
            'email' => 'john@email.test',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson(
            uri: route('register'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The password field is required.',
            'errors' => [
                'password' => ['The password field is required.']
            ]
        ]);
    }
    
    #[Test]
    public function password_must_be_at_least_8_characters(): void
    {
        $data = [
            'name' => 'John',
            'username' => 'john01',
            'email' => 'john@email.test',
            'password' => 'pass',
            'password_confirmation' => 'pass'
        ];

        $response = $this->postJson(
            uri: route('register'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The password field must be at least 8 characters.',
            'errors' => [
                'password' => ['The password field must be at least 8 characters.']
            ]
        ]);
    }
    
    #[Test]
    public function password_must_be_confimed(): void
    {
        $data = [
            'name' => 'John',
            'username' => 'john01',
            'email' => 'john@email.test',
            'password' => 'password',
        ];

        $response = $this->postJson(
            uri: route('register'),
            data: $data
        );
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The password field confirmation does not match.',
            'errors' => [
                'password' => ['The password field confirmation does not match.']
            ]
        ]);
    }
}