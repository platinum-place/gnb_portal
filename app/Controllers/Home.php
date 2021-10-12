<?php

namespace App\Controllers;

use App\Libraries\Cotizaciones;

class Home extends BaseController
{
    public function index()
    {
        $libreria = new Cotizaciones;
        $resumen = $libreria->resumen();
        return view('index', [
            "titulo" => "Panel de Control",
            "lista" => $resumen["lista"],
            "polizas" => $resumen["polizas"],
            "vencidas" => $resumen["vencidas"],
            "emisiones" => $resumen["emisiones"],
        ]);
    }
}
