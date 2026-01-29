<?php

namespace App\Usuario\Infrastructure\Controllers;

use App\Usuario\Application\UseCases\Usuario\LoginUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioController
{
    private LoginUseCase $loginUseCase;

    public function __construct(LoginUseCase $loginUseCase)
    {
        $this->loginUseCase = $loginUseCase;
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $usuario = $this->loginUseCase->execute(
            $request->input('username'),
            $request->input('password')
        );

        if (!$usuario) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        return response()->json($usuario);
    }

    // Endpoint temporal para generar hash de contraseñas
    public function hashPassword(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        return response()->json([
            'password_original' => $request->input('password'),
            'password_hash' => \Illuminate\Support\Facades\Hash::make($request->input('password'))
        ]);
    }
}
