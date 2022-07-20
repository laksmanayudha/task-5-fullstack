<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    // use RefreshDatabase;
    protected $user;

    public function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_store_post()
    {
        $formData = [
            'title' => 'test title',
            'content' => 'test content',
            'category_id' => 1
        ];
        
        $this->withoutExceptionHandling();
        $this->postJson('/api/v1/post/create', $formData)
            ->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'success to create a post',
                'data' => [
                    'post' => $formData
                ]
            ]);
    }
}
