<?php

namespace App\Libraries;

class CotizacionesAuto extends Cotizaciones
{
    public function verificar_limites($cotizacion, $plan)
    {
        //verificar limites de uso
        if (in_array($cotizacion->uso, $plan->getFieldValue('Restringir_veh_culos_de_uso'))) {
            return "Uso del vehículo restringido.";
        }
        //verificar antiguedad
        if ((date("Y") - $cotizacion->ano) > $plan->getFieldValue('Max_antig_edad')) {
            return "La antigüedad del vehículo es mayor al limite establecido.";
        }
    }

    public function verificar_restringido($cotizacion, $plan)
    {
        $criterio = "((Marca:equals:" . $cotizacion->marcaid . ") and (Aseguradora:equals:" . $plan->getFieldValue('Vendor_Name')->getEntityId() . "))";
        $marcas = $this->searchRecordsByCriteria("Restringidos", $criterio, 1, 200);
        foreach ((array)$marcas as $marca) {
            if (empty($marca->getFieldValue('Modelo'))) {
                return "Marca restrigida.";
            } elseif ($cotizacion->modeloid == $marca->getFieldValue('Modelo')) {
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
}
