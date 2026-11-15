<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', Auth::user()->id)->get();
        return response()->json($categories, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = Category::create([
            'user_id' => Auth::user()->id,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return response()->json($category, 201);
    }

    // GET /categories/{id} (Read Single)
    public function show($id)
    {
        $category = Category::where('id', $id)
                            ->where('user_id', Auth::user()->id)
                            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category, 200);
    }

    // PUT /categories/{id} (Update)
    public function update(Request $request, $id)
    {
        $category = Category::where('id', $id)
                            ->where('user_id', Auth::user()->id)
                            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category->update($request->only(['name', 'description']));

        return response()->json($category, 200);
    }

    // DELETE /categories/{id} (Delete)
    public function destroy($id)
    {
        $category = Category::where('id', $id)
                            ->where('user_id', Auth::user()->id)
                            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
