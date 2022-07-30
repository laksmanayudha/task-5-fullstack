<?php

namespace Tests\Unit;

use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\UploadedFile;

class BlogCrudFeatureTest extends TestCase
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

    public function test_blogs_page_view_can_be_rendered()
    {

        // test blog page view can be rendered and posts data are exists
        $this->withoutExceptionHandling();
        $response = $this->get('/blogs');
        $response->assertViewIs('blog')
                ->assertSee(['Blogs Page', 'add new article'])
                ->assertViewHas('posts');
    }

    public function test_create_blog_view_can_be_rendered()
    {

        // test create blog  view can be rendered with specific appropriate form url and button text
        $this->withoutExceptionHandling();
        $response = $this->get('/blog/form');
        $response->assertViewIs('form')
                ->assertSee('Blog Form')
                ->assertViewHasAll([
                    'categories',
                    'form_url' => '/blog/create',
                    'submit_text' => 'Create',
                ]);
    }

    public function test_update_blog_view_can_be_rendered()
    {

        // create a post
        $post = Post::factory()->make();

        // save a post by user
        $this->user->posts()->save($post);

        // test update form view can be rendered with the edited post data displayed
        $this->withoutExceptionHandling();
        $response = $this->get('/blog/formUpdate/' . $post->id);
        $response->assertViewIs('form')
                ->assertSee('Blog Form')
                ->assertViewHasAll([
                    'categories',
                    'form_url' => '/blog/update/' . $post->id,
                    'submit_text' => 'Update',
                    'post' => $post
                ]);
    }

    public function test_create_blog_successfuly_inserted_and_displayed()
    {

        // prepare blog data
        $file = UploadedFile::fake()->image('test.jpg', 200, 200);
        $data = [
            'title' => 'test title',
            'content' => 'test content',
            'image' => $file,
            'category_id' => $this->faker->randomElement(Category::all()->modelKeys())
        ];

        $this->withoutExceptionHandling();

        // insert blog data and check redirect route
        $this->post('/blog/create', $data)->assertRedirect('/blogs');

        // check if data displayed in /blogs
        $this->get('/blogs')
            ->assertSee(
                [
                    'test title', 
                    'test content', 
                    Category::find($data['category_id'])->name
                ]
            );

        // // alternative test
        // $this->followingRedirects()
        //     ->post('/blog/create', $data)            
        //     ->assertSee(
        //     [
        //         'test title', 
        //         'test content', 
        //         Category::find($data['category_id'])->name
        //     ]
        // );

    }

    public function test_update_blog_successfuly_inserted_and_displayed()
    {

        // create a post
        $post = Post::factory()->make();

        // save a post by user
        $this->user->posts()->save($post);

        $this->withoutExceptionHandling();

        // test update form view can be rendered with the old post data displayed
        // $this->withoutExceptionHandling();
        $response = $this->get('/blog/formUpdate/' . $post->id);
        $response->assertViewIs('form')
                ->assertSee('Blog Form')
                ->assertViewHasAll([
                    'categories',
                    'form_url' => '/blog/update/' . $post->id,
                    'submit_text' => 'Update',
                    'post' => $post
                ]);
        

        // prepare updated blog data
        $file = UploadedFile::fake()->image('test.jpg', 200, 200);
        $data = [
            'title' => 'test title',
            'content' => 'test content',
            'image' => $file,
            'category_id' => $this->faker->randomElement(Category::all()->modelKeys())
        ];

        // update blog data
        $this->post('/blog/update/' . $post->id, $data)->assertRedirect('/blogs');
        
        // check if data displayed in /blogs
        $this->get('/blogs')
        ->assertSee(
            [
                'test title', 
                'test content', 
                Category::find($data['category_id'])->name
            ]
        );
    }

    public function test_delete_blog()
    {
        // prepare blog data
        $deleted_id = $this->faker->randomElement(Post::all()->modelKeys());

        // select blog data
        $post = Post::find($deleted_id);

        // delete blog data
        $this->post('/blog/delete/' . $post->id)->assertRedirect('/blogs');
    }
}
