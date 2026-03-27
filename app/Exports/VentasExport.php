<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithEvents
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{
    Fill,
    Border,
    Alignment
};
use Maatwebsite\Excel\Events\AfterSheet;

class VentasExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $ventas;

    public function __construct($ventas)
    {
        $this->ventas = $ventas;
    }

    public function collection()
    {
        return collect($this->ventas)->map(function ($v) {
            return [
                'Folio'     => $v->MovID,
                'Tipo'      => $v->Mov,
                'Sucursal'  => $v->Sucursal,
                'Almacen'   => $v->Almacen,
                'Fecha'     => $v->FechaEmision 
                                ? \Carbon\Carbon::parse($v->FechaEmision)->format('d/m/Y') 
                                : '-',
                'Importe'   => $v->Importe ?? 0,
                'Total'     => $v->Total ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Tipo',
            'Sucursal',
            'Almacén',
            'Fecha',
            'Importe',
            'Total',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // HEADER
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0D6EFD']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // 🔥 BORDES A TODA LA TABLA
                $sheet->getStyle("A1:G{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // 🔥 ZEBRA ROWS
                for ($i = 2; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle("A{$i}:G{$i}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('F2F2F2');
                    }
                }

                // 🔥 FORMATO MONEDA
                $sheet->getStyle("F2:F{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"$"#,##0.00');

                $sheet->getStyle("G2:G{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"$"#,##0.00');

                // 🔥 TOTALES
                $totalRow = $lastRow + 2;

                $sheet->setCellValue("F{$totalRow}", 'TOTAL IMPORTE');
                $sheet->setCellValue("G{$totalRow}", '=SUM(F2:F'.$lastRow.')');

                $sheet->setCellValue("F".($totalRow+1), 'TOTAL GENERAL');
                $sheet->setCellValue("G".($totalRow+1), '=SUM(G2:G'.$lastRow.')');

                // Estilo totales
                $sheet->getStyle("F{$totalRow}:G".($totalRow+1))->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E9ECEF']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN
                        ]
                    ]
                ]);
            }
        ];
    }
}