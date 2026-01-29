<?php

namespace App\Usuario\Application\UseCases\Usuario;

use App\Usuario\Application\Repositories\UsuarioRepositoryInterface;

class LoginUseCase
{
    private UsuarioRepositoryInterface $usuarioRepository;

    public function __construct(UsuarioRepositoryInterface $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }

    public function execute(string $username, string $password): ?array
    {
        return $this->usuarioRepository->login($username, $password);
    }
}
