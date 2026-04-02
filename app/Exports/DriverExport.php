<?php

namespace App\Exports;

use App\Models\Driver;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DriverExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    public function collection()
    {
        return Driver::orderBy('nama_sopir', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            ['LAPORAN DATA MASTER SOPIR'], // Judul Baris 1
            ['Dicetak pada: ' . now()->format('d-m-Y H:i')], // Info Baris 2
            [], // Baris Kosong
            ['NO', 'NAMA SOPIR', 'NO. WHATSAPP', 'PLAT ARMADA DEFAULT'] // Header Tabel Baris 4
        ];
    }

    /**
    * @var Driver $driver
    */
    private $rowNumber = 0;
    public function map($driver): array
    {
        return [
            ++$this->rowNumber,
            strtoupper($driver->nama_sopir),
            " " . $driver->no_wa_sopir, // Tambah spasi agar Excel tidak menganggapnya angka scientific
            strtoupper($driver->plat_armada),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk Judul Besar
            1    => ['font' => ['bold' => true, 'size' => 16]],
            
            // Style untuk Header Tabel (Baris ke-4)
            4    => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981'] // Warna Hijau Emerald
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow();
                $lastCol = 'D'; // Kolom terakhir adalah D

                // Gabungkan baris judul
                $event->sheet->mergeCells('A1:'.$lastCol.'1');
                $event->sheet->mergeCells('A2:'.$lastCol.'2');
                $event->sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Tambahkan Border ke seluruh tabel
                $event->sheet->getStyle('A4:'.$lastCol.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E2E8F0'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Membuat Row menjadi lebih tinggi sedikit agar tidak sesak
                for ($i = 4; $i <= $lastRow; $i++) {
                    $event->sheet->getRowDimension($i)->setRowHeight(25);
                }
            },
        ];
    }
}