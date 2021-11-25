<?php

namespace App\Libraries;

class CotizarAuto extends Cotizar
{
    private function uso_restringido($Restringir_veh_culos_de_uso): string
    {
        if (in_array($this->cotizacion->uso, $Restringir_veh_culos_de_uso)) {
            return "Uso del vehículo restringido.";
        }

        return "";
    }

    private function antiguedad($Max_antig_edad): string
    {
        if ((date("Y") - $this->cotizacion->ano) > $Max_antig_edad) {
            return "La antigüedad del vehículo es mayor al limite establecido.";
        }

        return "";
    }

    private function vehiculo_restringido($aseguradoraid): string
    {
        $criterio = "((Marca:equals:" . $this->cotizacion->marcaid . ") and (Aseguradora:equals:$aseguradoraid))";
        $marcas = $this->zoho->searchRecordsByCriteria("Restringidos", $criterio);

        foreach ((array)$marcas as $marca) {
            if (empty($marca->getFieldValue('Modelo'))) {
                return "Marca restrigida.";
            } elseif ($this->cotizacion->modeloid == $marca->getFieldValue('Modelo')->getEntityId()) {
                return "Modelo restrigido.";
            }
        }

        return "";
    }

    private function calcular_tasa($planid)
    {
        $valortasa = 0;
        // encontrar la tasa
        $criterio = "((Plan:equals:$planid) and (A_o:equals:" . $this->cotizacion->ano . "))";
        $tasas = $this->zoho->searchRecordsByCriteria("Tasas", $criterio);

        foreach ((array)$tasas as $tasa) {
            // bucar entre los grupos de vehiculo
            if (in_array($this->cotizacion->modelotipo, $tasa->getFieldValue('Grupo_de_veh_culo'))) {
                $valortasa = $tasa->getFieldValue('Name') / 100;
            }
        }

        return $valortasa;
    }

    private function calcular_recargo($aseguradoraid)
    {
        $valorrecargo = 0;

        // verificar si la aseguradora tiene algun recargo para la marca o modelo
        $criterio = "((Marca:equals:" . $this->cotizacion->marcaid . ") and (Aseguradora:equals:$aseguradoraid))";
        $recargos = $this->zoho->searchRecordsByCriteria("Recargos", $criterio);

        foreach ((array)$recargos as $recargo) {
            if (
                ($this->cotizacion->ano > $recargo->getFieldValue('Desde')
                    and $this->cotizacion->ano < $recargo->getFieldValue('Hasta')
                    and $recargo->getFieldValue('Tipo') == $this->cotizacion->modelotipo)
                or
                ($this->cotizacion->modeloid == $recargo->getFieldValue('Modelo'))
                or
                ($recargo->getFieldValue('Tipo') == $this->cotizacion->modelotipo)
                or
                ($recargo->getFieldValue('Desde') > 0 and $this->cotizacion->ano > $recargo->getFieldValue('Desde'))
            ) {
                $valorrecargo = $recargo->getFieldValue('Name') / 100;
            }
        }

        return $valorrecargo;
    }

    private function calcular_prima($coberturaid, $aseguradoraid, $prima_minima)
    {
        // calcular tasa
        // en caso de error que el valor termine en 0
        $tasa = $this->calcular_tasa($coberturaid);

        // calcular recargo
        // en caso de error que el valor termine en 0
        $recargo = $this->calcular_recargo($aseguradoraid);

        // calcular prima
        // calculo para cotizacion auto
        $prima = $this->cotizacion->suma * ($tasa + ($tasa * $recargo));

        // si el valor de la prima es muy bajo
        if ($prima > 0 and $prima < $prima_minima) {
            $prima = $prima_minima;
        }

        // en caso de ser mensual
        if ($this->cotizacion->plan == "Mensual full") {
            $prima = $prima / 12;
        }

        return $prima;
    }

    private function verificar_comentarios(
        $Restringir_veh_culos_de_uso,
        $Suma_asegurada_min,
        $Suma_asegurada_max,
        $Max_antig_edad,
        $aseguradoraid
    ): string
    {
        // verificar limites de uso
        if ($comentario = $this->uso_restringido($Restringir_veh_culos_de_uso)) {
            return $comentario;
        }

        // verificar suma
        if ($comentario = $this->limite_suma($Suma_asegurada_min, $Suma_asegurada_max)) {
            return $comentario;
        }

        // verificar antiguedad
        if ($comentario = $this->antiguedad($Max_antig_edad)) {
            return $comentario;
        }

        // verificar si la marca o modelo esta restringidos en la aseguradora
        if ($comentario = $this->vehiculo_restringido($aseguradoraid)) {
            return $comentario;
        }

        return "";
    }

    public function cotizar_planes()
    {
        // planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Auto))";
        $coberturas = $this->zoho->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$coberturas as $cobertura) {
            // inicializacion de variables
            $prima = 0;

            // verificaciones
            $comentario = $this->verificar_comentarios(
                $cobertura->getFieldValue('Restringir_veh_culos_de_uso'),
                $cobertura->getFieldValue('Suma_asegurada_min'),
                $cobertura->getFieldValue('Suma_asegurada_max'),
                $cobertura->getFieldValue('Max_antig_edad'),
                $cobertura->getFieldValue('Vendor_Name')->getEntityId()
            );

            // si no hubo un excepcion
            if (empty($comentario)) {
                $prima = $this->calcular_prima(
                    $cobertura->getEntityId(),
                    $cobertura->getFieldValue('Vendor_Name')->getEntityId(),
                    $cobertura->getFieldValue('Prima_m_nima')
                );

                // en caso de haber algun problema
                if ($prima == 0) {
                    $comentario = "No existen tasas para el tipo del vehículo.";
                }
            }

            // lista con los resultados de cada calculo
            $this->cotizacion->planes[] = [
                "aseguradora" => $cobertura->getFieldValue('Product_Name'),
                "planid" => $cobertura->getEntityId(),
                "prima" => $prima - ($prima * 0.16),
                "neta" => $prima * 0.16,
                "total" => $prima,
                "suma" => $this->cotizacion->suma,
                "comentario" => $comentario
            ];
        }
    }
}

