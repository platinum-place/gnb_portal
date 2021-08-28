<?php

namespace App\Controllers;

use App\Libraries\Cotizaciones as LibrariesCotizaciones;
use App\Libraries\Incendio;
use App\Libraries\Zoho;

class Cotizaciones extends BaseController
{
    protected $zoho;
    protected $libreria;

    function __construct()
    {
        $this->zoho = new Zoho;
        $this->libreria = new LibrariesCotizaciones($this->zoho);
    }

    public function index()
    {
        return view('cotizaciones/index');
    }

    public function incendio()
    {
        if ($this->request->getPost()) {
            $planes = $this->libreria->calcularplanesincendio($this->request->getPost("propiedad"));

            if (!empty($planes)) {
                $cotizacion = [
                    "cliente" => $this->request->getPost("cliente"),
                    "plazo" => $this->request->getPost("plazo"),
                    "propiedad" => $this->request->getPost("propiedad"),
                    "prestamo" => $this->request->getPost("prestamo"),
                    "constrccion" => $this->request->getPost("constrccion"),
                    "riesgo" => $this->request->getPost("riesgo"),
                    "direccion" => $this->request->getPost("direccion"),
                    "planes" => $planes
                ];

                return view('cotizaciones/incendio', ["cotizacion" => $cotizacion]);
            }

            session()->setFlashdata('alerta', 'Ha ocurrido un error');
        }
        return view('cotizaciones/incendio');
    }

    public function cotizacionincendio($cotizacion)
    {
        $cotizacion = json_decode($cotizacion);
        return view('plantillas/cotizacionincendio', ["cotizacion" => $cotizacion, "zoho" => $this->zoho]);
    }
}
