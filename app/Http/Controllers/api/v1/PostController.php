<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function index(Request $request)
    {

        // get all posts in database
        if( $request->query('page') ){  
            $data['posts'] = Post::paginate(5);
        }else{
            $data['posts'] = Post::all();
        }

        // return all posts in database
        return response()->json([
            'status' => 'success',
            'message' => 'all posts',
            'data' => $data
        ], 200);
    }


    public function store(Request $request)
    {
        // validate data input
        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'content' => 'required',
            'category_id' => 'required'
        ]);

        // prepare data
        $user = $request->user();
        $folder = 'images';
        $data_request = $request->all();
        $data_request['user_id'] = $user->getKey();

        // store image
        if( $request->hasFile('image') ){
                      
            // store images in disk
            $path = $request->image->store($folder);
            $data_request['image'] = $path;
        }

        // insert post data to database
        try{
            Post::create( $data_request );
        }catch(\Throwable $e){

            // return failed response
            return response()->json([
                'status' => 'failed',
                'message' => 'failed to create a post'
            ]);
        }

        // prepare post data
        $data['post'] = $data_request;

        // return succes respon
        return response()->json([
            'status' => 'success',
            'message' => 'success to create a post',
            'data' => $data
        ], 201);
    }


    public function show(Post $post)
    {

        // prepare post data
        $data['post'] = $post;

        // return data post
        return response()->json([
            'status' => 'success',
            'message' => 'detail posts',
            'data' => $data
        ]);
    }


    public function update(Request $request, Post $post)
    {
        // validate data input
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg'
        ]);

        $folder = 'images';

        // prepare all updated field
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

        // new updated post
        $new_post['post'] = Post::find($post->getKey());
        
        // return success updated post
        return response()->json([
            'status' => 'success',
            'message' => 'update post',
            'data' => $new_post
        ]);
    }


    public function destroy(Post $post)
    {

        // delete post data
        try{
            $post->delete();
        }catch(\Throwable $e){

            // return failed delete response
            return response()->json([
                'status' => 'failed',
                'message' => 'failed to delete a post'
            ]);
        }

        // delete image on disk if exists
        if( $post->image != null ){
            Storage::delete($post->image);
        }
        
        // prepare deleted post
        $data['post'] = $post;

        // return success delete response
        return response()->json([
            'status' => 'success',
            'message' => 'success to delete a post',
            'data' => $data
        ]);
    }
}
