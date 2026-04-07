<?php

namespace App\Abastecimiento\Application\DTOs;

class AbastecimientoDTO
{
    public function __construct(
        public readonly string $fecha,
        public readonly int $idSucursal,
        public readonly array $detalles
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            fecha: $data['fecha'],
            idSucursal: $data['id_sucursal'],
            detalles: $data['detalles'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'fecha' => $this->fecha,
            'id_sucursal' => $this->idSucursal,
            'detalles' => $this->detalles
        ];
    }
}
