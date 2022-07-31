<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class CategoryCrudFeatureTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->seed();
        $this->user = User::firstWhere('email', 'yudha@yudha.com');
        $this->actingAs($this->user);
    }

    public function test_category_view_can_be_rendered()
    {

        // test get all categories
        $this->get('/categories')
            ->assertStatus(200)
            ->assertViewIs('categories')
            ->assertSee('Category Form')
            ->assertViewHas('categories');
    }

    public function test_create_category_successfuly_inserted_and_displayed()
    {
        // check if category not displayed in view
        $this->get('/categories')
        ->assertDontSee('test_category');

        // prepare category data
        $data = [ 'name' => 'test_category' ];

        // test create category
        $this->followingRedirects()->post('/category/create', $data)
            ->assertStatus(200)
            ->assertViewIs('categories')
            ->assertSee('<td>test_category</td>', false);
    }

    public function test_update_category_successfuly_saved_and_displayed()
    {
        // prepare category data
        $category = new Category([ 'name' => 'create_category' ]);

        // prepare updated category data
        $updated_category = [ 'name' => 'updated_test_category' ];

        // save to database
        $this->user->categories()->save($category);

        // check if category displayed in view
        $this->get('/categories')
            ->assertDontSee('<td>updated_test_category</td>', false)
            ->assertSee("<td>$category->name</td>", false);


        // save category in database
        $this->user->categories()->save($category);

        // update category
        $this->post('/category/update/' . $category->id, $updated_category)
            ->assertRedirect('/categories');

        // check if category data in view is updated
        $this->get('/categories')
            ->assertViewIs('categories')
            ->assertDontSee("<td>$category->name</td>", false)
            ->assertSee('<td>updated_test_category</td>', false);
    }

    public function test_delete_category_success_and_not_displayed()
    {
        // prepare category data
        $category = new Category([ 'name' => 'create_category' ]);

        // save to database
        $this->user->categories()->save($category);

        // check if category displayed in view
        $this->get('/categories')
            ->assertSee("<td>$category->name</td>", false);

        // delete category
        $this->post('/category/delete/' . $category->id)
            ->assertRedirect('/categories');

        // check if deleted category not displayed
        $this->get('/categories')
        ->assertDontSee("<td>$category->name</td>", false);
    }
}
