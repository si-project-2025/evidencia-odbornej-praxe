<?php

namespace Tests\Feature;

use Tests\TestCase;

class garantAuthTest extends TestCase
{
    public function test_garant_can_login_successfully(): void
    {
        $credentials = [
            'email' => 'garant@ukf.sk',
            'password' => '12345678',
        ];

        $response = $this->postJson('/api/login', $credentials);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'user',
        ]);
    }

    public function test_garant_cannot_login_with_wrong_password(): void
    {
        $credentials = [
            'email' => 'garant@ukf.sk',
            'password' => 'zleheslo',
        ];

        $response = $this->postJson('/api/login', $credentials);

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Nesprávne prihlasovacie údaje.'
        ]);
    }

    public function test_garant_cannot_login_with_wrong_email(): void
    {
        $credentials = [
            'email' => 'garantt@ukf.sk',
            'password' => '12345678',
        ];

        $response = $this->postJson('/api/login', $credentials);

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Nesprávne prihlasovacie údaje.'
        ]);
    }
}
