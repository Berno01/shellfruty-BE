<?php

namespace App\Venta\Application\DTOs;

class VentaDTO
{
    public function __construct(
        public readonly string $fecha,
        public readonly int $id_sucursal,
        public readonly float $monto_efectivo,
        public readonly float $monto_qr,
        public readonly float $total,
        public readonly string $estado,
        public readonly array $detalles
    ) {}

    public function toArray(): array
    {
        return [
            'fecha' => $this->fecha,
            'id_sucursal' => $this->id_sucursal,
            'monto_efectivo' => $this->monto_efectivo,
            'monto_qr' => $this->monto_qr,
            'total' => $this->total,
            'estado' => $this->estado
        ];
    }

    public function getDetalles(): array
    {
        return $this->detalles;
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            fecha: $data['fecha'],
            id_sucursal: (int) $data['id_sucursal'],
            monto_efectivo: (float) $data['monto_efectivo'],
            monto_qr: (float) $data['monto_qr'],
            total: (float) $data['total'],
            estado: $data['estado'] ?? 'PENDIENTE',
            detalles: $data['detalles'] ?? []
        );
    }
}
