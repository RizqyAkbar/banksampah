<?php

namespace App\Exports;

use App\Models\Pengeluaran;
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

class PengeluaranExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
{
    protected $request;
    private $rowNumber = 0;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Pengeluaran::query();

        // Filter Pencarian (Sama dengan Controller)
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kategori', 'LIKE', "%$search%")
                  ->orWhere('keterangan', 'LIKE', "%$search%");
            });
        }

        // Filter Tanggal
        if ($this->request->filled('tgl_mulai') && $this->request->filled('tgl_selesai')) {
            $query->whereBetween('tanggal', [$this->request->tgl_mulai, $this->request->tgl_selesai]);
        }

        return $query->orderBy('tanggal', 'desc');
    }

    public function headings(): array
    {
        $periode = ($this->request->tgl_mulai && $this->request->tgl_selesai) 
            ? "PERIODE: " . \Carbon\Carbon::parse($this->request->tgl_mulai)->format('d/m/Y') . " s/d " . \Carbon\Carbon::parse($this->request->tgl_selesai)->format('d/m/Y')
            : "SELURUH PERIODE";

        return [
            ['LAPORAN PENGELUARAN KAS - JURAGAN URUG'],
            [$periode],
            ['Dicetak pada: ' . now()->format('d/m/Y H:i')],
            [],
            [
                'NO',
                'TANGGAL PENGELUARAN',
                'KATEGORI',
                'KETERANGAN / DETAIL',
                'NOMINAL (Rp)'
            ]
        ];
    }

    public function map($item): array
    {
        return [
            ++$this->rowNumber,
            \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y'),
            strtoupper($item->kategori),
            $item->keterangan ?? '-',
            (float) $item->nominal,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '9F1239']]], // Rose 800
            2 => ['font' => ['italic' => true, 'size' => 12]],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E11D48'] // Rose 600
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
                $lastCol = 'E';

                $event->sheet->mergeCells('A1:'.$lastCol.'1');
                $event->sheet->mergeCells('A2:'.$lastCol.'2');
                $event->sheet->mergeCells('A3:'.$lastCol.'3');
                $event->sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getStyle('A5:'.$lastCol.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'FDA4AF'], // Rose 300
                        ],
                    ],
                ]);

                // Zebra Color
                for ($i = 6; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle('A'.$i.':'.$lastCol.$i)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFF1F2'); // Rose 50
                    }
                    $event->sheet->getRowDimension($i)->setRowHeight(25);
                }

                // Baris Total
                $totalRow = $lastRow + 1;
                $event->sheet->mergeCells('A'.$totalRow.':D'.$totalRow);
                $event->sheet->setCellValue('A'.$totalRow, 'TOTAL PENGELUARAN');
                $event->sheet->setCellValue('E'.$totalRow, '=SUM(E6:E'.$lastRow.')');
                
                $event->sheet->getStyle('A'.$totalRow.':'.$lastCol.$totalRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFE4E6']], // Rose 100
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
                ]);
                $event->sheet->getRowDimension($totalRow)->setRowHeight(30);
            },
        ];
    }
}