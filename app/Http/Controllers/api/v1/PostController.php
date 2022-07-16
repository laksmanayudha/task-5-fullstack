<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index()
    {

        // get all posts in database
        $data['posts'] = Post::all();

        // return all posts in database
        return response()->json([
            'status' => 'succes',
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

        // return succes respon
        return response()->json([
            'status' => 'success',
            'message' => 'success to create a post',
            'data' => [
                'post' => $data_request
            ]
        ], 200);
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
