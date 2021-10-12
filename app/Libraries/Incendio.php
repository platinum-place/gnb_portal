<?php

namespace App\Libraries;

class Incendio extends Cotizaciones
{
    public function calcular_prima($plan, $suma)
    {
        //inicializar valores vacios
        $prima = 0;

        //encontrar la tasa
        $criterio = "Plan:equals:" . $plan->getEntityId();
        $tasas = $this->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

        foreach ((array)$tasas as $tasa) {
            //verificar limite de edad
            $prima = ($suma / 100) * $tasa->getFieldValue('Name') / 100;;
        }

        //retornar la union de ambas primas
        return $prima;
    }

    public function cotizar($suma)
    {
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Incendio))";
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
                $prima = $this->calcular_prima($plan, $suma);

                //en caso de haber algun problema
                if (is_string($prima)) {
                    $comentario = $prima;
                    $prima = 0;
                }
            }

            //lista con los resultados de cada calculo
            $cotizacion[] = [
                "aseguradora" => $plan->getFieldValue('Product_Name'),
                "planid" => $plan->getEntityId(),
                "prima" => $prima - ($prima * 0.16),
                "neta" => $prima * 0.16,
                "total" => $prima,
                "suma" =>  $suma,
                "comentario" => $comentario
            ];
        }

        return $cotizacion;
    }
}
