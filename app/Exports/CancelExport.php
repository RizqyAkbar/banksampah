<?php

namespace App\Exports;

use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class CancelExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
{
    protected $request;
    private $rowNumber = 0;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = OrderItem::with('order')->where('status_item', 'Batal');

        // Sama persis dengan filter di Controller
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jenis_layanan', 'LIKE', "%$search%")
                  ->orWhere('alasan_cancel', 'LIKE', "%$search%")
                  ->orWhereHas('order', function ($sq) use ($search) {
                      $sq->where('nama_pelanggan', 'LIKE', "%$search%");
                  });
            });
        }

        if ($this->request->filled('tgl_mulai') && $this->request->filled('tgl_selesai')) {
            $start = $this->request->tgl_mulai . " 00:00:00";
            $end = $this->request->tgl_selesai . " 23:59:59";
            $query->whereBetween('updated_at', [$start, $end]);
        }

        return $query->orderBy('updated_at', 'desc');
    }

    public function headings(): array
    {
        $periode = ($this->request->tgl_mulai && $this->request->tgl_selesai) 
            ? "PERIODE: " . \Carbon\Carbon::parse($this->request->tgl_mulai)->format('d/m/Y') . " s/d " . \Carbon\Carbon::parse($this->request->tgl_selesai)->format('d/m/Y')
            : "SELURUH PERIODE";

        return [
            ['LAPORAN MONITORING CANCEL / PEMBATALAN'],
            [$periode],
            ['Dicetak pada: ' . now()->format('d/m/Y H:i')],
            [],
            [
                'NO',
                'TANGGAL BATAL',
                'NAMA PELANGGAN',
                'JENIS LAYANAN',
                'ARMADA & SOPIR',
                'ALASAN PEMBATALAN',
                'POTENSI KEHILANGAN (Rp)'
            ]
        ];
    }

    public function map($item): array
    {
        return [
            ++$this->rowNumber,
            \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i'),
            strtoupper($item->order->nama_pelanggan ?? 'N/A'),
            strtoupper($item->jenis_layanan),
            strtoupper($item->armada . ($item->nama_sopir ? " - " . $item->nama_sopir : '')),
            $item->alasan_cancel ?? 'Tanpa alasan',
            $item->subtotal_pendapatan,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'E11D48']]], // Warna Merah/Rose 600
            2 => ['font' => ['italic' => true]],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E11D48'] // Warna Header Merah
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow();
                $lastCol = 'G';

                // Merge & Center Header Judul
                $event->sheet->mergeCells('A1:'.$lastCol.'1');
                $event->sheet->mergeCells('A2:'.$lastCol.'2');
                $event->sheet->mergeCells('A3:'.$lastCol.'3');
                $event->sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Styling Tabel
                $event->sheet->getStyle('A5:'.$lastCol.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E2E8F0'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_TOP,
                    ],
                ]);

                // Fitur Wrap Text agar kolom Alasan Cancel tidak terlalu panjang ke kanan
                $event->sheet->getStyle('F6:F'.$lastRow)->getAlignment()->setWrapText(true);

                // Zebra Color (Beri warna kemerahan tipis untuk baris genap)
                for ($i = 6; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle('A'.$i.':'.$lastCol.$i)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFF1F2'); // Rose 50
                    }
                }
                
                $event->sheet->getDefaultRowDimension()->setRowHeight(-1);

                // Baris Penjumlahan Total di bawah
                $totalRow = $lastRow + 1;
                $event->sheet->mergeCells('A'.$totalRow.':F'.$totalRow);
                $event->sheet->setCellValue('A'.$totalRow, 'TOTAL POTENSI PENDAPATAN HILANG');
                $event->sheet->setCellValue('G'.$totalRow, '=SUM(G6:G'.$lastRow.')');
                
                $event->sheet->getStyle('A'.$totalRow.':'.$lastCol.$totalRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFE4E6']] // Rose 100
                ]);
            },
        ];
    }
}