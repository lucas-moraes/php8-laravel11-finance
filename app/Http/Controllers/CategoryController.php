<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAllCategories()
    {
        $category = CategoryModel::all();
        return response()->json($category);
    }

    public function createCategory(Request $request)
    {
        $validatedData = $request->validate([
            'descricao' => 'required|string|max:255',
        ]);

        $category = CategoryModel::create($validatedData);
        return response()->json(null, 201);
    }
}
