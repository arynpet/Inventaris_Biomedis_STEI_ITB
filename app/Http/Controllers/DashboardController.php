<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Borrowing;
use App\Models\Print3D;

class DashboardController extends Controller
{

    public function index()
    {
        // 1. Total Aset (Inventory Count)
        $totalItems = Item::count();

        // 2. Barang Sedang Dipinjam (Active Deployments)
        $activeLoans = Borrowing::where('status', 'borrowed')->count();

        // 3. Printer Sedang Jalan (Fabrication Tasks)
        $activePrints = Print3D::whereIn('status', ['pending', 'printing'])->count();

        // 4. Barang Rusak/Maintenance (System Warnings)
        $maintenanceCount = Item::whereIn('condition', ['damaged', 'broken', 'maintenance'])->count();

        return view('dashboard', compact('totalItems', 'activeLoans', 'activePrints', 'maintenanceCount'));
    }

}