<?php

namespace App\Controllers;

use App\Models\Emision;

class Home extends BaseController
{
    public function index()
    {
        $resumen = new Emision;
        $lista = array();
        $polizas = 0;
        $vencidas = 0;
        $evaluacion = 0;
        foreach ($resumen->lista_emisiones() as $emision) {
            if (date("Y-m", strtotime($emision->getCreatedTime())) == date("Y-m")) {
                $lista[] = $emision->getFieldValue('Aseguradora')->getLookupLabel();
                $polizas++;
                if ($emision->getFieldValue('Stage') == "Proceso de validación") {
                    $evaluacion++;
                }
            }
            if (date("Y-m", strtotime($emision->getFieldValue('Closing_Date'))) == date("Y-m")) {
                $vencidas++;
            }
        }
        return view('index', ["titulo" => "Panel de Control", "lista" => array_count_values($lista), "polizas" => $polizas, "vencidas" => $vencidas]);
    }
}
