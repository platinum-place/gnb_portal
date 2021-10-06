<?php

namespace App\Controllers;

use App\Libraries\Vida as LibrariesVida;
use App\Models\Cotizacion;

class Vida extends BaseController
{
    protected $libreria;

    function __construct()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $this->libreria = new LibrariesVida;
    }

    public function cotizar()
    {
        if ($this->request->getPost()) {
            //modelo para cotizacion
            $cotizacion = new Cotizacion;

            $cotizacion->suma = $this->request->getPost("suma");
            $cotizacion->plazo = $this->request->getPost("plazo");
            $cotizacion->tipo = "Vida";
            $cotizacion->plan = "Vida";
            $cotizacion->crm = "TU ASISTENCIA SALUD";
            $cotizacion->fecha_deudor = $this->request->getPost("deudor");
            $cotizacion->fecha_codeudor = $this->request->getPost("codeudor");

            //cotizar en libreria
            $this->libreria->cotizar($cotizacion);

            if (!empty($cotizacion->planes)) {
                session()->setFlashdata('alerta', '¡Cotización creada exitosamente! Para descargar la cotización, haz clic en "Continuar" y completa el formulario.');
            } else {
                session()->setFlashdata('alerta', 'No existen planes para cotizar.');
            }

            return view("vida/cotizar", ["titulo" => "Cotización de Plan Vida", "cotizacion" => $cotizacion]);
        }

        return view("vida/cotizar", ["titulo" => "Cotización de Plan Vida"]);
    }
}
