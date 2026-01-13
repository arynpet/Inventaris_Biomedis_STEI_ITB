<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    /**
     * Find Item by Serial Number
     */
    public function showBySerial($serial_number)
    {
        // Cari barang case-insensitive (opsional, tapi bagus untuk scan)
        $item = Item::with(['room', 'categories', 'latestLog'])
            ->where('serial_number', $serial_number)
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ItemResource($item),
        ]);
    }
}
