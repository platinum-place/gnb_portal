<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Home extends BaseController
{
    public function index()
    {
        $libreria = new Zoho;
        $lista = array();
        $polizas = 0;
        $vencidas = 0;

        if (session('puesto') == "Administrador") {
            $criterio = "((Account_Name:equals:" . session('cuenta_id') . ") and (Quote_Stage:starts_with:E))";
        } else {
            $criterio = "((Account_Name:equals:" . session('cuenta_id') . ") and (Contact_Name:equals:" . session('usuario_id') . ") and (Quote_Stage:starts_with:E))";
        }

        $cotizaciones = $libreria->searchRecordsByCriteria("Quotes", $criterio);

        foreach ((array)$cotizaciones as $cotizacion) {
            //filtrar por  mes y año actual
            if (date("Y-m", strtotime($cotizacion->getFieldValue("Vigencia_desde"))) == date("Y-m")) {
                $lista[] =  $cotizacion->getFieldValue('Coberturas')->getLookupLabel();
                $polizas++;
            }
            //contador para las emisiones que vencen en el mes y año actual
            if (date("Y-m", strtotime($cotizacion->getFieldValue('Valid_Till'))) == date("Y-m")) {
                $vencidas++;
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
