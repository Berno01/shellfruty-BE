<?php

namespace App\Usuario\Infrastructure\Repositories;

use App\Usuario\Application\Repositories\UsuarioRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EloquentUsuarioRepository implements UsuarioRepositoryInterface
{
    public function login(string $username, string $password): ?array
    {
        $usuario = DB::table('usuario')
            ->join('rol', 'usuario.id_rol', '=', 'rol.id_rol')
            ->join('sucursal', 'usuario.id_sucursal', '=', 'sucursal.id_sucursal')
            ->where('usuario.username', $username)
            ->where('usuario.estado', true)
            ->select(
                'usuario.id_usuario',
                'usuario.username',
                'usuario.password',
                'usuario.id_rol',
                'usuario.id_sucursal',
                'rol.nombre_rol',
                'sucursal.nombre_sucursal'
            )
            ->first();

        if (!$usuario) {
            return null;
        }

        // Verificar contraseña encriptada
        if (!Hash::check($password, $usuario->password)) {
            return null;
        }

        return [
            'id_usuario' => $usuario->id_usuario,
            'username' => $usuario->username,
            'nombre_rol' => $usuario->nombre_rol,
            'id_rol' => $usuario->id_rol,
            'id_sucursal' => $usuario->id_sucursal,
            'nombre_sucursal' => $usuario->nombre_sucursal
        ];
    }
}
