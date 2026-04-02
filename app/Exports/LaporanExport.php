<?php

namespace App\Exports;

use App\Models\OrderItem;
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

class LaporanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
{
    protected $request;
    private $rowNumber = 0;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = OrderItem::with('order')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status_order', 'Aktif')
            ->where('order_items.status_item', 'Aktif')
            ->where('order_items.subtotal_pendapatan', '>', 0);

        // Penerapan Filter
        if ($this->request->filled('nama')) {
            $query->where('orders.nama_pelanggan', 'like', '%' . $this->request->nama . '%');
        }
        if ($this->request->filled('sopir')) {
            $query->where('order_items.nama_sopir', 'like', '%' . $this->request->sopir . '%');
        }
        if ($this->request->filled('layanan')) {
            $query->where('order_items.jenis_layanan', $this->request->layanan);
        }
        if ($this->request->filled('tgl_mulai') && $this->request->filled('tgl_selesai')) {
            $query->whereBetween('orders.penjadwalan', [$this->request->tgl_mulai, $this->request->tgl_selesai]);
        }

        return $query->select('order_items.*', 'orders.penjadwalan', 'orders.nama_pelanggan')->get();
    }

    public function headings(): array
    {
        $periode = ($this->request->tgl_mulai && $this->request->tgl_selesai) 
            ? "PERIODE: " . $this->request->tgl_mulai . " s/d " . $this->request->tgl_selesai 
            : "SELURUH PERIODE";

        return [
            ['LAPORAN PERFORMA JASA - JURAGAN URUG'],
            [$periode],
            ['Dicetak pada: ' . now()->format('d/m/Y H:i')],
            [], // Baris kosong
            [
                'NO',
                'TANGGAL',
                'PELANGGAN',
                'LAYANAN',
                'SOPIR',
                'PLAT',
                'RIT',
                'PENDAPATAN (Rp)',
                'LABA KOTOR (Rp)'
            ]
        ];
    }

    public function map($item): array
    {
        return [
            ++$this->rowNumber,
            \Carbon\Carbon::parse($item->penjadwalan)->format('d/m/Y'),
            strtoupper($item->nama_pelanggan),
            $item->jenis_layanan,
            strtoupper($item->nama_sopir ?? '-'),
            strtoupper($item->plat_armada ?? '-'),
            $item->jumlah_rit,
            $item->subtotal_pendapatan,
            $item->subtotal_keuntungan,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
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
                    'startColor' => ['rgb' => '1E40AF'] // Dark Blue (Indigo)
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
                $lastCol = 'I';

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

                // Zebra Color untuk baris data
                for ($i = 6; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle('A'.$i.':'.$lastCol.$i)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F8FAFC');
                    }
                    $event->sheet->getRowDimension($i)->setRowHeight(22);
                }

                // Baris Total di bagian paling bawah
                $totalRow = $lastRow + 1;
                $event->sheet->setCellValue('A'.$totalRow, 'TOTAL KESELURUHAN');
                $event->sheet->mergeCells('A'.$totalRow.':G'.$totalRow);
                $event->sheet->getStyle('A'.$totalRow)->getFont()->setBold(true);
                
                $event->sheet->setCellValue('H'.$totalRow, '=SUM(H6:H'.$lastRow.')');
                $event->sheet->setCellValue('I'.$totalRow, '=SUM(I6:I'.$lastRow.')');
                
                $event->sheet->getStyle('A'.$totalRow.':'.$lastCol.$totalRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F1F5F9']
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ]
                ]);
            },
        ];
    }
}