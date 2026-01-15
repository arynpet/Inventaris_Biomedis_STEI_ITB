<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * Fetch collection of items for export
     */
    public function collection()
    {
        return Item::with(['room', 'categories'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Barang',
            'Serial Number',
            'Asset Number',
            'Kondisi',
            'Status',
            'Lokasi (Ruangan)',
            'Kategori',
            'Tahun Pengadaan',
            'Sumber',
            'Tanggal Dibuat',
        ];
    }

    /**
     * Map data for each row
     */
    public function map($item): array
    {
        return [
            $item->id,
            $item->name,
            $item->serial_number,
            $item->asset_number ?? '-',
            ucfirst($item->condition),
            ucfirst($item->status),
            $item->room->name ?? '-',
            $item->categories->pluck('name')->join(', ') ?: '-',
            $item->acquisition_year ?? '-',
            $item->source ?? '-',
            $item->created_at->format('d/m/Y'),
        ];
    }

    /**
     * Apply styles to the spreadsheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2563EB'], // Blue color
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
