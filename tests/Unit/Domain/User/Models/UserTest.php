<?php

namespace Tests\Unit\Domain\User\Models;

use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_user_using_factory()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotEmpty($user->name);
        $this->assertNotEmpty($user->surname);
        $this->assertNotEmpty($user->email);
        $this->assertNotEmpty($user->phone);
        $this->assertContains($user->country, ['US', 'GB', 'DE', 'FR', 'IR']);
        $this->assertContains($user->gender, ['male', 'female']);
        $this->assertNotEmpty($user->introduction);
    }

    /** @test */
    public function it_can_create_multiple_users_using_factory()
    {
        $users = User::factory()->count(3)->create();

        $this->assertCount(3, $users);
        $this->assertDatabaseCount('users', 3);
    }

    /** @test */
    public function it_hashes_password_when_creating_user()
    {
        $user = User::factory()->create([
            'password' => 'password'
        ]);

        $this->assertNotEquals('password', $user->password);
        $this->assertTrue(password_verify('password', $user->password));
    }
} 