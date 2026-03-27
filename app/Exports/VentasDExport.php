<?php

namespace App\Exports;

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

class VentasDExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $ventas;

    // 🔥 RECIBE COLECCIÓN (NO REQUEST)
    public function __construct($ventas)
    {
        $this->ventas = $ventas;
    }

    // 🔥 DATA LIMPIA (YA FILTRADA DESDE CONTROLLER)
    public function collection()
    {
        return collect($this->ventas)->map(function ($v) {
            return [
                'Suc'         => $v->Sucursal,
                'Artículo'    => $v->Articulo,
                'Descripción' => $v->ArtDescripcion ?? 'Sin descripción',
                'Cant'        => $v->Cantidad,
                'Cliente'     => $v->Cliente,
                'Nombre'      => $v->CteNombre,
                'Factura'     => $v->MovID,
                'Estatus'     => $v->Estatus,
                'Tipo'        => $v->Mov,
                'Alm'         => $v->Almacen,
                'Fecha'       => $v->FechaEmision 
                    ? \Carbon\Carbon::parse($v->FechaEmision)->format('d/m/Y') 
                    : '-',
            ];
        });
    }

    // 🔥 HEADERS
    public function headings(): array
    {
        return [
            'Sucursal',
            'Artículo',
            'Descripción',
            'Cantidad',
            'Cliente',
            'Nombre',
            'Factura',
            'Estatus',
            'Tipo',
            'Almacén',
            'Fecha',
        ];
    }

    // 🎨 ESTILO HEADER
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
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

    // 🔥 EVENTOS (DISEÑO PRO)
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ($event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // 🔳 BORDES
                $sheet->getStyle("A1:K{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // 🎨 FILAS ALTERNADAS
                for ($i = 2; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle("A{$i}:K{$i}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('F2F2F2');
                    }
                }

                // 📏 CENTRADO GENERAL
                $sheet->getStyle("A1:K{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // 🧾 DESCRIPCIÓN AJUSTADA
                $sheet->getStyle("C2:C{$lastRow}")
                    ->getAlignment()
                    ->setWrapText(true);

                // ❄️ CONGELAR HEADER
                $sheet->freezePane('A2');

                // 🔍 FILTROS EXCEL
                $sheet->setAutoFilter("A1:K{$lastRow}");

                // 📊 TOTALES (PRO 🔥)
                $totalRow = $lastRow + 2;

                $sheet->setCellValue("D{$totalRow}", 'TOTAL CANTIDAD');
                $sheet->setCellValue("D".($totalRow+1), '=SUM(D2:D'.$lastRow.')');

                $sheet->getStyle("D{$totalRow}:D".($totalRow+1))->applyFromArray([
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