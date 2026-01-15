<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $isBatch = $this->input('input_mode') === 'batch';

        $rules = [
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'nullable|integer|min:1',
            'source' => 'nullable|string|max:255',
            'acquisition_year' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'placed_in_service_at' => 'nullable|date',
            'fiscal_group' => 'nullable|string|max:255',
            'status' => 'required|in:available,borrowed,maintenance,dikeluarkan',
            'condition' => 'required|in:good,damaged,broken',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'asset_number' => 'nullable|string|max:255',

            // Image Validation (Hybrid)
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|string|max:1000',
        ];

        // Batch or Single Mode Validation
        if ($isBatch) {
            $rules['serial_numbers_batch'] = 'required|string';
        } else {
            $rules['serial_number'] = 'required|string|max:255|unique:items,serial_number';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama barang wajib diisi.',
            'room_id.required' => 'Ruangan wajib dipilih.',
            'room_id.exists' => 'Ruangan yang dipilih tidak valid.',
            'status.required' => 'Status barang wajib diisi.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'condition.required' => 'Kondisi barang wajib diisi.',
            'condition.in' => 'Kondisi yang dipilih tidak valid.',
            'serial_number.required' => 'Serial Number wajib diisi.',
            'serial_number.unique' => 'Serial Number sudah digunakan. Silakan gunakan nomor lain.',
            'serial_numbers_batch.required' => 'Serial Number batch wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus: jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    /**
     * Get safe data for creating Item (preventing mass assignment).
     * Excludes fields that are handled separately (categories, image, etc).
     */
    public function safeData(): array
    {
        $data = $this->only([
            'name',
            'brand',
            'type',
            'asset_number',
            'serial_number',
            'room_id',
            'quantity',
            'source',
            'acquisition_year',
            'placed_in_service_at',
            'fiscal_group',
            'status',
            'condition',
        ]);

        // Set default quantity if not provided
        if (!isset($data['quantity'])) {
            $data['quantity'] = 1;
        }

        return $data;
    }
}
