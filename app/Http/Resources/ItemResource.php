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
            'name' => $this->name,
            'serial_number' => $this->serial_number,
            'asset_number' => $this->asset_number,
            'brand' => $this->brand,
            'type' => $this->type,
            'room' => $this->room ? $this->room->name : '-',
            'category' => $this->categories->pluck('name')->join(', '), // Comma separated for mobile
            'status' => $this->status,
            'condition' => $this->condition,
            'quantity' => $this->quantity,
            'acquisition_year' => $this->acquisition_year,
            'source' => $this->source,
            'fiscal_group' => $this->fiscal_group,
            'usage_start_date' => $this->placed_in_service_at ? $this->placed_in_service_at->format('d F Y') : '-',
            'qr_code_url' => route('items.show', $this->id),
        ];
    }
}
