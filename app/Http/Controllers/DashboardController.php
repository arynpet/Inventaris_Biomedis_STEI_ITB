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

        // 5. Overdue Loans (Telat Mengembalikan)
        $overdueLoans = Borrowing::with(['item', 'borrower'])
            ->where('status', 'borrowed')
            ->whereDate('return_date', '<', now())
            ->get();

        // 6. Recent Borrowings (5 Transaksi Terakhir)
        $recentBorrowings = Borrowing::with(['item', 'borrower'])
            ->orderBy('borrow_date', 'desc')
            ->take(5)
            ->get();

        // 7. Recent Activities (Log Aktivitas) - Jika model ada
        $recentActivities = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // 8. DATA CHART: Status Barang
        $pieData = [
            'available' => Item::where('status', 'available')->count(),
            'borrowed' => Item::where('status', 'borrowed')->count(),
            'maintenance' => Item::whereIn('status', ['maintenance', 'repair'])->count(),
            'lost' => Item::whereIn('status', ['lost', 'broken', 'disposed'])->count(),
        ];

        // 9. DATA CHART: Tren Peminjaman 7 Hari Terakhir
        $chartDates = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
            $count = Borrowing::whereDate('borrow_date', $date)->count();
            $chartDates[] = \Carbon\Carbon::parse($date)->format('d M');
            $chartValues[] = $count;
        }

        return view('dashboard', compact(
            'totalItems',
            'activeLoans',
            'activePrints',
            'maintenanceCount',
            'overdueLoans',
            'recentBorrowings',
            'recentActivities',
            'pieData',
            'chartDates',
            'chartValues'
        ));
    }

}