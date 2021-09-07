<?php

namespace App\Http\Controllers;

use App\Models\Emision;

class Home extends Controller
{
    public function __invoke()
    {
        $lista = array();
        $polizas = 0;
        $vencidas = 0;
        $evaluacion = 0;
        $emision = new Emision;
        $emisiones = $emision->obtener_lista();
        foreach ($emisiones as $record) {
            if (date("Y-m", strtotime($record->getCreatedTime())) == date("Y-m")) {
                $lista[] = $record->getFieldValue('Aseguradora')->getLookupLabel();
                $polizas++;
                if ($record->getFieldValue('Stage') == "Proceso de validación") {
                    $evaluacion++;
                }
            }
            if (date("Y-m", strtotime($record->getFieldValue('Closing_Date'))) == date("Y-m")) {
                $vencidas++;
            }
        }
        return view('index', [
            "lista" => array_count_values($lista),
            "polizas" => $polizas,
            "evaluacion" => $evaluacion,
            "vencidas" => $vencidas
        ]);
    }
}
