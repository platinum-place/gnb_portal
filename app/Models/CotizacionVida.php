<?php

namespace App\Models;

class CotizacionVida extends Cotizacion
{
 
    public function cotizar($fecha_deudor, $fecha_codeudor, $plazo, $suma, $plan)
    {
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Vida))";
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
                $deudor = 0;
                $codeudor = 0;

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
                        $deudor = $tasa->getFieldValue('Name') / 100;
                    }

                    if (!empty($fecha_codeudor)) {
                        if (
                            $this->calcular_edad($fecha_codeudor) > $tasa->getFieldValue('Edad_min')
                            and
                            $this->calcular_edad($fecha_codeudor) < $tasa->getFieldValue('Edad_max')
                        ) {
                            $codeudor = $tasa->getFieldValue('Codeudor') / 100;
                        }
                    }
                }

                if ($deudor == 0) {
                    $comentario = "La edad del deudor no esta dentro del limite permitido.";
                } else {
                    //calcular prima un deudor
                    $prima_deudor = ($suma / 1000) * $deudor;

                    //calcular prima si existe un codeudor
                    if (!empty($fecha_codeudor)) {
                        if ($codeudor == 0) {
                            $comentario = "La edad del codeudor no esta dentro del limite permitido.";
                        }

                        $prima_codeudor = ($suma / 1000) * ($codeudor - $deudor);
                    } else {
                        $prima_codeudor = 0;
                    }

                    //retornar la union de ambas primas
                    $prima = $prima_deudor + $prima_codeudor;
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
        $this->fecha_codeudor = $fecha_codeudor;
        $this->plazo = $plazo;
        $this->suma = $suma;
        $this->plan = $plan;
    }
}
