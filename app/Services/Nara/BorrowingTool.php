<?php
namespace App\Services\Nara;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\PeminjamUser; // Sesuaikan dengan model user peminjam kamu

class BorrowingTool
{
    public function search($filters)
    {
        // Eager load relasi item dan borrower
        $query = Borrowing::with(['item', 'borrower']);

        if (!empty($filters['borrower'])) {
            $query->whereHas('borrower', fn($q) => $q->where('name', 'LIKE', '%' . $filters['borrower'] . '%'));
        }

        if (!empty($filters['item'])) {
            $query->whereHas('item', fn($q) => $q->where('name', 'LIKE', '%' . $filters['item'] . '%'));
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']); // active, returned
        }

        $data = $query->latest()->limit(20)->get();
        
        // Format data untuk frontend agar mudah dibaca
        return $data->map(function($loan) {
            return [
                'id' => $loan->id,
                'item_name' => $loan->item->name ?? 'Unknown Item',
                'borrower_name' => $loan->borrower->name ?? 'Unknown User',
                'borrow_date' => $loan->borrow_date ? $loan->borrow_date->format('Y-m-d') : '-',
                'status' => $loan->status,
                'return_condition' => $loan->return_condition ?? '-'
            ];
        });
    }

    public function prepareCreatePreview($rawLoans)
    {
        foreach ($rawLoans as &$loan) {
            // 1. Cari Item ID dari Nama Barang
            if (isset($loan['item_name'])) {
                // Cari barang yang available saja
                $item = Item::where('name', 'LIKE', '%' . $loan['item_name'] . '%')
                            ->where('status', 'available')
                            ->first();
                $loan['item_id'] = $item ? $item->id : null;
                $loan['display_item'] = $item ? $item->name . " ({$item->serial_number})" : "❌ Tidak Ditemukan/Tidak Available";
                unset($loan['item_name']);
            }

            // 2. Cari User ID dari Nama Peminjam
            if (isset($loan['borrower_name'])) {
                $user = PeminjamUser::where('name', 'LIKE', '%' . $loan['borrower_name'] . '%')->first();
                $loan['user_id'] = $user ? $user->id : null;
                $loan['display_user'] = $user ? $user->name : "❌ User Tidak Ditemukan";
                unset($loan['borrower_name']);
            }

            // Defaults
            $loan['borrow_date'] = $loan['borrow_date'] ?? date('Y-m-d H:i:s');
            $loan['status'] = 'active';
            $loan['notes'] = $loan['notes'] ?? 'Pinjam via NARA';
        }
        return $rawLoans;
    }
}