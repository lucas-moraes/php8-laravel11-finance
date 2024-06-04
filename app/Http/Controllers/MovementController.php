<?php

namespace App\Http\Controllers;

use App\Models\MovementModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller
{
    public function getAllMovements()
    {
        $movement = MovementModel::select('lc_movimento.*', 'rowid')->get();
        return response()->json($movement);
    }

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
        return response()->json($movements);
    }

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
