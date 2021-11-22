<?php

namespace App\Models;

use App\Libraries\Zoho;

class CotizacionDesempleo extends Cotizacion
{
    public $cuota;

    public function cotizar($fecha_deudor, $cuota, $plazo, $suma, $plan)
    {
        $libreria = new Zoho;

        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Desempleo))";
        $coberturas =  $libreria->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$coberturas as $cobertura) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones
            //verificar limite de plazo
            if ($plazo > $cobertura->getFieldValue('Plazo_max')) {
                $comentario =  "El plazo es mayor al limite establecido.";
            }

            //verificar limite suma
            if ($suma > $cobertura->getFieldValue('Suma_asegurada_max')) {
                $comentario =  "La suma asegurada es mayor al limite establecido.";
            }

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular prima
                //inicializar valores vacios
                $vida = 0;
                $desempleo = 0;

                //encontrar la tasa
                $criterio = "Plan:equals:" . $cobertura->getEntityId();
                $tasas = $libreria->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

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
