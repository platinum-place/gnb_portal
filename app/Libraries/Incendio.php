<?php

namespace App\Libraries;

use App\Models\Cotizacion;

class Incendio extends Cotizaciones
{
    /*

    public function verificar_limites(Cotizacion $cotizacion, ZCRMRecord $plan)
    {
        //verificar limite de plazo
        if ($cotizacion->plazo > $plan->getFieldValue('Plazo_max')) {
            return "El plazo es mayor al limite establecido.";
        }

        //verificar limite suma
        if ($cotizacion->suma > $plan->getFieldValue('Suma_asegurada_max')) {
            return "La suma asegurada es mayor al limite establecido.";
        }
    }
    */
    public function calcular_prima(Cotizacion $cotizacion, $plan)
    {
        //inicializar valores vacios
        $prima = 0;

        //encontrar la tasa
        $criterio = "Plan:equals:" . $plan->getEntityId();
        $tasas = $this->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

        foreach ((array)$tasas as $tasa) {
            //verificar limite de edad
            $prima = ($cotizacion->suma / 100) * $tasa->getFieldValue('Name') / 100;;
        }

        //retornar la union de ambas primas
        return $prima;
    }

    public function cotizar(Cotizacion $cotizacion)
    {
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Product_Category:equals:Incendio))";
        $planes =  $this->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$planes as $plan) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones
            //$comentario = $this->verificar_limites($cotizacion, $plan);

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular prima
                $prima = $this->calcular_prima($cotizacion, $plan);

                //en caso de haber algun problema
                if (is_string($prima)) {
                    $comentario = $prima;
                    $prima = 0;
                }
            }

            //lista con los resultados de cada calculo
            $cotizacion->planes[] = [
                "aseguradora" => $plan->getFieldValue('Vendor_Name')->getLookupLabel(),
                "aseguradoraid" => $plan->getFieldValue('Vendor_Name')->getEntityId(),
                "planid" => $plan->getEntityId(),
                "prima" => $prima - ($prima * 0.16),
                "neta" => $prima * 0.16,
                "total" => $prima,
                "suma" =>  $cotizacion->suma,
                "comentario" => $comentario
            ];
        }
    }
}
