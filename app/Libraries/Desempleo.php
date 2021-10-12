<?php

namespace App\Libraries;

class Desempleo extends Cotizaciones
{
    protected function calcular_edad($fecha)
    {
        list($ano, $mes, $dia) = explode("-", $fecha);
        $ano_diferencia  = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia   = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
            $ano_diferencia--;
        return $ano_diferencia;
    }

    public function verificar_limites($plan, $plazo, $suma)
    {
        //verificar limite de plazo
        if ($plazo > $plan->getFieldValue('Plazo_max')) {
            return "El plazo es mayor al limite establecido.";
        }

        //verificar limite suma
        if ($suma > $plan->getFieldValue('Suma_asegurada_max')) {
            return "La suma asegurada es mayor al limite establecido.";
        }
    }

    public function calcular_prima($plan, $fecha_deudor, $suma, $cuota)
    {
        //inicializar valores vacios
        $vida = 0;
        $desempleo = 0;

        //encontrar la tasa
        $criterio = "Plan:equals:" . $plan->getEntityId();
        $tasas = $this->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

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
                return "La edad del deudor no esta dentro del limite permitido.";
            }
        }
        //calcular prima
        $prima_vida = ($suma / 1000) * $vida;
        $prima_desempleo = ($cuota / 1000) * $desempleo;

        //retornar la union de ambas primas
        return $prima_vida + $prima_desempleo;
    }

    public function cotizar($fecha_deudor, $suma, $cuota, $plazo)
    {
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Desempleo))";
        $planes =  $this->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$planes as $plan) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones
            $comentario = $this->verificar_limites($plan, $plazo, $suma);

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular prima
                $prima = $this->calcular_prima($plan, $fecha_deudor, $suma, $cuota);

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
