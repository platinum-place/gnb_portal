<?php

namespace App\Libraries\Cotizar;

class CotizarVida extends Cotizar
{
    private $deudor = 0;
    private $codeudor = 0;

    private function verificar_comentarios($Plazo_max, $Suma_asegurada_min, $Suma_asegurada_max): string
    {
        if ($comentario = $this->limite_plazo($Plazo_max)) {
            return $comentario;
        }

        if ($comentario = $this->limite_suma($Suma_asegurada_min, $Suma_asegurada_max)) {
            return $comentario;
        }

        return "";
    }

    private function calcular_tasas($zoho, $coberturaid)
    {
        //encontrar la tasa
        $criterio = "Plan:equals:$coberturaid";
        $tasas = $zoho->searchRecordsByCriteria("Tasas", $criterio);

        foreach ((array)$tasas as $tasa) {
            //verificar limite de edad
            if (
                $this->calcular_edad($this->cotizacion->fecha_deudor) > $tasa->getFieldValue('Edad_min')
                and
                $this->calcular_edad($this->cotizacion->fecha_deudor) < $tasa->getFieldValue('Edad_max')
            ) {
                $this->deudor = $tasa->getFieldValue('Name') / 100;
            }

            if (!empty($this->cotizacion->fecha_codeudor)) {
                if (
                    $this->calcular_edad($this->cotizacion->fecha_codeudor) > $tasa->getFieldValue('Edad_min')
                    and
                    $this->calcular_edad($this->cotizacion->fecha_codeudor) < $tasa->getFieldValue('Edad_max')
                ) {
                    $this->codeudor = $tasa->getFieldValue('Codeudor') / 100;
                }
            }
        }
    }

    private function calcular_prima($zoho, $coberturaid)
    {

        //calcular tasas
        $this->calcular_tasas($zoho, $coberturaid);

        //si existe codeudor
        if (!empty($this->cotizacion->fecha_codeudor)) {
            $prima_deudor = ($this->cotizacion->suma / 1000) * $this->deudor;
            $prima_codeudor = ($this->cotizacion->suma / 1000) * ($this->codeudor - $this->deudor);
            return $prima_deudor + $prima_codeudor;
        } else {
            return ($this->cotizacion->suma / 1000) * $this->deudor;
        }
    }

    public function cotizar_planes($zoho)
    {
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Vida))";
        $coberturas = $zoho->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$coberturas as $cobertura) {
            //inicializacion de variables
            $prima = 0;

            //verificaciones
            $comentario = $this->verificar_comentarios(
                $cobertura->getFieldValue('Plazo_max'),
                $cobertura->getFieldValue('Suma_asegurada_min'),
                $cobertura->getFieldValue('Suma_asegurada_max')
            );

            //si no hubo un excepcion
            if (empty($comentario)) {
                $prima = $this->calcular_prima($zoho, $cobertura->getEntityId());

                // en caso de haber algun problema
                if ($prima == 0) {
                    $comentario = "La edad del deudor o deudor no estan dentro del limite permitido.";
                }
            }

            //lista con los resultados de cada calculo
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