<?php

namespace App\Http\Controllers;

use App\Models\MovementModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0"
 * )
 */

class MovementController extends Controller
{


    /**
     * @OA\Get(
     *    path="/movement/getAll",
     *    summary="Get all movements",
     *    tags={"Movements"},
     *    @OA\Response(
     *       response=200,
     *       description="Success",
     *       @OA\JsonContent(
     *           type="array",
     *           @OA\Items(
     *               @OA\Property(property="rowid", type="integer"),
     *               @OA\Property(property="dia", type="integer"),
     *               @OA\Property(property="mes", type="integer"),
     *               @OA\Property(property="ano", type="integer"),
     *               @OA\Property(property="tipo", type="string"),
     *               @OA\Property(property="categoria", type="integer"),
     *               @OA\Property(property="descricao", type="string"),
     *               @OA\Property(property="valor", type="number"),
     *           )
     *       )
     *   )
     * )
     */

    public function getAllMovements()
    {
        $movement = MovementModel::select('lc_movimento.*', 'rowid')->get();
        return response()->json($movement);
    }


    /**
     * @OA\Get(
     *     path="/movement/filter",
     *     summary="Retrieve movements by month, year, and category",
     *     tags={"Movements"},
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=2024
     *         ),
     *         description="Year to filter by"
     *     ),
     *     @OA\Parameter(
     *         name="month",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         ),
     *         description="Month to filter by"
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Food"
     *         ),
     *         description="Category to filter by"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of movements",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="rowid", type="integer", example=1),
     *                 @OA\Property(property="lc_movimento", type="string", example="Movement details"),
     *                 @OA\Property(property="ano", type="integer", example=2024),
     *                 @OA\Property(property="mes", type="integer", example=1),
     *                 @OA\Property(property="categoria", type="string", example="Food")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */

     public function getByMonthAndYearAndCategory(Request $request)
     {
        $category = $request->input('category');
        $month = $request->input('month');
        $year = $request->input('year');

        $query = MovementModel::select('lc_movimento.*', 'rowid')->where('ano',  $year);

        if ($month) {
            $query = $query->where('mes', $month);
        }

        if ($category) {
            $query = $query->where('categoria', $category);
        }

        $movements = $query->get();

        $totalValue = $movements->sum('valor');

        $movements->push(['total' => $totalValue]);

        return response()->json($movements);
     }

    /**
     * @OA\Post(
     *     path="/api/movements",
     *     tags={"Movements"},
     *     summary="Create a new movement",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="dia", type="integer", example=15, description="Day of the movement"),
     *             @OA\Property(property="mes", type="integer", example=8, description="Month of the movement"),
     *             @OA\Property(property="ano", type="integer", example=2024, description="Year of the movement"),
     *             @OA\Property(property="tipo", type="string", example="Expense", description="Type of the movement"),
     *             @OA\Property(property="categoria", type="integer", example=1, description="Category ID of the movement"),
     *             @OA\Property(property="descricao", type="string", example="Grocery shopping", description="Description of the movement"),
     *             @OA\Property(property="valor", type="number", format="float", example=150.75, description="Value of the movement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Movement created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="dia", type="integer", example=15),
     *             @OA\Property(property="mes", type="integer", example=8),
     *             @OA\Property(property="ano", type="integer", example=2024),
     *             @OA\Property(property="tipo", type="string", example="Expense"),
     *             @OA\Property(property="categoria", type="integer", example=1),
     *             @OA\Property(property="descricao", type="string", example="Grocery shopping"),
     *             @OA\Property(property="valor", type="number", format="float", example=150.75)
     *         )
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
     *             @OA\Property(property="error", type="string", example="Could not create movement")
     *         )
     *     )
     * )
     */

    public function createMovement(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'dia' => 'required|integer|between:1,31',
                'mes' => 'required|integer|between:1,12',
                'ano' => 'required|integer|min:2000',
                'tipo' => 'required|string|max:255',
                'categoria' => 'required|integer',
                'descricao' => 'required|string|max:255',
                'valor' => 'required|numeric',
            ]);

            $movement = MovementModel::create($validatedData);
            return response()->json($movement, 201);

        } catch (\Exception $e) {
            Log::error('Error creating movement - ' . $e->getMessage());
            return response()->json(['error' => 'Could not create movement'], 500);
        }
    }

     /**
     * @OA\Delete(
     *     path="/api/movements/{rowid}",
     *     tags={"Movements"},
     *     summary="Delete a movement by ID",
     *     @OA\Parameter(
     *         name="rowid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         ),
     *         description="ID of the movement to be deleted"
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Movement deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Could not delete movement")
     *         )
     *     )
     * )
     */

    public function deleteById($rowid)
    {
        try {
            $deleted = DB::delete('delete from lc_movimento where rowid = ?', [$rowid]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting movement with rowid: ' . $rowid . ' - ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete movement'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/movements/{rowid}",
     *     tags={"Movements"},
     *     summary="Update a movement by ID",
     *     @OA\Parameter(
     *         name="rowid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         ),
     *         description="ID of the movement to be updated"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="dia", type="integer", example=15, description="Day of the movement"),
     *             @OA\Property(property="mes", type="integer", example=8, description="Month of the movement"),
     *             @OA\Property(property="ano", type="integer", example=2024, description="Year of the movement"),
     *             @OA\Property(property="tipo", type="string", example="Expense", description="Type of the movement"),
     *             @OA\Property(property="categoria", type="integer", example=1, description="Category ID of the movement"),
     *             @OA\Property(property="descricao", type="string", example="Grocery shopping", description="Description of the movement"),
     *             @OA\Property(property="valor", type="number", format="float", example=150.75, description="Value of the movement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Movement updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="rowid", type="integer", example=1),
     *             @OA\Property(property="dia", type="integer", example=15),
     *             @OA\Property(property="mes", type="integer", example=8),
     *             @OA\Property(property="ano", type="integer", example=2024),
     *             @OA\Property(property="tipo", type="string", example="Expense"),
     *             @OA\Property(property="categoria", type="integer", example=1),
     *             @OA\Property(property="descricao", type="string", example="Grocery shopping"),
     *             @OA\Property(property="valor", type="number", format="float", example=150.75)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Movement not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Movement not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Could not update movement")
     *         )
     *     )
     * )
     */

    public function updateFullMovementById(Request $request, $rowid)
    {
        $validatedData = $request->validate([
            'dia' => 'required|integer|between:1,31',
            'mes' => 'required|integer|between:1,12',
            'ano' => 'required|integer|min:2000',
            'tipo' => 'required|string|max:255',
            'categoria' => 'required|integer',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
        ]);

        $updateQuery = 'UPDATE lc_movimento SET dia = ?, mes = ?, ano = ?, tipo = ?, categoria = ?, descricao = ?, valor = ? WHERE rowid = ?';

        try {
            $updated = DB::update($updateQuery, [
                $validatedData['dia'],
                $validatedData['mes'],
                $validatedData['ano'],
                $validatedData['tipo'],
                $validatedData['categoria'],
                $validatedData['descricao'],
                $validatedData['valor'],
                $rowid
            ]);

            if ($updated) {
                $updatedMovement = DB::select('SELECT * FROM lc_movimento WHERE rowid = ?', [$rowid]);
                return response()->json($updatedMovement[0], 200);
            } else {
                return response()->json(['message' => 'Movement not found'], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Error updating movement with rowid: ' . $rowid . ' - ' . $e->getMessage());
            return response()->json(['error' => 'Could not update movement'], 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/movements/{rowid}",
     *     summary="Partially update a movement by ID",
     *     tags={"Movements"},
     *     @OA\Parameter(
     *         name="rowid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         ),
     *         description="ID of the movement to be updated"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="dia", type="integer", example=15, description="Day of the movement"),
     *             @OA\Property(property="mes", type="integer", example=8, description="Month of the movement"),
     *             @OA\Property(property="ano", type="integer", example=2024, description="Year of the movement"),
     *             @OA\Property(property="tipo", type="string", example="Expense", description="Type of the movement"),
     *             @OA\Property(property="categoria", type="integer", example=1, description="Category ID of the movement"),
     *             @OA\Property(property="descricao", type="string", example="Grocery shopping", description="Description of the movement"),
     *             @OA\Property(property="valor", type="number", format="float", example=150.75, description="Value of the movement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Movement updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="rowid", type="integer", example=1),
     *             @OA\Property(property="dia", type="integer", example=15),
     *             @OA\Property(property="mes", type="integer", example=8),
     *             @OA\Property(property="ano", type="integer", example=2024),
     *             @OA\Property(property="tipo", type="string", example="Expense"),
     *             @OA\Property(property="categoria", type="integer", example=1),
     *             @OA\Property(property="descricao", type="string", example="Grocery shopping"),
     *             @OA\Property(property="valor", type="number", format="float", example=150.75)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No fields to update",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No fields to update")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Movement not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Movement not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Could not update movement")
     *         )
     *     )
     * )
     */

    public function updatePartialMovementById(Request $request, $rowid)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'dia' => 'sometimes|integer|between:1,31',
            'mes' => 'sometimes|integer|between:1,12',
            'ano' => 'sometimes|integer|min:2000',
            'tipo' => 'sometimes|string|max:255',
            'categoria' => 'sometimes|integer',
            'descricao' => 'sometimes|string|max:255',
            'valor' => 'sometimes|numeric',
        ]);

        // Construir a query de atualização dinamicamente
        $fields = [];
        $values = [];

        foreach ($validatedData as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        if (empty($fields)) {
            return response()->json(['message' => 'No fields to update'], 400);
        }

        // Adicionar o rowid ao final dos valores
        $values[] = $rowid;

        // Construir a instrução SQL
        $updateQuery = 'UPDATE lc_movimento SET ' . implode(', ', $fields) . ' WHERE rowid = ?';

        try {
            // Executar a instrução UPDATE diretamente
            $updated = DB::update($updateQuery, $values);

            if ($updated) {
                // Se a atualização foi bem-sucedida, retornar os dados atualizados
                $updatedMovement = DB::select('SELECT * FROM lc_movimento WHERE rowid = ?', [$rowid]);
                return response()->json($updatedMovement[0], 200);
            } else {
                // Se nenhum registro foi atualizado, retornar 404 Not Found
                return response()->json(['message' => 'Movement not found'], 404);
            }
        } catch (\Exception $e) {
            // Em caso de erro, registrar a mensagem e retornar 500 Internal Server Error
            Log::error('Error updating movement with rowid: ' . $rowid . ' - ' . $e->getMessage());
            return response()->json(['error' => 'Could not update movement'], 500);
        }
    }
}
