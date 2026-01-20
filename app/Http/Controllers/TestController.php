<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function hola()
    {
        return response()->json([
            'status' => 'success',
            'message' => '¡Hola! El backend de ShellFruty está vivo',
            'entorno' => app()->environment(),
            'hora' => now()->toDateTimeString()
        ]);
    }
}
