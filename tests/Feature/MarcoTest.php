<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MarcoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
    }

    #[Test]
    public function create_user(): void
    {
        $response = $this->get('/');

        $users = User::get();

        echo $users->count();

        $response->assertStatus(200);
    }
    
    #[Test]
    public function delete_user(): void
    {
        $response = $this->get('/');

        User::factory()->create();
        
        $users = User::get();


        echo $users->count();

        echo "Hola";

        $response->assertStatus(200);
    }
}
