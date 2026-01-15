<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
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
        $itemId = $this->route('item')->id;

        return [
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'asset_number' => 'nullable|string|max:255',
            'serial_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('items', 'serial_number')->ignore($itemId),
            ],
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'required|integer|min:1',
            'source' => 'nullable|string|max:255',
            'acquisition_year' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'placed_in_service_at' => 'nullable|date',
            'fiscal_group' => 'nullable|string|max:255',
            'status' => 'required|in:available,borrowed,maintenance,dikeluarkan',
            'condition' => 'required|in:good,damaged,broken',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',

            // Image Validation (Hybrid)
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|string|max:1000',
        ];
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
            'serial_number.unique' => 'Serial Number sudah digunakan oleh item lain. Silakan gunakan nomor lain.',
            'quantity.required' => 'Jumlah barang wajib diisi.',
            'quantity.min' => 'Jumlah barang minimal 1.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus: jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    /**
     * Get safe data for updating Item (preventing mass assignment).
     */
    public function safeData(): array
    {
        return $this->only([
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
    }
}
