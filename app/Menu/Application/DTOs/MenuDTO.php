<?php

namespace App\Menu\Application\DTOs;

class MenuDTO
{
    public function __construct(
        public readonly string $nombre_menu,
        public readonly float $precio_menu,
        public readonly int $id_categoria_menu,
        public readonly ?string $url_foto = null
    ) {}

    public function toArray(): array
    {
        return [
            'nombre_menu' => $this->nombre_menu,
            'precio_menu' => $this->precio_menu,
            'id_categoria_menu' => $this->id_categoria_menu,
            'url_foto' => $this->url_foto
        ];
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            nombre_menu: $data['nombre_menu'],
            precio_menu: (float) $data['precio_menu'],
            id_categoria_menu: (int) $data['id_categoria_menu'],
            url_foto: $data['url_foto'] ?? null
        );
    }
}
