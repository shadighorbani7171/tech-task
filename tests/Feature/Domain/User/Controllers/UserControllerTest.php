<?php

namespace Tests\Feature\Domain\User\Controllers;

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
        User::query()->delete(); // Clear any existing users
        $users = User::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
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
                            'introduction',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'total',
                    'per_page',
                    'current_page',
                    'last_page'
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
            'phone' => '+1' . random_int(1000000000, 9999999999),
            'country' => 'US',
            'gender' => 'male',
            'introduction' => $this->faker->paragraph,
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'surname',
                    'email',
                    'phone',
                    'country',
                    'gender',
                    'introduction',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'name' => $userData['name'],
            'surname' => $userData['surname']
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
                'password'
            ]);
    }

    /** @test */
    public function it_can_show_user_details()
    {
        $user = User::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_when_user_not_found()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/users/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_user()
    {
        $user = User::factory()->create();
        $updateData = [
            'name' => 'Updated Name',
            'surname' => 'Updated Surname',
            'introduction' => 'Updated introduction'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => 'Updated Name',
                    'surname' => 'Updated Surname',
                    'introduction' => 'Updated introduction'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'surname' => 'Updated Surname'
        ]);
    }

    /** @test */
    public function it_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_validates_email_uniqueness()
    {
        $existingUser = User::factory()->create();
        $userData = User::factory()->make([
            'email' => $existingUser->email
        ])->toArray();
        $userData['password'] = 'password123';
        $userData['password_confirmation'] = 'password123';

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/users', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_fetch_countries_list()
    {
        $response = $this->getJson('/api/v1/countries');

        $response->assertStatus(200);
    }
} 