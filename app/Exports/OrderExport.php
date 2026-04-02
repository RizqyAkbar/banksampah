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

class OrderExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
{
    protected $tgl_mulai;
    protected $tgl_selesai;
    private $rowNumber = 0;

    public function __construct($tgl_mulai, $tgl_selesai)
    {
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_selesai = $tgl_selesai;
    }

    public function query()
    {
        return Order::query()
            ->with(['items' => function($q) {
                $q->where('status_item', 'Aktif');
            }])
            ->whereBetween('penjadwalan', [$this->tgl_mulai, $this->tgl_selesai])
            ->where('status_order', 'Aktif')
            ->orderBy('penjadwalan', 'asc');
    }

    public function headings(): array
    {
        return [
            ['REKAPITULASI ORDER JURAGAN URUG'],
            ['PERIODE: ' . \Carbon\Carbon::parse($this->tgl_mulai)->format('d/m/Y') . ' s/d ' . \Carbon\Carbon::parse($this->tgl_selesai)->format('d/m/Y')],
            ['Dicetak pada: ' . now()->format('d/m/Y H:i')],
            [],
            [
                'NO', 
                'INVOICE', 
                'TANGGAL', 
                'PELANGGAN', 
                'WHATSAPP', 
                'ALAMAT', 
                'LINK MAPS',                  // KOLOM BARU
                'STATUS PELANGGAN',           // KOLOM BARU
                'DETAIL LAYANAN & SOPIR', 
                'TOTAL PENDAPATAN (Rp)', 
                'STATUS BAYAR'
            ]
        ];
    }

    public function map($order): array
    {
        // Gabungkan detail item menjadi satu string dengan Line Break (\n)
        $detailLayanan = $order->items->map(function($item) {
            return "• {$item->jenis_layanan} ({$item->jumlah_rit} Rit) - Sopir: {$item->nama_sopir}";
        })->implode("\n");

        // Logika Status Pelanggan (Sama seperti di tampilan View)
        $statusPelanggan = in_array($order->status_pelanggan, ['Pelanggan', 'Pelanggan Lama']) ? 'PELANGGAN' : 'BARU';

        return [
            ++$this->rowNumber,
            $order->no_invoice,
            \Carbon\Carbon::parse($order->penjadwalan)->format('d/m/Y'),
            strtoupper($order->nama_pelanggan),
            " " . $order->no_wa, // Format teks untuk WhatsApp
            $order->alamat ?? '-',
            $order->link_maps ?? '-',       // Menarik data Link Maps
            $statusPelanggan,               // Menarik data Status Pelanggan
            $detailLayanan,
            $order->total_pendapatan,
            strtoupper($order->status_pembayaran)
        ];
    }

    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Pindah ke Kolom J karena ada tambahan kolom
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['italic' => true]],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '334155'] // Slate 700 (Warna Admin)
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
                $lastCol = 'K'; // Batas kolom berubah dari I menjadi K

                // Merge & Center Header
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
                        'vertical' => Alignment::VERTICAL_TOP, // Top agar detail layanan rapi
                    ],
                ]);

                // Fitur Wrap Text untuk Kolom Detail (Sekarang bergeser ke Kolom I)
                $event->sheet->getStyle('I6:I'.$lastRow)->getAlignment()->setWrapText(true);

                // Zebra Color & Row Height
                for ($i = 6; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle('A'.$i.':'.$lastCol.$i)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F8FAFC');
                    }

                    // Membuat Kolom Link Maps Bisa Diklik Secara Otomatis
                    $linkMapsValue = $event->sheet->getCell('G'.$i)->getValue();
                    if ($linkMapsValue !== '-' && filter_var($linkMapsValue, FILTER_VALIDATE_URL)) {
                        $event->sheet->getCell('G'.$i)->getHyperlink()->setUrl($linkMapsValue);
                        $event->sheet->getStyle('G'.$i)->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => '2563EB'], // Warna Biru Khas Link
                                'underline' => 'single'
                            ]
                        ]);
                    }
                }
                
                // Set Auto Row Height (Sangat penting karena ada Wrap Text)
                $event->sheet->getDefaultRowDimension()->setRowHeight(-1);

                // Baris Total
                $totalRow = $lastRow + 1;
                $event->sheet->mergeCells('A'.$totalRow.':I'.$totalRow); // Menggabungkan kolom A sampai I
                $event->sheet->setCellValue('A'.$totalRow, 'TOTAL PENDAPATAN PERIODE INI');
                $event->sheet->setCellValue('J'.$totalRow, '=SUM(J6:J'.$lastRow.')'); // Hitung total di kolom J
                
                $event->sheet->getStyle('A'.$totalRow.':'.$lastCol.$totalRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']]
                ]);
            },
        ];
    }
}