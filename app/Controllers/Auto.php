<?php

namespace App\Controllers;

use App\Libraries\Zoho;
use App\Models\Cotizacion;

class Auto extends BaseController
{
    public function lista_modelos()
    {
        //inicializar el contador
        $pag = 1;
        $libreria = new Zoho;
        //criterio de aseguir por la api
        $criteria = "Marca:equals:" . $this->request->getPost("marcaid");
        //repetir en secuencia para obtener todo los modelos de una misma marca, 
        //teniendo en cuenta que pueden ser mas de 200 en algunos casos
        // por tanto en necesario recontar la sentencia pero variando en paginas para superar el limite de la api
        do {
            //obtener los modelos empezando por la primera pagina
            $modelos =  $libreria->searchRecordsByCriteria("Modelos", $criteria, $pag);
            //en caso de encontrar valores
            if (!empty($modelos)) {
                //formatear el resultado para ordenarlo alfabeticamente en forma descendente
                asort($modelos);
                //aumentar el contador
                $pag++;
                //mostrar los valores en forma de option para luego ser mostrados en dentro de un select
                foreach ($modelos as $modelo) {
                    echo '<option value="' . $modelo->getEntityId() . "," . $modelo->getFieldValue('Tipo') . '">' . strtoupper($modelo->getFieldValue('Name')) . '</option>';
                }
            } else {
                //igualar a 0 el contador para salir del ciclo
                $pag = 0;
            }
        } while ($pag > 0);
    }

    public function cotizar()
    {
        $cotizacion = new Cotizacion;
        $libreria = new Zoho;

        //datos relacionados al modelo, dividios en un array
        $modelo = explode(",", $this->request->getPost("modelo"));
        //asignando valores al objeto
        $modeloid = $modelo[0];
        $modelotipo = $modelo[1];

        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("cuenta_id") . ") and (Product_Category:equals:Auto))";
        $planes =  $libreria->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$planes as $plan) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones
            //verificar limites de uso
            if (in_array($this->request->getPost("uso"), $plan->getFieldValue('Restringir_veh_culos_de_uso'))) {
                $comentario = "Uso del vehículo restringido.";
            }

            //verificar suma
            if (
                $this->request->getPost("suma") < $plan->getFieldValue('Suma_asegurada_min')
                and
                $this->request->getPost("suma") > $plan->getFieldValue('Suma_asegurada_max')
            ) {
                $comentario = "La suma asegurada no esta dentro de los limites.";
            }

            //verificar antiguedad
            if ((date("Y") - $this->request->getPost("ano")) > $plan->getFieldValue('Max_antig_edad')) {
                $comentario = "La antigüedad del vehículo es mayor al limite establecido.";
            }

            $criterio = "((Marca:equals:" . $this->request->getPost("marca") . ") and (Aseguradora:equals:" . $plan->getFieldValue('Vendor_Name')->getEntityId() . "))";
            $marcas = $libreria->searchRecordsByCriteria("Restringidos", $criterio, 1, 200);

            foreach ((array)$marcas as $marca) {
                if (empty($marca->getFieldValue('Modelo'))) {
                    $comentario = "Marca restrigida.";
                }
                if ($modeloid == $marca->getFieldValue('Modelo')->getEntityId()) {
                    $comentario = "Modelo restrigido.";
                }
            }

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular tasa
                //en caso de error que el valor termine en 0
                $valortasa = 0;
                //encontrar la tasa
                $criterio = "((Plan:equals:" . $plan->getEntityId() . ") and (A_o:equals:" . $this->request->getPost("ano") . "))";
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
                $criterio = "((Marca:equals:" . $this->request->getPost("marca") . ") and (Aseguradora:equals:" . $plan->getFieldValue('Vendor_Name')->getEntityId() . "))";
                $recargos = $libreria->searchRecordsByCriteria("Recargos", $criterio, 1, 200);

                foreach ((array)$recargos as $recargo) {
                    if (
                        ($this->request->getPost("ano") > $recargo->getFieldValue('Desde')
                            and
                            $this->request->getPost("ano") < $recargo->getFieldValue('Hasta')
                            and
                            $recargo->getFieldValue('Tipo') == $modelotipo)
                        or
                        ($modeloid == $recargo->getFieldValue('Modelo'))
                        or
                        ($recargo->getFieldValue('Tipo') == $modelotipo)
                        or
                        ($recargo->getFieldValue('Desde') > 0 and $this->request->getPost("ano") > $recargo->getFieldValue('Desde'))
                    ) {
                        $valorrecargo = $recargo->getFieldValue('Name') / 100;
                    }
                }


                //calcular prima
                //calculo para cotizacion auto
                $prima =  $this->request->getPost("suma") * ($valortasa + ($valortasa * $valorrecargo));

                //si el valor de la prima es muy bajo
                if ($prima > 0 and $prima < $plan->getFieldValue('Prima_m_nima')) {
                    $prima = $plan->getFieldValue('Prima_m_nima');
                }

                //en caso de ser mensual
                if ($this->request->getPost("plan") == "Mensual full") {
                    $prima = $prima / 12;
                }

                //en caso de haber algun problema
                if ($prima == 0) {
                    $comentario = "No existen tasas para el tipo del vehículo.";
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

        $cotizacion->auto(
            $this->request->getPost("marca"),
            $modeloid,
            $modelotipo,
            $this->request->getPost("plan"),
            $this->request->getPost("ano"),
            $this->request->getPost("uso"),
            $this->request->getPost("estado"),
            $this->request->getPost("suma")
        );

        //libreria del api para obtener todo los registros de un modulo, en este caso del de marcas
        $marcas = $libreria->getRecords("Marcas");
        //formatear el resultado para ordenarlo alfabeticamente en forma descendente
        asort($marcas);
        return view("cotizaciones/cotizar", ["titulo" => "Cotizar", "marcas" => $marcas, "cotizacion" => $cotizacion]);
    }
}
