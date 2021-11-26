<?php

namespace App\Controllers;


use App\Libraries\Emisiones;
use App\Libraries\Zoho;

class Home extends BaseController
{
    public function index(): string
    {
        $libreria = new \App\Libraries\Cotizaciones();
        $emisiones = $libreria->lista_emisiones();

        $lista = array();
        $polizas = 0;

        foreach ((array)$emisiones as $emision) {
            //filtrar por  mes y año actual
            if (date("Y-m", strtotime($emision->getFieldValue("Vigencia_desde"))) == date("Y-m")) {
                $lista[] = $emision->getFieldValue('Coberturas')->getLookupLabel();
                $polizas++;
            }
        }

        return view('index', [
            "titulo" => "Panel de Control",
            "lista" => array_count_values($lista),
            "polizas" => $polizas,
            "cotizaciones" => $emisiones,
        ]);
    }
}
