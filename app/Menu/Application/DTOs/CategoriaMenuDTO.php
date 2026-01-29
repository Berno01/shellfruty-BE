<?php

namespace App\Menu\Application\DTOs;

class CategoriaMenuDTO
{
    public function __construct(
        public readonly string $nombre_categoria_menu
    ) {}

    public function toArray(): array
    {
        return [
            'nombre_categoria_menu' => $this->nombre_categoria_menu
        ];
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            nombre_categoria_menu: $data['nombre_categoria_menu']
        );
    }
}
