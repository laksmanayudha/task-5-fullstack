<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $user;

    public function setUp() : void
    {
        parent::setUp();

        // create a user and acting as it
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');

        // seed database
        $this->seed();
    }

    public function test_store_post()
    {
        // prepare input data
        $formData = [
            'title' => 'test store title',
            'content' => 'test store content',
            'category_id' => $this->faker->randomElement(Category::all()->modelKeys())
        ];
        
        // test storing data
        $this->withoutExceptionHandling();
        $this->post('/api/v1/post/create', $formData)
            ->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'success to create a post',
                'data' => [
                    'post' => $formData
                ]
            ]);
    }

    public function test_update_post()
    {

        // create a post
        $post = Post::factory()->make();

        // save a post by user
        $this->user->posts()->save($post);

        // prepare updated data
        $updatedData = [
            'title' => 'test update title',
            'content' => 'test update content',
            'category_id' => $this->faker->randomElement(Category::all()->modelKeys())
        ];
        
        // test update post data
        $this->withoutExceptionHandling();
        $this->post("/api/v1/post/update/$post->id", $updatedData)
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'success to update post',
                'data' => [
                    'post' => $updatedData
                ]
            ]);
    }

    public function test_detail_post()
    {
        // craete a post
        $post = Post::factory()->make();

        // save a post by user
        $this->user->posts()->save($post);

        // test get detail of the post
        $this->get("/api/v1/post/detail/$post->id")->assertStatus(200);
    }

    public function test_delete_post()
    {
        // craete a post
        $post = Post::factory()->make();

        // save a post by user
        $this->user->posts()->save($post);

        // test delete post
        $this->post("/api/v1/post/delete/$post->id")->assertStatus(200);
    }

    public function test_list_all_post()
    {
        // create some posts
        $posts = Post::factory()->count(3)->make();

        // save posts by user
        $this->user->posts()->saveMany($posts);

        // test get all post
        $this->get('/http://localhost:8000/api/v1/post/all')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'posts' => [ '*' => ['id', 'title', 'content', 'image', 'user_id', 'category_id'] ]
                ]
            ]);
    }

    public function test_pagination_post()
    {
        // create some posts
        $posts = Post::factory()->count(3)->make();

        // save posts by user
        $this->user->posts()->saveMany($posts);

        // test get all post
        $this->get('/http://localhost:8000/api/v1/post/all?page=1')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'posts' => [ 
                        'current_page',
                        'data'=> [ '*' => ['id', 'title', 'content', 'image', 'user_id', 'category_id'] ],
                        'first_page_url',
                        'from',
                        'last_page',
                        'last_page_url',
                        'next_page_url',
                        'path',
                        'per_page',
                        'prev_page_url',
                        'to',
                        'total'
                    ]
                ]
            ]);
    }

    public function test_upload_image_post()
    {

        // prepare file upload
        $file = UploadedFile::fake()->image('test.jpg', 200, 200);
        $folder = 'images';

        // prepare input data
        $formData = [
            'title' => 'test store upload title',
            'content' => 'test store upload content',
            'image' => $file,
            'category_id' => $this->faker->randomElement(Category::all()->modelKeys())
        ];

        // post the data
        $this->withoutExceptionHandling();
        $this->post('/api/v1/post/create', $formData);
        
        // test uploaded file exist in storage
        Storage::disk('local')->assertExists($folder . DIRECTORY_SEPARATOR . $file->hashName());

    }

    public function test_update_image_post()
    {

        // prepare file upload
        $file = UploadedFile::fake()->image('test-update.jpg', 200, 200);
        $folder = 'images';

        // create a post
        $post = Post::factory()->make();

        // save a post by user
        $this->user->posts()->save($post);

        // prepare updated data
        $updatedData = [
            'title' => 'test update upload title',
            'content' => 'test update upload content',
            'image' => $file,
            'category_id' => $this->faker->randomElement(Category::all()->modelKeys())
        ];
        
        // update image data
        $this->withoutExceptionHandling();
        $this->post("/api/v1/post/update/$post->id", $updatedData);

         // test updated file exist in storage
         Storage::disk('local')->assertExists($folder . DIRECTORY_SEPARATOR . $file->hashName());
    }
}
