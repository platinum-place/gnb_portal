<?php

namespace App\Controllers;

use App\Libraries\Incendio as LibrariesIncendio;
use App\Models\Cotizacion;

class Incendio extends BaseController
{
    protected $libreria;

    function __construct()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $this->libreria = new LibrariesIncendio;
    }

    public function cotizar_incendio()
    {
        if ($this->request->getPost()) {
            //modelo para cotizacion
            $cotizacion = new Cotizacion;

            $cotizacion->suma = $this->request->getPost("suma");
            $cotizacion->plazo = $this->request->getPost("plazo");
            $cotizacion->tipo = "Incendio";
            $cotizacion->plan = "Incendio Hipotecario";
            $cotizacion->cuota = $this->request->getPost("cuota");

            //cotizar en libreria
            $this->libreria->cotizar($cotizacion);

            if (!empty($cotizacion->planes)) {
                session()->setFlashdata('alerta', '¡Cotización creada exitosamente! Para descargar la cotización, haz clic en "Continuar" y completa el formulario.');
            } else {
                session()->setFlashdata('alerta', 'No existen planes para cotizar.');
            }

            return view("incendio/cotizar", ["titulo" => "Cotización de Seguro Incendio Hipotecario", "cotizacion" => $cotizacion]);
        }

        return view("incendio/cotizar", ["titulo" => "Cotización de Seguro Incendio Hipotecario"]);
    }
}
