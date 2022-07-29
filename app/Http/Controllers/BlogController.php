<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        // get blogs data
        $posts = Post::latest()->get();
        return view('blog', [
            'posts' => $posts
        ]);
    }

    public function form()
    {
        // get all category
        $categories = Category::all();
        return view('form', [
            'categories' => $categories,
            'form_url' => '/blog/create',
            'submit_text' => 'Create'
        ]);
    }

    public function formUpdate(Post $post)
    {
        // get all category
        $categories = Category::all();
        return view('form', [
            'categories' => $categories,
            'post' => $post,
            'form_url' => "/blog/update/$post->id",
            'submit_text' => 'Update'
        ]);
    }

    public function create(Request $request)
    {
        // validate data input
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'content' => 'required',
            'category_id' => 'required'
        ]);

        if($validator->fails()){
            return redirect('/blog/form')
                    ->withErrors($validator)
                    ->withInput();
        }

        // prepare data
        $user = $request->user();
        $folder = 'images';
        $data_request = $request->all();

        // store image
        if( $request->hasFile('image') ){
                        
            // store images in disk
            $path = $request->image->store($folder);
            $data_request['image'] = $path;
        }

        // insert post data to database
        $user->posts()->create( $data_request );

        return redirect('/blogs')->with('alert',
            [
                'type' => 'success',
                'message' => 'Success to create an article'
            ]
        );
    }

    public function update(Request $request, Post $post)
    {

        // validate data input
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg'
        ]);

        if($validator->fails()){
            return redirect("/blog/formUpdate/$post->id")
                    ->withErrors($validator)
                    ->withInput();
        }

        
        // prepare all updated field
        $folder = 'images';
        $data_request = $request->all();

        // update image on disk
        if( $request->hasFile('image') ){
            
            // store image on disk
            $path = $request->image->store($folder);
            $data_request['image'] = $path;
            
            // delete image on disk
            Storage::delete($post->image);
        }
        
        // update image on database
        $post->update( $data_request );
        
        return redirect('/blogs')->with('alert', 
            [
                'type' => 'success',
                'message' => 'Success to update an article'
            ]
        );
    }

    public function delete(Post $post)
    {
        // delete data from database
        $post->delete();

        // delete image on disk if exists
        if( $post->image != null ){
            Storage::delete($post->image);
        }
        return redirect('/blogs')->with('alert', 
            [
                'type' => 'danger',
                'message' => 'Success to delete an article'
            ]
        );
    }
}
