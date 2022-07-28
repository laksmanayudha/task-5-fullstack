<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        // get all category
        $categories = Category::latest()->get();

        // return view
        return view('categories', [
            'categories' => $categories
        ]);
    }

    public function create(Request $request)
    {
        
        // validate input data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        // throw error if fails
        if ($validator->fails()) {
            return redirect('/categories')
                    ->withErrors($validator)
                    ->withInput();
        } 

        // insert category
        $user = auth()->user();
        $user->categories()->create([
            'name' => $request->name
        ]);

        return redirect('/categories');

    }

    public function delete(Category $category)
    {
        $category->delete();
        return redirect('/categories');
    }

    public function update(Request $request, Category $category)
    {
        // validate input data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        // throw error if fails
        if ($validator->fails()) {
            return redirect('/categories')
                    ->withErrors($validator)
                    ->withInput();
        }
        
        // update category
        $category->name = $request->name;
        $user = auth()->user();
        $user->categories()->save($category);

        return redirect('/categories');

    }
}
