<?php

namespace App\Controllers;

use App\Libraries\Resumen_emisiones;

class Home extends BaseController
{
    public function index()
    {
        $resumen = new Resumen_emisiones;
        return view('index', ["titulo" => "Panel de Control", "lista" => array_count_values($resumen->lista), "polizas" => $resumen->polizas, "vencidas" => $resumen->vencidas]);
    }
}
