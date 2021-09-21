<?php

namespace App\Controllers;

use App\Libraries\Emisiones;

class Home extends BaseController
{
    public function index()
    {
        $emisiones = new Emisiones;
        $emisiones->resumen();
        return view('index', [
            "titulo" => "Panel de Control",
            "lista" => array_count_values($emisiones->lista),
            "polizas" => $emisiones->polizas,
            "vencidas" => $emisiones->vencidas,
            "vencidas" => $emisiones->evaluacion
        ]);
    }
}
