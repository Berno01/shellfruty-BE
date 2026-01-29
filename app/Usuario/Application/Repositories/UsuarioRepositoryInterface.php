<?php

namespace App\Usuario\Application\Repositories;

interface UsuarioRepositoryInterface
{
    public function login(string $username, string $password): ?array;
}
