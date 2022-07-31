<?php

namespace Tests\Unit;

use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_register()
    {
        $this->post('/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ])
        ->assertRedirect('/home');
    }

    public function test_login()
    {
        $this->post('/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $this->post('/login', [
            'email' => 'test@test.com',
            'password' => '12345678',
        ])
        ->assertRedirect('/home');
    }
}
