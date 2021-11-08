<?php

namespace App\Controllers;

use App\Libraries\Zoho;
use App\Models\Cotizacion;

class Desempleo extends BaseController
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

    public function cotizar()
    {
        $cotizacion = new Cotizacion;
        $libreria = new Zoho;

        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Desempleo))";
        $planes =  $libreria->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$planes as $plan) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones
            //verificar limite de plazo
            if ($this->request->getPost("plazo") > $plan->getFieldValue('Plazo_max')) {
                $comentario =  "El plazo es mayor al limite establecido.";
            }

            //verificar limite suma
            if ($this->request->getPost("suma") > $plan->getFieldValue('Suma_asegurada_max')) {
                $comentario =  "La suma asegurada es mayor al limite establecido.";
            }

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular prima
                //inicializar valores vacios
                $vida = 0;
                $desempleo = 0;

                //encontrar la tasa
                $criterio = "Plan:equals:" . $plan->getEntityId();
                $tasas = $libreria->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

                foreach ((array)$tasas as $tasa) {
                    //verificar limite de edad
                    if (
                        $this->calcular_edad($this->request->getPost("deudor")) > $tasa->getFieldValue('Edad_min')
                        and
                        $this->calcular_edad($this->request->getPost("deudor")) < $tasa->getFieldValue('Edad_max')
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
                    $prima_vida = ($this->request->getPost("suma") / 1000) * $vida;
                    $prima_desempleo = ($this->request->getPost("cuota") / 1000) * $desempleo;
                    //retornar la union de ambas primas
                    $prima =  $prima_vida + $prima_desempleo;
                }
            }

            //lista con los resultados de cada calculo
            $cotizacion->planes[] = [
                "aseguradora" => $plan->getFieldValue('Product_Name'),
                "planid" => $plan->getEntityId(),
                "prima" => $prima - ($prima * 0.16),
                "neta" => $prima * 0.16,
                "total" => $prima,
                "suma" =>  $this->request->getPost("suma"),
                "comentario" => $comentario
            ];
        }

        if (empty($cotizacion->planes)) {
            session()->setFlashdata('alerta', 'No existen planes para cotizar.');
        }

        $cotizacion->desempleo(
            $this->request->getPost("deudor"),
            $this->request->getPost("cuota"),
            $this->request->getPost("plazo"),
            $this->request->getPost("suma"),
            $this->request->getPost("plan")
        );

        //libreria del api para obtener todo los registros de un modulo, en este caso del de marcas
        $marcas = $libreria->getRecords("Marcas");
        //formatear el resultado para ordenarlo alfabeticamente en forma descendente
        asort($marcas);
        return view("cotizaciones/cotizar", ["titulo" => "Cotizar", "marcas" => $marcas, "cotizacion" => $cotizacion]);
    }
}
