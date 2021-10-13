<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Plantillas extends BaseController
{
    public function cotizacion($id)
    {
        $libreria = new Zoho;
        //obtener datos de la cotizacion
        $cotizacion = $libreria->getRecord("Quotes", $id);

        switch ($cotizacion->getFieldValue("Plan")) {
            case 'Vida':
                return view('cotizacion_vida', ["cotizacion" => $cotizacion, "libreria" => $libreria]);
                break;

            case 'Vida/Desempleo':
                return view('cotizacion_desempleo', ["cotizacion" => $cotizacion, "libreria" => $libreria]);
                break;

            case 'Seguro Incendio Hipotecario':
                return view('cotizacion_incendio', ["cotizacion" => $cotizacion]);
                break;

            default:
                return view('cotizacion_auto', ["cotizacion" => $cotizacion, "libreria" => $libreria]);
                break;
        }
    }

    public function emision($id)
    {
        $libreria = new Zoho;
        //obtener datoss de la emision
        $cotizacion = $libreria->getRecord("Quotes", $id);
        //informacion sobre las coberturas, la aseguradora,las coberturas
        $plan = $libreria->getRecord("Products", $cotizacion->getFieldValue("Coberturas")->getEntityId());

        switch ($cotizacion->getFieldValue("Plan")) {
            case 'Vida':
                return view('emision_vida',  ["cotizacion" => $cotizacion, "plan" => $plan]);
                break;

            case 'Vida/Desempleo':
                return view('emision_desempleo',  ["cotizacion" => $cotizacion, "plan" => $plan]);
                break;

            case 'Seguro Incendio Hipotecario':
                return view('emision_incendio',  ["cotizacion" => $cotizacion, "plan" => $plan]);
                break;

            default:
                return view('emision_auto', ["cotizacion" => $cotizacion, "plan" => $plan]);
                break;
        }
    }

    public function tua($id)
    {
        $libreria = new Zoho;
        //datos de la tua
        $tua = $libreria->getRecord("Deals", $id);
        //datos del cliente
        $cliente = $libreria->getRecord("Leads", $tua->getFieldValue("Cliente")->getEntityId());
        //datos de los vehiculos
        $criterio = "Trato:equals:$id";
        $vehiculos = $libreria->searchRecordsByCriteria("Bienes", $criterio);
        //usuario que creo la tua
        $creado_por = $tua->getCreatedBy();
        //usuario que modifico la tua
        $modificado_por = $tua->getModifiedBy();
        return view('tua', [
            "tua" => $tua,
            "cliente" => $cliente,
            "vehiculos" => $vehiculos,
            "creado_por" => $creado_por,
            "modificado_por" => $modificado_por,
        ]);
    }
}
