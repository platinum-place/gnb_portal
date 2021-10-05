<?php

namespace App\Controllers;

use App\Libraries\Auto as LibrariesAuto;
use App\Models\Cotizacion;

class Auto extends BaseController
{
    protected $libreria;

    function __construct()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $this->libreria = new LibrariesAuto;
    }

    //funcion post
    public function modelos()
    {
        //inicializar el contador
        $pag = 1;

        //criterio de aseguir por la api
        $criteria = "Marca:equals:" . $this->request->getPost("marcaid");

        //repetir en secuencia para obtener todo los modelos de una misma marca, 
        //teniendo en cuenta que pueden ser mas de 200 en algunos casos
        // por tanto en necesario recontar la sentencia pero variando en paginas para superar el limite de la api
        do {

            //obtener los modelos empezando por la primera pagina
            $modelos =  $this->libreria->searchRecordsByCriteria("Modelos", $criteria, $pag);

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
        //libreria del api para obtener todo los registros de un modulo, en este caso del de marcas
        $marcas = $this->libreria->getRecords("Marcas");
        //formatear el resultado para ordenarlo alfabeticamente en forma descendente
        asort($marcas);

        if ($this->request->getPost()) {
            //modelo para cotizacion
            $cotizacion = new Cotizacion;

            $cotizacion->suma = $this->request->getPost("suma");
            $cotizacion->ano = $this->request->getPost("ano");
            $cotizacion->uso = $this->request->getPost("uso");
            $cotizacion->plan = $this->request->getPost("plan");
            $cotizacion->estado = $this->request->getPost("estado");
            $cotizacion->marcaid = $this->request->getPost("marca");
            $cotizacion->tipo = "Auto";

            //datos relacionados al modelo, dividios en un array
            $modelo = explode(",", $this->request->getPost("modelo"));

            //asignando valores al objeto
            $cotizacion->modeloid = $modelo[0];
            $cotizacion->modelotipo = $modelo[1];

            //cotizar en libreria
            $this->libreria->cotizar($cotizacion);

            if (!empty($cotizacion->planes)) {
                session()->setFlashdata('alerta', '¡Cotización creada exitosamente! Para descargar la cotización, haz clic en "Continuar" y completa el formulario.');
            } else {
                session()->setFlashdata('alerta', 'No existen planes para cotizar.');
            }

            return view("auto/cotizar", ["titulo" => "Cotización de Plan Auto", "marcas" => $marcas, "cotizacion" => $cotizacion]);
        }

        return view("auto/cotizar", ["titulo" => "Cotización de Plan Auto", "marcas" => $marcas]);
    }
}
