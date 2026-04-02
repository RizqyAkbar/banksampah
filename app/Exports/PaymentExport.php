<?php

namespace App\Exports;

use App\Models\Order;
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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class PaymentExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
{
    protected $tgl_mulai;
    protected $tgl_selesai;
    private $rowNumber = 0;

    public function __construct($tgl_mulai, $tgl_selesai)
    {
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_selesai = $tgl_selesai;
    }

    public function collection()
    {
        return Order::whereBetween('penjadwalan', [$this->tgl_mulai, $this->tgl_selesai])
            ->where('status_order', 'Aktif')
            ->where('total_pendapatan', '>', 0)
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            ['LAPORAN STATUS PEMBAYARAN & PIUTANG'],
            ['PERIODE: ' . \Carbon\Carbon::parse($this->tgl_mulai)->format('d/m/Y') . ' s/d ' . \Carbon\Carbon::parse($this->tgl_selesai)->format('d/m/Y')],
            ['Dicetak pada: ' . now()->format('d/m/Y H:i')],
            [],
            [
                'NO', 
                'NO. INVOICE', 
                'TANGGAL ORDER', 
                'NAMA PELANGGAN', 
                'TOTAL TAGIHAN (Rp)', 
                'SUDAH DIBAYAR (Rp)', 
                'SISA HUTANG (Rp)', 
                'STATUS', 
                'METODE BAYAR', 
                'CATATAN'
            ]
        ];
    }

    public function map($order): array
    {
        return [
            ++$this->rowNumber,
            $order->no_invoice,
            \Carbon\Carbon::parse($order->penjadwalan)->format('d/m/Y'),
            strtoupper($order->nama_pelanggan),
            $order->total_pendapatan,
            $order->jumlah_dibayar,
            ($order->total_pendapatan - $order->jumlah_dibayar),
            strtoupper($order->status_pembayaran),
            $order->metode_pembayaran ?? '-',
            $order->catatan_pembayaran ?? '-'
        ];
    }

    public function columnFormats(): array
    {
        // Format Rupiah/Ribuan untuk kolom E, F, dan G
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['italic' => true, 'size' => 12]],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'] // Emerald 600
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
                $lastCol = 'J';

                // Merge Header
                $event->sheet->mergeCells('A1:'.$lastCol.'1');
                $event->sheet->mergeCells('A2:'.$lastCol.'2');
                $event->sheet->mergeCells('A3:'.$lastCol.'3');
                $event->sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Styling Tabel
                $event->sheet->getStyle('A5:'.$lastCol.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CBD5E1'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Zebra Rows & Row Height
                for ($i = 6; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle('A'.$i.':'.$lastCol.$i)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F0FDF4'); // Emerald Tint
                    }
                    $event->sheet->getRowDimension($i)->setRowHeight(22);
                }

                // Baris Summary Total
                $totalRow = $lastRow + 1;
                $event->sheet->mergeCells('A'.$totalRow.':D'.$totalRow);
                $event->sheet->setCellValue('A'.$totalRow, 'RINGKASAN TOTAL');
                
                // Rumus SUM Otomatis
                $event->sheet->setCellValue('E'.$totalRow, "=SUM(E6:E$lastRow)");
                $event->sheet->setCellValue('F'.$totalRow, "=SUM(F6:F$lastRow)");
                $event->sheet->setCellValue('G'.$totalRow, "=SUM(G6:G$lastRow)");

                $event->sheet->getStyle("A$totalRow:$lastCol$totalRow")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DCFCE7'] // Emerald 100
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ]
                ]);
            },
        ];
    }
}