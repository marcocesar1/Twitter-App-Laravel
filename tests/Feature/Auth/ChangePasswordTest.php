<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;

    #[Test]
    public function logged_user_can_change_password(): void
    {
        $data = [
            'current_password' => '12345678',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->token,
                    ])->postJson(
                        uri: route('change-password'),
                        data: $data
                    );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'message'
            ]
        ]);
        $response->assertJsonFragment([
            'data' => [
                'message' => 'Password updated'
            ]
        ]);
    }

    #[Test]
    public function current_password_must_be_required(): void
    {
        $data = [
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->token,
                    ])->postJson(
                        uri: route('change-password'),
                        data: $data
                    );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The current password field is required.',
            'errors' => [
                'current_password' => ['The current password field is required.']
            ]
        ]);
    }
    
    #[Test]
    public function password_must_be_required(): void
    {
        $data = [
            'current_password' => '12345678',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->token,
                    ])->postJson(
                        uri: route('change-password'),
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
    
    #[Test]
    public function password_confirmation_does_not_match(): void
    {
        $data = [
            'current_password' => '12345678',
            'password' => 'new_password',
            'password_confimration' => 'new_password1',
        ];

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $this->token,
                    ])->postJson(
                        uri: route('change-password'),
                        data: $data
                    );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'The password field confirmation does not match.',
            'errors' => [
                'password' => ['The password field confirmation does not match.']
            ]
        ]);
    }

    #[Test]
    public function user_not_logged_cant_change_password(): void
    {
        $response = $this->postJson(
            uri: route('change-password'),
            data: []
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'message'
        ]);
        $response->assertJsonFragment([
            'message' => 'Unauthenticated.'
        ]);
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
