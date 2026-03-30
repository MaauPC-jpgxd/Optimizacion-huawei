<?php

namespace App\Exports;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

class ArticulosExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Articulo::query()
            ->leftJoin('inventario_sucursales as inv', function ($join) {
                $join->on('articulos.Articulo', '=', 'inv.articulo')
                     ->on('articulos.Almacen', '=', 'inv.almacen');
            });

        if ($this->request->filled('buscar')) {
            $buscar = $this->request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('articulos.Articulo', 'like', "%$buscar%")
                  ->orWhere('articulos.Descripcion1', 'like', "%$buscar%");
            });
        }

        if ($this->request->filled('categoria')) {
            $query->where('articulos.Categoria', $this->request->categoria);
        }

        if ($this->request->filled('fabricante')) {
            $query->where('articulos.Fabricante', $this->request->fabricante);
        }

        if ($this->request->filled('almacen')) {
            $query->where('articulos.Almacen', $this->request->almacen);
        }

        // ✅ Compatible con MySQL y SQL Server
        $query->where(function ($q) {
            $q->whereNotNull('inv.existencias')
              ->where('inv.existencias', '>', 0);
        });

        return $query->select(
            'articulos.Articulo',
            'articulos.Descripcion1',
            'articulos.Categoria',
            'articulos.Fabricante',
            'inv.almacen',
            'inv.existencias',
            'inv.disponible',
            'articulos.Estatus',
            'articulos.CostoPromedio',
            DB::raw('(COALESCE(inv.existencias, 0) * articulos.CostoPromedio) as Valor')
        )
        ->orderBy('articulos.Articulo')
        ->orderBy('inv.almacen')
        ->get()
        ->map(function ($row) {
            // ✅ Forzar tipos numéricos para que Excel no los trate como texto
            $row->existencias   = (float) $row->existencias;
            $row->disponible    = (float) $row->disponible;
            $row->CostoPromedio = (float) $row->CostoPromedio;
            $row->Valor         = (float) $row->Valor;
            return $row;
        });
    }

    public function headings(): array
    {
        return [
            'Artículo',
            'Descripción',
            'Categoría',
            'Fabricante',
            'Almacén',
            'Existencias',
            'Disponible',
            'Estatus',
            'Costo Promedio',
            'Valor'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0D6EFD']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Bordes a toda la tabla
                $sheet->getStyle("A1:J{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Zebra rows
                for ($i = 2; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle("A{$i}:J{$i}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('F2F2F2');
                    }
                }

                // Formato moneda columnas I y J (desde fila 2)
                $sheet->getStyle("I2:J{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"$"#,##0.00');

                // ✅ Totales al final (fila lastRow+2 = labels, lastRow+3 = valores)
                $labelRow = $lastRow + 2;
                $valueRow = $lastRow + 3;

                $sheet->setCellValue("F{$labelRow}", 'TOTAL EXIST.');
                $sheet->setCellValue("G{$labelRow}", 'TOTAL DISP.');
                $sheet->setCellValue("J{$labelRow}", 'TOTAL VALOR');

                $sheet->setCellValue("F{$valueRow}", "=SUM(F2:F{$lastRow})");
                $sheet->setCellValue("G{$valueRow}", "=SUM(G2:G{$lastRow})");
                $sheet->setCellValue("J{$valueRow}", "=SUM(J2:J{$lastRow})");

                // Formato moneda para el total de valor
                $sheet->getStyle("J{$valueRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"$"#,##0.00');

                // Estilo fila de labels
                $sheet->getStyle("F{$labelRow}:J{$labelRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E9ECEF']
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ]);

                // Estilo fila de valores
                $sheet->getStyle("F{$valueRow}:J{$valueRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E9ECEF']
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ]
                ]);
            }
        ];
    }
}