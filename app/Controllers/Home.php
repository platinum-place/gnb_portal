<?php

namespace App\Controllers;

use App\Models\Cuenta;

class Home extends BaseController
{
    public function index()
    {
        $cuenta = new Cuenta;
        $resumen = $cuenta->resumen();

        return view('index', [
            "titulo" => "Panel de Control",
            "lista" => array_count_values($resumen[0]),
            "polizas" => $resumen[1],
            "cotizaciones" => $resumen[2],
        ]);
    }
}
