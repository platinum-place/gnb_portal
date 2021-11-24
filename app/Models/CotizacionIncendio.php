<?php

namespace App\Models;

class CotizacionIncendio extends Cotizacion
{


    public function cotizar($suma, $prestamo, $plazo, $riesgo, $construccion, $direccion, $plan)
    {
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Incendio))";
        $coberturas =  $this->libreria->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$coberturas as $cobertura) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular prima
                //encontrar la tasa
                $criterio = "Plan:equals:" . $cobertura->getEntityId();
                $tasas = $this->libreria->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

                foreach ((array)$tasas as $tasa) {
                    //verificar limite de edad
                    $prima = ($suma / 100) * $tasa->getFieldValue('Name') / 100;
                }
            }

            //lista con los resultados de cada calculo
            $this->planes[] = [
                "aseguradora" => $cobertura->getFieldValue('Product_Name'),
                "planid" => $cobertura->getEntityId(),
                "prima" => $prima - ($prima * 0.16),
                "neta" => $prima * 0.16,
                "total" => $prima,
                "suma" =>  $suma,
                "comentario" => $comentario
            ];
        }

        $this->suma = $suma;
        $this->prestamo = $prestamo;
        $this->plazo = $plazo;
        $this->riesgo = $riesgo;
        $this->construccion = $construccion;
        $this->direccion = $direccion;
        $this->plan = $plan;
    }
}
