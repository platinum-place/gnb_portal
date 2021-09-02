<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Plantillas extends BaseController
{
    public function emision($id)
    {
        $zoho = new Zoho;
        $emision = $zoho->getRecord("Deals", $id);
        switch ($emision->getFieldValue("Type")) {
            case 'Incendio':
                return view('plantillas/emisiones/incendio', ["emision" => $emision]);
                break;
            case 'Desempleo':
                $coberturas = $zoho->getRecord("Products", $emision->getFieldValue("Coberturas")->getEntityId());
                $requisitos = $coberturas->getFieldValue("Requisitos_deudor");
                return view('plantillas/emisiones/desempleo', ["emision" => $emision, "requisitos" => $requisitos]);
                break;
            case 'Vida':
                $coberturas = $zoho->getRecord("Products", $emision->getFieldValue("Coberturas")->getEntityId());
                $requisitos = $coberturas->getFieldValue("Requisitos_deudor");
                $corequisitos = $coberturas->getFieldValue("Requisitos_codeudor");
                return view('plantillas/emisiones/vida', ["emision" => $emision, "requisitos" => $requisitos, "corequisitos" => $corequisitos]);
                break;
        }
    }

    //la plantilla carga mas rapido porque no tiene coberturas que cargar, por tanto json es mas eficiente
    public function cotizacion($cotizacion, $tipo)
    {
        $cotizacion = json_decode($cotizacion, true);
        switch ($tipo) {
            case 'incendio':
                return view('plantillas/cotizaciones/incendio', ["cotizacion" => $cotizacion]);
                break;
            case 'desempleo':
                $zoho = new Zoho;
                $requisitos = array();
                foreach ($cotizacion["planes"] as $plan) {
                    $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Vendor_Name:equals:" . $plan["id"] . ") and (Product_Category:equals:Desempleo))";
                    $coberturas = $zoho->searchRecordsByCriteria("Products", $criterio);
                    foreach ($coberturas as $cobertura) {
                        $requisitos[$plan["nombre"]] = $cobertura->getFieldValue("Requisitos_deudor");
                    }
                }
                return view('plantillas/cotizaciones/desempleo', ["cotizacion" => $cotizacion, "requisitos" => $requisitos]);
                break;
            case 'vida':
                $zoho = new Zoho;
                $requisitos = array();
                $corequisitos = array();
                foreach ($cotizacion["planes"] as $plan) {
                    $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Vendor_Name:equals:" . $plan["id"] . ") and (Product_Category:equals:Vida))";
                    $coberturas = $zoho->searchRecordsByCriteria("Products", $criterio);
                    foreach ($coberturas as $cobertura) {
                        $requisitos[$plan["nombre"]] = $cobertura->getFieldValue("Requisitos_deudor");
                        $corequisitos[$plan["nombre"]] = $cobertura->getFieldValue("Requisitos_codeudor");
                    }
                }
                return view('plantillas/cotizaciones/vida', ["cotizacion" => $cotizacion, "requisitos" => $requisitos, "corequisitos" => $corequisitos]);
                break;
        }
    }
}
