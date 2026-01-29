<?php

namespace App\Menu\Application\DTOs;

class ReglaDTO
{
    public function __construct(
        public readonly int $id_categoria,
        public readonly int $id_menu,
        public readonly int $cant_gratis,
        public readonly int $costo_extra,
        public readonly bool $combinacion,
        public readonly array $detalles
    ) {}

    public function toArray(): array
    {
        return [
            'id_categoria' => $this->id_categoria,
            'id_menu' => $this->id_menu,
            'cant_gratis' => $this->cant_gratis,
            'costo_extra' => $this->costo_extra,
            'combinacion' => $this->combinacion
        ];
    }

    public function getDetalles(): array
    {
        return $this->detalles;
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            id_categoria: (int) $data['id_categoria'],
            id_menu: (int) $data['id_menu'],
            cant_gratis: (int) $data['cant_gratis'],
            costo_extra: (int) $data['costo_extra'],
            combinacion: (bool) $data['combinacion'],
            detalles: $data['detalles'] ?? []
        );
    }
}
