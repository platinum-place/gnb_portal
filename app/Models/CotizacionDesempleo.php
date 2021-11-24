<?php

namespace App\Models;

class CotizacionDesempleo extends Cotizacion
{
 
    public function cotizar($fecha_deudor, $cuota, $plazo, $suma, $plan)
    {
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Desempleo))";
        $coberturas =  $this->libreria->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$coberturas as $cobertura) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones
            //verificar limite de plazo
            $comentario = $this->limite_plazo($plazo, $cobertura->getFieldValue('Plazo_max'));

            //verificar limite suma
            $comentario = $this->limite_suma($suma, $cobertura->getFieldValue('Suma_asegurada_min'), $cobertura->getFieldValue('Suma_asegurada_max'));
            

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular prima
                //inicializar valores vacios
                $vida = 0;
                $desempleo = 0;

                //encontrar la tasa
                $criterio = "Plan:equals:" . $cobertura->getEntityId();
                $tasas = $this->libreria->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

                foreach ((array)$tasas as $tasa) {
                    //verificar limite de edad
                    if (
                        $this->calcular_edad($fecha_deudor) > $tasa->getFieldValue('Edad_min')
                        and
                        $this->calcular_edad($fecha_deudor) < $tasa->getFieldValue('Edad_max')
                    ) {
                        $vida = $tasa->getFieldValue('Name') / 100;
                        $desempleo = $tasa->getFieldValue('Desempleo');
                    } else {
                        $comentario =  "La edad del deudor no esta dentro del limite permitido.";
                    }
                }

                //en caso de no haber algun problema
                if (empty($comentario)) {
                    //calcular prima
                    $prima_vida = ($suma / 1000) * $vida;
                    $prima_desempleo = ($cuota / 1000) * $desempleo;
                    //retornar la union de ambas primas
                    $prima =  $prima_vida + $prima_desempleo;
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

        $this->fecha_deudor = $fecha_deudor;
        $this->cuota = $cuota;
        $this->plazo = $plazo;
        $this->suma = $suma;
        $this->plan = $plan;
    }
}
