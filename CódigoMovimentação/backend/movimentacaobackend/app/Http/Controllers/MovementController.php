<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Movement;

class MovementController extends Controller
{
    public function saveMovement(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'value' => 'required|numeric',
            'category' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        $movement = Movement::create([
            'type' => $validated['type'],
            'value' => $validated['value'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Movimentação criada com sucesso!',
            'movement' => $movement
        ], 201);
    }

    public function getUserMovements(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        $movements = Movement::where('user_id', $user->id)->get();

        return response()->json([
            'movements' => $movements
        ], 200);
    }

    public function updateMovement(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:input,exit',
            'value' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $user = $request->user();

        $movement = Movement::where('id', $id)->where('user_id', $user->id)->first();

        if (!$movement) {
            return response()->json(['message' => 'Movimentação não encontrada!'], 404);
        }

        $movement->type = $validated['type'];
        $movement->value = $validated['value'];
        $movement->category = $validated['category'];
        $movement->description = $validated['description'] ?? '';
        $movement->save();

        return response()->json([
            'message' => 'Movimentação atualizada!',
            'movement' => $movement
        ]);
    }

    public function deleteMovement(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }


        $movement = Movement::where('id', $id)->where('user_id', $user->id)->first();

        if (!$movement) {
            return response()->json(['error' => 'Movimentação não encontrada!.'], 404);
        }


        $movement->delete();

        return response()->json(['Movimentação deletada com sucesso!'], 200);
    }
}
