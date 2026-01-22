<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->optimized_image,
            'serial_number' => $this->serial_number,
            'asset_number' => $this->asset_number,
            'brand' => $this->brand,
            'type' => $this->type,
            'room' => $this->room ? $this->room->name : '-',
            'category' => $this->categories->pluck('name')->join(', '),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'condition' => $this->condition,
            'condition_label' => $this->getConditionLabel(),
            'quantity' => $this->quantity,
            'acquisition_year' => $this->acquisition_year,
            'source' => $this->source,
            'fiscal_group' => $this->fiscal_group,
            'usage_start_date' => $this->placed_in_service_at ? $this->placed_in_service_at->format('d M Y') : '-',
            'latest_log' => $this->whenLoaded('latestLog', function () {
                return [
                    'date' => $this->latestLog->created_at->format('d M Y'),
                    'action' => $this->latestLog->action,
                    'note' => $this->latestLog->note ?? $this->latestLog->description ?? '-',
                ];
            }),
            'qr_code_url' => route('items.show', $this->id),
        ];
    }

    private function getStatusLabel()
    {
        return match ($this->status) {
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'maintenance' => 'Dalam Perawatan',
            'dikeluarkan' => 'Sudah Keluar',
            default => ucfirst($this->status),
        };
    }

    private function getConditionLabel()
    {
        return match ($this->condition) {
            'good' => 'Baik',
            'damaged' => 'Rusak Ringan',
            'broken' => 'Rusak Berat',
            default => ucfirst($this->condition),
        };
    }
}
