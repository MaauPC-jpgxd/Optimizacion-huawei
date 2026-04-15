<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VentasC;
use Illuminate\Pagination\LengthAwarePaginator;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // ================= ÚLTIMA VENTA DISPONIBLE 🔥 =================
        $ultimaVenta = VentasC::selectRaw("
                CONVERT(date, FechaEmision) as fecha,
                SUM(
                    CASE 
                        WHEN ISNUMERIC(Total) = 1 
                        THEN CAST(Total AS DECIMAL(18,2))
                        ELSE 0 
                    END
                ) as total
            ")
            ->groupBy(DB::raw("CONVERT(date, FechaEmision)"))
            ->orderByDesc('fecha')
            ->first();

        // ================= VENTAS POR MES 🔥 =================
        $ventasPorMes = VentasC::selectRaw("
                FORMAT(FechaEmision, 'yyyy-MM') as mes,
                SUM(
                    CASE 
                        WHEN ISNUMERIC(Total) = 1 
                        THEN CAST(Total AS DECIMAL(18,2))
                        ELSE 0 
                    END
                ) as total
            ")
            ->groupBy(DB::raw("FORMAT(FechaEmision, 'yyyy-MM')"))
            ->orderBy('mes')
            ->get();

        // ================= PROMEDIO DIARIO =================
        $ventas = DB::table('Ventas_d')
            ->select(
                'Articulo',
                DB::raw("
                    AVG(
                        CASE 
                            WHEN ISNUMERIC(Cantidad) = 1 
                            THEN CAST(Cantidad AS FLOAT)
                            ELSE 0 
                        END
                    ) as promedio_diario
                ")
            )
            ->groupBy('Articulo')
            ->get()
            ->keyBy('Articulo');

        // ================= QUERY BASE =================
        $query = DB::table('articulos as a')
            ->leftJoin('inventario_sucursales as inv', function ($join) {
                $join->on('a.Articulo', '=', 'inv.articulo')
                     ->on('a.Almacen', '=', 'inv.almacen');
            })
            ->whereRaw('ISNULL(inv.existencias,0) > 0')
            ->select(
                'a.Articulo',
                'a.Descripcion1',
                'a.Almacen',
                DB::raw('ISNULL(inv.existencias,0) as existencias')
            );

        if ($request->buscar) {
            $query->where(function ($q) use ($request) {
                $q->where('a.Articulo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('a.Descripcion1', 'like', '%' . $request->buscar . '%');
            });
        }

        if ($request->almacen) {
            $query->where('a.Almacen', $request->almacen);
        }

        $articulos = $query->get();

        // ================= CALCULOS =================
        $articulos = $articulos->map(function ($a) use ($ventas) {

            $promedio = $ventas[$a->Articulo]->promedio_diario ?? 0;
            $a->promedio_diario = $promedio;

            $a->dias_restantes = $promedio == 0 
                ? 999 
                : $a->existencias / $promedio;

            return $a;
        });

        if ($request->estado == 'critico') {
            $articulos = $articulos->where('dias_restantes', '<=', 5);
        }

        if ($request->estado == 'alerta') {
            $articulos = $articulos->whereBetween('dias_restantes', [6, 10]);
        }

        $articulos = $articulos->where('dias_restantes', '<=', 10);

        $articulos = $articulos->sortBy('dias_restantes')->values();

        $criticos = $articulos->where('dias_restantes', '<=', 5)->values();
        $otros = $articulos->where('dias_restantes', '>', 5)->values();

        // ================= PAGINACIÓN =================
        $perPage = 20;
        $page = LengthAwarePaginator::resolveCurrentPage();

        $items = $otros->slice(($page - 1) * $perPage, $perPage)->values();

        $articulosPaginados = new LengthAwarePaginator(
            $items,
            $otros->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('analytics.index', [
            'criticos' => $criticos,
            'articulos' => $articulosPaginados,
            'ventasPorMes' => $ventasPorMes, // 🔥 CAMBIO
            'ultimaVenta' => $ultimaVenta
        ]);
    }
}