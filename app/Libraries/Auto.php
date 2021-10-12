<?php

namespace App\Libraries;

class Auto extends Cotizaciones
{
    public function verificar_limites($plan, $uso, $suma, $ano, $marcaid, $modeloid)
    {
        //verificar limites de uso
        if (in_array($uso, $plan->getFieldValue('Restringir_veh_culos_de_uso'))) {
            return "Uso del vehículo restringido.";
        }

        //verificar suma
        if (
            $suma < $plan->getFieldValue('Suma_asegurada_min')
            and
            $suma > $plan->getFieldValue('Suma_asegurada_max')
        ) {
            return "La suma asegurada no esta dentro de los limites.";
        }

        //verificar antiguedad
        if ((date("Y") - $ano) > $plan->getFieldValue('Max_antig_edad')) {
            return "La antigüedad del vehículo es mayor al limite establecido.";
        }

        $criterio = "((Marca:equals:" . $marcaid . ") and (Aseguradora:equals:" . $plan->getFieldValue('Vendor_Name')->getEntityId() . "))";
        $marcas = $this->searchRecordsByCriteria("Restringidos", $criterio, 1, 200);

        foreach ((array)$marcas as $marca) {
            if (empty($marca->getFieldValue('Modelo'))) {
                return "Marca restrigida.";
            }
            if ($modeloid == $marca->getFieldValue('Modelo')->getEntityId()) {
                return "Modelo restrigido.";
            }
        }
    }

    public function calcular_tasa($plan, $ano, $modelotipo)
    {
        //en caso de error que el valor termine en 0
        $valortasa = 0;
        //encontrar la tasa
        $criterio = "((Plan:equals:" . $plan->getEntityId() . ") and (A_o:equals:$ano))";
        $tasas = $this->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

        foreach ((array)$tasas as $tasa) {
            //bucar entre los grupos de vehiculo
            if (in_array($modelotipo, $tasa->getFieldValue('Grupo_de_veh_culo'))) {
                $valortasa = $tasa->getFieldValue('Name') / 100;
            }
        }

        return $valortasa;
    }

    public function calcular_recargo($plan, $marcaid, $ano, $modelotipo, $modeloid)
    {
        //en caso de error que el valor termine en 0
        $valorrecargo = 0;

        //verificar si la aseguradora tiene algun recargo para la marca o modelo
        $criterio = "((Marca:equals:$marcaid) and (Aseguradora:equals:" . $plan->getFieldValue('Vendor_Name')->getEntityId() . "))";
        $recargos = $this->searchRecordsByCriteria("Recargos", $criterio, 1, 200);

        foreach ((array)$recargos as $recargo) {
            if (
                ($ano > $recargo->getFieldValue('Desde')
                    and
                    $ano < $recargo->getFieldValue('Hasta')
                    and
                    $recargo->getFieldValue('Tipo') == $modelotipo)
                or
                ($modeloid == $recargo->getFieldValue('Modelo'))
                or
                ($recargo->getFieldValue('Tipo') == $modelotipo)
                or
                ($ano > $recargo->getFieldValue('Desde'))
            ) {
                $valorrecargo = $recargo->getFieldValue('Name') / 100;
            }
        }

        return $valorrecargo;
    }

    public function calcular_prima($suma, $tasa, $recargo)
    {
        //calculo para cotizacion auto
        return  $suma * ($tasa + ($tasa * $recargo));
    }

    public function cotizar($uso, $suma, $ano, $marcaid, $modeloid, $modelotipo, $plan_auto)
    {
        $cotizacion = array();
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Auto))";
        $planes =  $this->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$planes as $plan) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones
            $comentario = $this->verificar_limites($plan, $uso, $suma, $ano, $marcaid, $modeloid);

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular tasa
                $tasa = $this->calcular_tasa($plan, $ano, $modelotipo);

                //calcular recargo
                $recargo = $this->calcular_recargo($plan, $marcaid, $ano, $modelotipo, $modeloid);

                //calcular prima
                $prima = $this->calcular_prima($suma, $tasa, $recargo);

                //si el valor de la prima es muy bajo
                if ($prima > 0 and $prima < $plan->getFieldValue('Prima_m_nima')) {
                    $prima = $plan->getFieldValue('Prima_m_nima');
                }

                //en caso de ser mensual
                if ($plan_auto == "Mensual full") {
                    $prima = $prima / 12;
                }

                //en caso de haber algun problema
                if ($prima == 0) {
                    $comentario = "No existen tasas para el año o tipo del vehículo.";
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
