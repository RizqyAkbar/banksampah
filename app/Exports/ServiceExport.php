<?php

namespace App\Exports;

use App\Models\Service;
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

class ServiceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
{
    private $rowNumber = 0;

    public function collection()
    {
        return Service::orderBy('nama_layanan', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            ['DAFTAR MASTER HARGA LAYANAN & ARMADA'],
            ['JURAGAN URUG OFFICIAL PRICELIST'],
            ['Update Per: ' . now()->format('d/m/Y H:i')],
            [],
            [
                'NO',
                'NAMA LAYANAN',
                'JENIS ARMADA',
                'HPP / MODAL (Rp)',
                'HARGA JUAL (Rp)',
                'MARGIN (Rp)'
            ]
        ];
    }

    /**
    * @var Service $service
    */
    public function map($service): array
    {
        return [
            ++$this->rowNumber,
            strtoupper($service->nama_layanan),
            strtoupper($service->armada),
            $service->harga_dasar,
            $service->harga_jual,
            ($service->harga_jual - $service->harga_dasar) // Hitung margin otomatis
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true, 'color' => ['rgb' => '64748B']]],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'] // Indigo 600
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
                $lastCol = 'F';

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
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Zebra Color & Row Height
                for ($i = 6; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle('A'.$i.':'.$lastCol.$i)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F5F3FF'); // Indigo Tint
                    }
                    $event->sheet->getRowDimension($i)->setRowHeight(25);
                }

                // Baris Rata-rata Margin (Optional Insight)
                $totalRow = $lastRow + 1;
                $event->sheet->setCellValue('A'.$totalRow, 'RATA-RATA MARGIN KEUNTUNGAN');
                $event->sheet->mergeCells('A'.$totalRow.':E'.$totalRow);
                $event->sheet->setCellValue('F'.$totalRow, '=AVERAGE(F6:F'.$lastRow.')');
                
                $event->sheet->getStyle('A'.$totalRow.':'.$lastCol.$totalRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']]
                ]);
            },
        ];
    }
}