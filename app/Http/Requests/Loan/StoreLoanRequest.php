<?php

namespace App\Http\Requests\Loan;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ensure user is authenticated via student guard
        return auth('student')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1|max:10',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:borrow_date',
            'purpose' => 'required|string|max:500|min:10',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'item_id.required' => 'Barang wajib dipilih.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'quantity.required' => 'Jumlah  peminjaman wajib diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'quantity.max' => 'Jumlah maksimal 10 per peminjaman.',
            'borrow_date.required' => 'Tanggal pinjam wajib diisi.',
            'borrow_date.after_or_equal' => 'Tanggal pinjam tidak boleh kurang dari hari ini.',
            'return_date.required' => 'Tanggal kembali wajib diisi.',
            'return_date.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
            'purpose.required' => 'Keperluan peminjaman wajib diisi.',
            'purpose.min' => 'Keperluan minimal 10 karakter.',
            'purpose.max' => 'Keperluan maksimal 500 karakter.',
        ];
    }

    /**
     * Get safe loan data (preventing mass assignment).
     */
    public function safeLoanData(): array
    {
        return [
            'user_id' => auth('student')->id(),
            'item_id' => $this->input('item_id'),
            'quantity' => $this->input('quantity'),
            'borrow_date' => $this->input('borrow_date'),
            'return_date' => $this->input('return_date'),
            'purpose' => $this->input('purpose'),
            'status' => 'pending', // Default status
        ];
    }
}
