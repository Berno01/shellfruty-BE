<?php

namespace App\Menu\Application\DTOs;

class IngredienteDTO
{
    public function __construct(
        public readonly string $nombre_ingrediente,
        public readonly int $id_categoria,
        public readonly ?string $url_foto = null
    ) {}

    public function toArray(): array
    {
        return [
            'nombre_ingrediente' => $this->nombre_ingrediente,
            'id_categoria' => $this->id_categoria,
            'url_foto' => $this->url_foto
        ];
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            nombre_ingrediente: $data['nombre_ingrediente'],
            id_categoria: (int) $data['id_categoria'],
            url_foto: $data['url_foto'] ?? null
        );
    }
}
