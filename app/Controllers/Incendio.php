<?php

namespace App\Controllers;

use App\Libraries\Zoho;
use App\Models\Cotizacion;

class Incendio extends BaseController
{
    public function cotizar()
    {
        $cotizacion = new Cotizacion;
        $libreria = new Zoho;
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Incendio))";
        $planes =  $libreria->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$planes as $plan) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular prima
                //encontrar la tasa
                $criterio = "Plan:equals:" . $plan->getEntityId();
                $tasas = $libreria->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

                foreach ((array)$tasas as $tasa) {
                    //verificar limite de edad
                    $prima = ($this->request->getPost("suma") / 100) * $tasa->getFieldValue('Name') / 100;
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

        $cotizacion->incendio(
            $this->request->getPost("suma"),
            $this->request->getPost("prestamo"),
            $this->request->getPost("plazo"),
            $this->request->getPost("riesgo"),
            $this->request->getPost("construccion"),
            $this->request->getPost("direccion"),
            $this->request->getPost("plan")
        );

        //libreria del api para obtener todo los registros de un modulo, en este caso del de marcas
        $marcas = $libreria->getRecords("Marcas");
        //formatear el resultado para ordenarlo alfabeticamente en forma descendente
        asort($marcas);
        return view("cotizaciones/cotizar", ["titulo" => "Cotizar", "marcas" => $marcas, "cotizacion" => $cotizacion]);
    }
}
