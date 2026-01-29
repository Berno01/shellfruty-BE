<?php

namespace App\Menu\Application\DTOs;

class CategoriaDTO
{
    public function __construct(
        public readonly string $nombre_categoria
    ) {}

    public function toArray(): array
    {
        return [
            'nombre_categoria' => $this->nombre_categoria
        ];
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            nombre_categoria: $data['nombre_categoria']
        );
    }
}
