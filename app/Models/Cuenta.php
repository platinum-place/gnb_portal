<?php

namespace App\Models;

use App\Libraries\Zoho;

class Cuenta
{
    public function resumen($lista = array(), $polizas = 0)
    {
        $libreria = new Zoho;

        if (session("admin") == true) {
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
        }

        return [$lista, $polizas, $cotizaciones];
    }
}
