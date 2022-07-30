<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeViewTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker; 

    protected $user;

    public function setUp() : void
    {
        parent::setUp();
        
        // set authenticated user
        $this->seed();
        $this->user = User::firstWhere('email', 'yudha@yudha.com');
        $this->actingAs($this->user);
    }

    public function test_home_view_can_be_rendered()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/home');
        $response->assertViewIs('home');
    }
}
