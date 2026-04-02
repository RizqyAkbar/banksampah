<?php

namespace App\Exports;

use App\Models\Order;
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

class InvoiceExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
{
    protected $request;
    private $rowNumber = 0;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Order::with(['items' => function ($q) {
            $q->where('status_item', 'Aktif');
        }])
        ->where('status_order', 'Aktif')
        ->where('total_pendapatan', '>', 0);

        // Filter Pencarian
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%$search%")
                  ->orWhere('no_wa', 'LIKE', "%$search%")
                  ->orWhere('no_invoice', 'LIKE', "%$search%")
                  ->orWhereHas('items', function ($sq) use ($search) {
                      $sq->where('jenis_layanan', 'LIKE', "%$search%")
                         ->orWhere('armada', 'LIKE', "%$search%");
                  });
            });
        }

        // Filter Tanggal
        if ($this->request->filled('tgl_mulai') && $this->request->filled('tgl_selesai')) {
            $query->whereBetween('penjadwalan', [$this->request->tgl_mulai, $this->request->tgl_selesai]);
        }

        return $query->orderBy('penjadwalan', 'desc');
    }

    public function headings(): array
    {
        $periode = ($this->request->tgl_mulai && $this->request->tgl_selesai) 
            ? "PERIODE: " . \Carbon\Carbon::parse($this->request->tgl_mulai)->format('d/m/Y') . " s/d " . \Carbon\Carbon::parse($this->request->tgl_selesai)->format('d/m/Y')
            : "SELURUH PERIODE";

        return [
            ['LAPORAN DAFTAR TAGIHAN (INVOICE) - JURAGAN URUG'],
            [$periode],
            ['Dicetak pada: ' . now()->format('d/m/Y H:i')],
            [],
            [
                'NO',
                'NO. INVOICE',
                'TANGGAL ORDER',
                'NAMA PELANGGAN',
                'KONTAK WHATSAPP',
                'RINCIAN LAYANAN & ARMADA',
                'TOTAL TAGIHAN (Rp)'
            ]
        ];
    }

    public function map($order): array
    {
        // Gabungkan rincian item
        $rincian = $order->items->map(function($item) {
            return "• {$item->jenis_layanan} ({$item->jumlah_rit} Rit) - {$item->armada}";
        })->implode("\n");

        return [
            ++$this->rowNumber,
            $order->no_invoice,
            \Carbon\Carbon::parse($order->penjadwalan)->format('d/m/Y'),
            strtoupper($order->nama_pelanggan),
            " " . $order->no_wa, // Spasi agar tidak terbaca scientific
            $rincian,
            (float) $order->total_pendapatan,
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
            1 => ['font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '0F172A']]],
            2 => ['font' => ['italic' => true]],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '334155'] // Slate 700
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

                $event->sheet->mergeCells('A1:'.$lastCol.'1');
                $event->sheet->mergeCells('A2:'.$lastCol.'2');
                $event->sheet->mergeCells('A3:'.$lastCol.'3');
                $event->sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getStyle('A5:'.$lastCol.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CBD5E1'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_TOP,
                    ],
                ]);

                // Wrap text untuk rincian layanan
                $event->sheet->getStyle('F6:F'.$lastRow)->getAlignment()->setWrapText(true);

                // Zebra Color
                for ($i = 6; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle('A'.$i.':'.$lastCol.$i)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F8FAFC');
                    }
                }
                
                $event->sheet->getDefaultRowDimension()->setRowHeight(-1);

                // Baris Total di bawah
                $totalRow = $lastRow + 1;
                $event->sheet->mergeCells('A'.$totalRow.':F'.$totalRow);
                $event->sheet->setCellValue('A'.$totalRow, 'TOTAL KESELURUHAN TAGIHAN');
                $event->sheet->setCellValue('G'.$totalRow, '=SUM(G6:G'.$lastRow.')');
                
                $event->sheet->getStyle('A'.$totalRow.':'.$lastCol.$totalRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']]
                ]);
            },
        ];
    }
}