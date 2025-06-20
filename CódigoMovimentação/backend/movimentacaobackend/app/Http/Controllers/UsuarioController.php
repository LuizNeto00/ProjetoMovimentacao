<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function store(UsuarioRequest $request): JsonResponse
    {
        $usuario = Usuario::create([
            'username' => $request->username,
            'email' => $request->email,
            'senha' => bcrypt($request->senha),
        ]);

        return response()->json([
            'message' => 'Usuário cadastrado com sucesso!',
            'usuario' => $usuario
        ]);
    }

    public function index()
    {

        $usuarios = Usuario::all();

        return response()->json($usuarios);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|min:8'
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if ($usuario && Hash::check($request->senha, $usuario->senha)) {

            $token = $usuario->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login feito com sucesso!',
                'email' => $usuario->email,
                'senha' => $usuario->senha,
                'token' => $token
            ], 200);
        }

        return response()->json([
            'message' => 'Erro ao fazer login! Email ou senha inválidos',
        ], 401);
    }

    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado!'], 401);
        }

        if (Hash::check($request->password, $user->senha)) {
            return response()->json(['match' => true]);
        }

        return response()->json(['match' => false], 401);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8'
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não auutenticado!'], 401);
        }

        $user->senha = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Senha alterada com sucesso!']);
    }
}
