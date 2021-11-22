<?php

namespace App\Models;

use App\Libraries\Zoho;

class CotizacionAuto extends Cotizacion
{
    public $marcaid;
    public $modeloid;
    public $modelotipo;
    public $ano;
    public $uso;
    public $estado;

    public function cotizar($marcaid, $modeloid, $modelotipo, $plan, $ano, $uso, $estado, $suma)
    {
        $libreria = new Zoho;

        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Auto))";
        $coberturas =  $libreria->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$coberturas as $cobertura) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones
            //verificar limites de uso
            if (in_array($uso, $cobertura->getFieldValue('Restringir_veh_culos_de_uso'))) {
                $comentario = "Uso del vehículo restringido.";
            }

            //verificar suma
            if (
                $suma < $cobertura->getFieldValue('Suma_asegurada_min')
                and
                $suma > $cobertura->getFieldValue('Suma_asegurada_max')
            ) {
                $comentario = "La suma asegurada no esta dentro de los limites.";
            }

            //verificar antiguedad
            if ((date("Y") -  $ano) > $cobertura->getFieldValue('Max_antig_edad')) {
                $comentario = "La antigüedad del vehículo es mayor al limite establecido.";
            }

            $criterio = "((Marca:equals:$marcaid) and (Aseguradora:equals:" . $cobertura->getFieldValue('Vendor_Name')->getEntityId() . "))";
            $marcas = $libreria->searchRecordsByCriteria("Restringidos", $criterio, 1, 200);

            foreach ((array)$marcas as $marca) {
                if (empty($marca->getFieldValue('Modelo'))) {
                    $comentario = "Marca restrigida.";
                } elseif ($modeloid == $marca->getFieldValue('Modelo')->getEntityId()) {
                    $comentario = "Modelo restrigido.";
                }
            }

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular tasa
                //en caso de error que el valor termine en 0
                $valortasa = 0;
                //encontrar la tasa
                $criterio = "((Plan:equals:" . $cobertura->getEntityId() . ") and (A_o:equals:$ano))";
                $tasas = $libreria->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

                foreach ((array)$tasas as $tasa) {
                    //bucar entre los grupos de vehiculo
                    if (in_array($modelotipo, $tasa->getFieldValue('Grupo_de_veh_culo'))) {
                        $valortasa = $tasa->getFieldValue('Name') / 100;
                    }
                }

                //calcular recargo
                //en caso de error que el valor termine en 0
                $valorrecargo = 0;

                //verificar si la aseguradora tiene algun recargo para la marca o modelo
                $criterio = "((Marca:equals:$marcaid) and (Aseguradora:equals:" . $cobertura->getFieldValue('Vendor_Name')->getEntityId() . "))";
                $recargos = $libreria->searchRecordsByCriteria("Recargos", $criterio, 1, 200);

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
                        ($recargo->getFieldValue('Desde') > 0 and $ano > $recargo->getFieldValue('Desde'))
                    ) {
                        $valorrecargo = $recargo->getFieldValue('Name') / 100;
                    }
                }

                //calcular prima
                //calculo para cotizacion auto
                $prima =  $suma * ($valortasa + ($valortasa * $valorrecargo));

                //si el valor de la prima es muy bajo
                if ($prima > 0 and $prima < $cobertura->getFieldValue('Prima_m_nima')) {
                    $prima = $cobertura->getFieldValue('Prima_m_nima');
                }

                //en caso de ser mensual
                if ($plan == "Mensual full") {
                    $prima = $prima / 12;
                }

                //en caso de haber algun problema
                if ($prima == 0) {
                    $comentario = "No existen tasas para el tipo del vehículo.";
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

        $this->marcaid = $marcaid;
        $this->modeloid = $modeloid;
        $this->modelotipo = $modelotipo;
        $this->plan = $plan;
        $this->ano = $ano;
        $this->uso = $uso;
        $this->estado = $estado;
        $this->suma = $suma;
    }
}
