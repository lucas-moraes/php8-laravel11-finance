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
     *    path="/movement/get-all",
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

    public function getAllMovements(Request $request)
    {
        try {
            $groupByCategory = $request->input('group-by-category');
            $groupByMonth = $request->input('group-by-month');
            $groupByYear = $request->input('group-by-year');
            $movement = [];

            if ($groupByCategory) {
                $categories = MovementModel::select('categoria')
                    ->groupBy('categoria')
                    ->get();
                $movement['categories'] = $categories;
            }

            if ($groupByMonth) {
                $months = MovementModel::select('mes')
                    ->groupBy('mes')
                    ->get();
                $movement['months'] = $months;
            }

            if ($groupByYear) {
                $years = MovementModel::select('ano')
                    ->groupBy('ano')
                    ->get();
                $movement['years'] = $years;
            }

            return response()->json($movement);
        } catch (\Exception $e) {
            Log::error('Error getting all movements - ' . $e->getMessage());
            return response()->json(['error' => 'Could not get all movements'], 500);
        }
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
        try {
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

            $movements = $query->orderBy('valor', 'desc')->get();

            $totalValue = $movements->sum('valor');

            $movements->push(['total' => $totalValue]);

            return response()->json($movements);
        } catch (\Exception $e) {
            Log::error('Error getting movements by month, year, and category - ' . $e->getMessage());
            return response()->json(['error' => 'Could not get movements by month, year, and category'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/movement",
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
     *     path="/movement/{rowid}",
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
            return response()->json($deleted, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting movement with rowid: ' . $rowid . ' - ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete movement'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/movement/{rowid}",
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
            'descricao' => 'nullable|string|max:255',
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
     *     path="/movement/{rowid}",
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
        $validatedData = $request->validate([
            'dia' => 'sometimes|integer|between:1,31',
            'mes' => 'sometimes|integer|between:1,12',
            'ano' => 'sometimes|integer|min:2000',
            'tipo' => 'sometimes|string|max:255',
            'categoria' => 'sometimes|integer',
            'descricao' => 'sometimes|string|max:255',
            'valor' => 'sometimes|numeric',
        ]);

        $fields = [];
        $values = [];

        foreach ($validatedData as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        if (empty($fields)) {
            return response()->json(['message' => 'No fields to update'], 400);
        }

        $values[] = $rowid;

        $updateQuery = 'UPDATE lc_movimento SET ' . implode(', ', $fields) . ' WHERE rowid = ?';

        try {
            $updated = DB::update($updateQuery, $values);

            if ($updated) {
                $updatedMovement = DB::select('SELECT * FROM lc_movimento WHERE rowid = ?', [$rowid]);
                return response()->json($updatedMovement[0], 200);
            } else {
                return response()->json(['message' => 'Movement not found'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error updating movement with rowid: ' . $rowid . ' - ' . $e->getMessage());
            return response()->json(['error' => 'Could not update movement'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/movement/filter-year-group-by-month",
     *     summary="Get movements grouped by month for a given year",
     *     tags={"Movements"},
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         description="Year to filter movements",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(
     *                     property="mes",
     *                     type="integer",
     *                     description="Month"
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     type="number",
     *                     description="Total value for the month"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */

    public function getMovementsByYearGroupByMonth($year)
    {
        try {
            $movements = MovementModel::select('mes', DB::raw('SUM(valor) as total'))
                ->where('ano', $year)
                ->groupBy('mes')
                ->get();
            return response()->json($movements);
        } catch (\Exception $e) {
            Log::error('Error getting movements grouped by month for year ' . $year . ' - ' . $e->getMessage());
            return response()->json(['error' => 'Could not get movements grouped by month'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/movement/filter-year-group-by-category",
     *     summary="Obter movimentos por categoria em um ano específico",
     *     tags={"Movements"},
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="Ano para filtrar os movimentos"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de movimentos agrupados por categoria",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(
     *                     property="categoria",
     *                     type="string",
     *                     description="Categoria do movimento"
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     type="number",
     *                     format="float",
     *                     description="Valor total dos movimentos na categoria"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Parâmetro 'year' ausente ou inválido"
     *     )
     * )
     */

    public function getMovementsByYearGroupByCategory($year)
    {
        try {
            $movements = MovementModel::select('categoria', DB::raw('SUM(valor) as total'))
                ->where('ano', $year)
                ->groupBy('categoria')
                ->orderBy('total', 'desc')
                ->get();

            $totalValue = $movements->sum('total');

            $movements->push(['totalYear' => $totalValue]);

            return response()->json($movements);
        } catch (\Exception $e) {
            Log::error('Error getting movements grouped by category for year ' . $year . ' - ' . $e->getMessage());
            return response()->json(['error' => 'Could not get movements grouped by category'], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/movement/{rowid}",
     *     summary="Obter um movimento pelo ID",
     *     tags={"Movements"},
     *     @OA\Parameter(
     *         name="rowid",
     *         in="path",
     *         description="ID do movimento",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Movimento encontrado",
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
     *         description="Movimento não encontrado"
     *     )
     * )
     */

    public function getMovementById($rowid)
    {
        try {
            $movement = MovementModel::select('lc_movimento.*', 'rowid')->where('rowid', $rowid)->first();

            if ($movement) {
                return response()->json($movement);
            }
        } catch (\Exception $e) {
            Log::error('Error getting movement by ID - ' . $e->getMessage());
            return response()->json(['error' => 'Could not get movement by ID'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/movements/batch",
     *     summary="Criar múltiplos movimentos",
     *     tags={"Movements"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(
     *                     property="dia",
     *                     type="integer",
     *                     description="Dia do movimento",
     *                     example=15,
     *                     minimum=1,
     *                     maximum=31
     *                 ),
     *                 @OA\Property(
     *                     property="mes",
     *                     type="integer",
     *                     description="Mês do movimento",
     *                     example=8,
     *                     minimum=1,
     *                     maximum=12
     *                 ),
     *                 @OA\Property(
     *                     property="ano",
     *                     type="integer",
     *                     description="Ano do movimento",
     *                     example=2024,
     *                     minimum=2000
     *                 ),
     *                 @OA\Property(
     *                     property="tipo",
     *                     type="string",
     *                     description="Tipo do movimento",
     *                     example="Despesa",
     *                     maxLength=255
     *                 ),
     *                 @OA\Property(
     *                     property="categoria",
     *                     type="integer",
     *                     description="Categoria do movimento",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="descricao",
     *                     type="string",
     *                     description="Descrição do movimento",
     *                     example="Compra de materiais",
     *                     maxLength=255,
     *                     nullable=true
     *                 ),
     *                 @OA\Property(
     *                     property="valor",
     *                     type="number",
     *                     description="Valor do movimento",
     *                     example=250.75
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Movimentos criados com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="ID do movimento"
     *                 ),
     *                 @OA\Property(
     *                     property="dia",
     *                     type="integer",
     *                     description="Dia do movimento"
     *                 ),
     *                 @OA\Property(
     *                     property="mes",
     *                     type="integer",
     *                     description="Mês do movimento"
     *                 ),
     *                 @OA\Property(
     *                     property="ano",
     *                     type="integer",
     *                     description="Ano do movimento"
     *                 ),
     *                 @OA\Property(
     *                     property="tipo",
     *                     type="string",
     *                     description="Tipo do movimento"
     *                 ),
     *                 @OA\Property(
     *                     property="categoria",
     *                     type="integer",
     *                     description="Categoria do movimento"
     *                 ),
     *                 @OA\Property(
     *                     property="descricao",
     *                     type="string",
     *                     description="Descrição do movimento",
     *                     nullable=true
     *                 ),
     *                 @OA\Property(
     *                     property="valor",
     *                     type="number",
     *                     description="Valor do movimento"
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     format="date-time",
     *                     description="Data de criação"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     format="date-time",
     *                     description="Data de atualização"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar os movimentos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 description="Mensagem de erro"
     *             )
     *         )
     *     )
     * )
     */


    public function createMultipleMovements(Request $request)
    {
        try {
            $validatedData = $request->validate([
                '*.dia' => 'required|integer|between:1,31',
                '*.mes' => 'required|integer|between:1,12',
                '*.ano' => 'required|integer|min:2000',
                '*.tipo' => 'required|string|max:255',
                '*.categoria' => 'required|integer',
                '*.descricao' => 'nullable|string|max:255',
                '*.valor' => 'required|numeric',
            ]);

            $movements = [];
            foreach ($validatedData as $item) {
                if (!isset($item['descricao']))
                    $item['descricao'] = '';
                $movement = MovementModel::create($item);
                $movements[] = $movement;
            }

            return response()->json(201);
        } catch (\Exception $e) {
            Log::error('Error creating batch movements - ' . $e->getMessage());
            return response()->json(['error' => 'Could not create batch movements'], 500);
        }
    }
}
