<?php

namespace App\Controllers;

use App\Libraries\Desempleo as LibrariesDesempleo;
use App\Models\Cotizacion;

class Desempleo extends BaseController
{
    protected $libreria;

    function __construct()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $this->libreria = new LibrariesDesempleo;
    }

    public function cotizar()
    {
        if ($this->request->getPost()) {
            //modelo para cotizacion
            $cotizacion = new Cotizacion;

            $cotizacion->suma = $this->request->getPost("suma");
            $cotizacion->plazo = $this->request->getPost("plazo");
            $cotizacion->tipo = "Desempleo";
            $cotizacion->plan = "Vida/Desempleo";
            $cotizacion->crm = "TU ASISTENCIA SALUD";
            $cotizacion->fecha_deudor = $this->request->getPost("deudor");
            $cotizacion->cuota = $this->request->getPost("cuota");

            //cotizar en libreria
            $this->libreria->cotizar($cotizacion);

            if (!empty($cotizacion->planes)) {
                session()->setFlashdata('alerta', '¡Cotización creada exitosamente! Para descargar la cotización, haz clic en "Continuar" y completa el formulario.');
            } else {
                session()->setFlashdata('alerta', 'No existen planes para cotizar.');
            }

            return view("desempleo/cotizar", ["titulo" => "Cotización de Plan Vida/Desempleo", "cotizacion" => $cotizacion]);
        }

        return view("desempleo/cotizar", ["titulo" => "Cotización de Plan Vida/Desempleo"]);
    }
}
