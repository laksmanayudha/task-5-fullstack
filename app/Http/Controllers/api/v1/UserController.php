<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // validate user input
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:8'
        ]);
        
        // prepare user data before insert
        $data_request = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];

        // create new user
        try{
            User::create( $data_request );
        }catch(\Throwable $e){

            // throw response if an error occurs
            return response()->json([
                'status' => 'failed',
                'message' => 'failed to create an account'
            ]);
        }

        // response success
        return response()->json([
            'status' => 'success',
            'message' => 'succes create an account',
        ], 200);
    }
    public function login(Request $request)
    {
        // validate user input
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // prepare login data
        $data_request = request(['email', 'password']);

        if( !Auth::attempt( $data_request ) ){
            return response()->json([
                'status' => 'failed',
                'message' => 'invalid user credentials'
            ]);
        }

        // generate JWT token
        $user = Auth::user();
        $token = $user->createToken('authToken')->accessToken;

        // data response
        $data['user'] = $user;
        $data['token'] = $token;
        
        // response succes
        return response()->json([
            'status' => 'succes',
            'message' => 'login success',
            'data' => $data
        ]);
    }
}
