<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
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
            'categories' => $categories
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

        return redirect('/blogs');
    }

    public function delete(Post $post)
    {
        $post->delete();
        return redirect('/blogs');
    }
}
