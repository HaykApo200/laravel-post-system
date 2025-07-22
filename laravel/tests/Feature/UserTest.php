<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_signup(): void
    {
        $response = $this->post('/signup',
            [
                'username' => 'test',
                'email' => 'test@gmail.com',
                'password' => 'Test2Test!',
                'password_confirmation' => 'Test2Test!',
            ]
        );

        $response->assertStatus(201)
                 ->assertJson(['message' => 'User created successfully.']);
    }
}
