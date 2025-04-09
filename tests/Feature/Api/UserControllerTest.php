<?php

namespace Tests\Feature\Api;

use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create and authenticate a user for each test
        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);
    }

    /** @test */
    public function it_can_list_users()
    {
        // Create some test users
        User::factory()->count(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'surname',
                            'email',
                            'phone',
                            'country',
                            'gender',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'current_page',
                    'total'
                ]
            ]);
    }

    /** @test */
    public function it_can_create_new_user()
    {
        $userData = [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '+1' . $this->faker->numerify('##########'),
            'country' => 'US',
            'gender' => 'male',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'introduction' => $this->faker->paragraph
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'surname',
                    'email',
                    'phone',
                    'country',
                    'gender'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'phone' => $userData['phone']
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_user()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/users', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'surname',
                'email',
                'phone',
                'country',
                'gender',
                'password'
            ]);
    }

    /** @test */
    public function it_can_update_user()
    {
        $user = User::factory()->create();
        
        $updateData = [
            'name' => 'Updated Name',
            'surname' => 'Updated Surname',
            'phone' => '+1234567890'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'User updated successfully'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'surname' => 'Updated Surname',
            'phone' => '+1234567890'
        ]);
    }

    /** @test */
    public function it_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    /** @test */
    public function it_returns_404_when_user_not_found()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/users/999');

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'User not found'
            ]);
    }

    /** @test */
    public function it_can_get_countries_list()
    {
        $response = $this->getJson('/api/v1/countries');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'IR',
                    'US',
                    'GB',
                    'DE',
                    'FR'
                ]
            ]);
    }
} 