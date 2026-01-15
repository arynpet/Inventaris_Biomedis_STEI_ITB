<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show the report selection page
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Export all items to Excel
     */
    public function exportItemsExcel()
    {
        $filename = 'Inventaris_Lab_Biomedis_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new ItemsExport, $filename);
    }

    /**
     * Show monthly loan report form
     */
    public function monthlyLoanForm()
    {
        return view('reports.monthly_form');
    }

    /**
     * Generate Monthly Loan Report (PDF)
     */
    public function monthlyLoanPdf(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
        ]);

        $month = $request->month;
        $year = $request->year;

        // Get loans for the specified month
        $loans = Loan::with(['user', 'item.room'])
            ->whereYear('borrow_date', $year)
            ->whereMonth('borrow_date', $month)
            ->orderBy('borrow_date', 'desc')
            ->get();

        // Month name in Indonesian
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $monthName = $monthNames[$month];

        $data = [
            'loans' => $loans,
            'month' => $monthName,
            'year' => $year,
            'generated_at' => Carbon::now()->format('d F Y, H:i'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('reports.monthly_pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = "Laporan_Peminjaman_{$monthName}_{$year}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Generate Item Condition Report (PDF)
     */
    public function itemConditionPdf()
    {
        $items = Item::with(['room', 'categories'])
            ->orderBy('condition', 'desc')
            ->orderBy('name')
            ->get();

        $data = [
            'items' => $items,
            'generated_at' => Carbon::now()->format('d F Y, H:i'),
            'summary' => [
                'good' => $items->where('condition', 'good')->count(),
                'damaged' => $items->where('condition', 'damaged')->count(),
                'broken' => $items->where('condition', 'broken')->count(),
            ],
        ];

        $pdf = Pdf::loadView('reports.item_condition_pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        $filename = 'Laporan_Kondisi_Barang_' . Carbon::now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
