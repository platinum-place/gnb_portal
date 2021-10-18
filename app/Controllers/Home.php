<?php

namespace App\Controllers;

use App\Libraries\Cotizaciones;

class Home extends BaseController
{
    public function index()
    {
        $libreria = new Cotizaciones;
        $lista = array();
        $polizas = 0;
        $vencidas = 0;
        $cotizaciones = $libreria->lista_cotizaciones();
        foreach ((array)$cotizaciones as $cotizacion) {
            if ($cotizacion->getFieldValue('Quote_Stage') == "Emitida") {
                //filtrar por  mes y año actual
                if (date("Y-m", strtotime($cotizacion->getCreatedTime())) == date("Y-m")) {
                    $lista[] =  $cotizacion->getFieldValue('Coberturas')->getLookupLabel();
                    $polizas++;
                }
                //contador para las emisiones que vencen en el mes y año actual
                if (date("Y-m", strtotime($cotizacion->getFieldValue('Valid_Till'))) == date("Y-m")) {
                    $vencidas++;
                }
            }
        }
        return view('index', [
            "titulo" => "Panel de Control",
            "lista" => array_count_values($lista),
            "polizas" => $polizas,
            "vencidas" => $vencidas,
            "cotizaciones" => $cotizaciones,
        ]);
    }
}
