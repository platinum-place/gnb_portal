<?php

namespace App\Libraries;

use App\Models\Cotizacion;

class Auto extends Cotizaciones
{
    public function verificar_limites(Cotizacion $cotizacion, $plan)
    {
        //verificar limites de uso
        if (in_array($cotizacion->uso, $plan->getFieldValue('Restringir_veh_culos_de_uso'))) {
            return "Uso del vehículo restringido.";
        }

        //verificar suma
        if (
            $cotizacion->suma < $plan->getFieldValue('Suma_asegurada_min')
            and
            $cotizacion->suma > $plan->getFieldValue('Suma_asegurada_max')
        ) {
            return "La suma asegurada no esta dentro de los limites.";
        }

        //verificar antiguedad
        if ((date("Y") - $cotizacion->ano) > $plan->getFieldValue('Max_antig_edad')) {
            return "La antigüedad del vehículo es mayor al limite establecido.";
        }

        $criterio = "((Marca:equals:" . $cotizacion->marcaid . ") and (Aseguradora:equals:" . $plan->getFieldValue('Vendor_Name')->getEntityId() . "))";
        $marcas = $this->searchRecordsByCriteria("Restringidos", $criterio, 1, 200);

        foreach ((array)$marcas as $marca) {
            if (empty($marca->getFieldValue('Modelo'))) {
                return "Marca restrigida.";
            }
            if ($cotizacion->modeloid == $marca->getFieldValue('Modelo')->getEntityId()) {
                return "Modelo restrigido.";
            }
        }
    }

    public function calcular_tasa($cotizacion, $plan)
    {
        //en caso de error que el valor termine en 0
        $valortasa = 0;
        //encontrar la tasa
        $criterio = "((Plan:equals:" . $plan->getEntityId() . ") and (A_o:equals:" . $cotizacion->ano . "))";
        $tasas = $this->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

        foreach ((array)$tasas as $tasa) {
            //bucar entre los grupos de vehiculo
            if (in_array($cotizacion->modelotipo, $tasa->getFieldValue('Grupo_de_veh_culo'))) {
                $valortasa = $tasa->getFieldValue('Name') / 100;
            }
        }

        return $valortasa;
    }

    public function calcular_recargo($cotizacion, $plan)
    {
        //en caso de error que el valor termine en 0
        $valorrecargo = 0;

        //verificar si la aseguradora tiene algun recargo para la marca o modelo
        $criterio = "((Marca:equals:" . $cotizacion->marcaid . ") and (Aseguradora:equals:" . $plan->getFieldValue('Vendor_Name')->getEntityId() . "))";
        $recargos = $this->searchRecordsByCriteria("Recargos", $criterio, 1, 200);

        foreach ((array)$recargos as $recargo) {
            if (
                empty($recargo->getFieldValue('Modelo'))
                and
                empty($recargo->getFieldValue('Desde'))
                and
                empty($recargo->getFieldValue('Hasta'))
                and
                empty($recargo->getFieldValue('Tipo'))
            ) {
                $valorrecargo = $recargo->getFieldValue('Name') / 100;
            } elseif (
                $cotizacion->modeloid == $recargo->getFieldValue('Modelo')
            ) {
                $valorrecargo = $recargo->getFieldValue('Name') / 100;
            } elseif (
                $cotizacion->ano > $recargo->getFieldValue('Desde')
                and
                $cotizacion->ano < $recargo->getFieldValue('Hasta')
            ) {
                $valorrecargo = $recargo->getFieldValue('Name') / 100;
            }
        }

        return $valorrecargo;
    }

    public function calcular_prima($cotizacion, $tasa, $recargo)
    {
        //calculo para cotizacion auto
        return  $cotizacion->suma * ($tasa + ($tasa * $recargo));
    }

    public function cotizar(Cotizacion $cotizacion)
    {
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Product_Category:equals:Auto))";
        $planes =  $this->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$planes as $plan) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones
            $comentario = $this->verificar_limites($cotizacion, $plan);

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular tasa
                $tasa = $this->calcular_tasa($cotizacion, $plan);

                //calcular recargo
                $recargo = $this->calcular_recargo($cotizacion, $plan);

                //calcular prima
                $prima = $this->calcular_prima($cotizacion, $tasa, $recargo);

                //si el valor de la prima es muy bajo
                if ($prima > 0 and $prima < $plan->getFieldValue('Prima_m_nima')) {
                    $prima = $plan->getFieldValue('Prima_m_nima');
                }

                //en caso de ser mensual
                if ($cotizacion->plan == "Mensual full") {
                    $prima = $prima / 12;
                }

                //en caso de haber algun problema
                if ($prima == 0) {
                    $comentario = "No existen tasas para el año o tipo del vehículo.";
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
