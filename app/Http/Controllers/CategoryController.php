<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    /**
    * @OA\Get(
    *    path="/category/get-all",
    *    summary="Retorna todas as categorias",
    *    tags={"Categories"},
    *    @OA\Response(
    *       response=200,
    *       description="Success",
    *       @OA\JsonContent(
    *           type="array",
    *           @OA\Items(
    *               @OA\Property(property="idCategory", type="integer"),
    *               @OA\Property(property="descricao", type="string"),
    *           )
    *       )
    *    )
    * )
    */

    public function getAllCategories()
    {
        $category = CategoryModel::all();
        return response()->json($category);
    }

    /**
     * @OA\Post(
     *     path="/category/create",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="descricao", type="string", example="Food", description="Description of the category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Validation error")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Could not create category")
     *         )
     *     )
     * )
     */

    public function createCategory(Request $request)
    {
        $validatedData = $request->validate([
            'descricao' => 'required|string|max:255',
        ]);

        $category = CategoryModel::create($validatedData);
        return response()->json(null, 201);
    }
}
