<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VentasCabecera;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\VentasHuaweiExport;
class VentasCabeceraController extends Controller
{
    public function index (){
        $ventas=VentasCabecera::whereIn('sucursal',[390,400,500,600])->orderBy('fecha_emision', 'desc')->paginate(15);
        return view ('facturas.index', compact('ventas'));
    }
    public function excel(Request $request){
        return Excel::Download (new VentasCabeceraExport($request),'Facturas.xlsx');
    }
}
            