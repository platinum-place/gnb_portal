<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Cotizaciones extends BaseController
{
    protected $zoho;

    function __construct()
    {
        $this->zoho = new Zoho;
    }

    public function index()
    {
        return view('cotizaciones/index');
    }

    public function incendio()
    {
        if ($this->request->getPost()) {
            //obtener todas las tasas relacionadas con el banco
            //las tasas tiene los requisitos que se compararan para saber si aplican o no
            $criterio = "((Intermediario:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Tipo:equals:Incendio))";
            $tasas = $this->zoho->searchRecordsByCriteria("Tasas", $criterio);

            //se comparan los requisitos
            //crear un plan solo con el valor de la prima, ya que no require coberturas
            foreach ((array)$tasas as $tasa) {
                $prima = ($this->request->getPost("propiedad") / 100) * ($tasa->getFieldValue('Name') / 100);
                $isc = $prima * 0.16;
                $total = $prima + $isc;

                $planes[] = [
                    "nombre" => $tasa->getFieldValue("Aseguradora")->getLookupLabel(),
                    "id" => $tasa->getFieldValue("Aseguradora")->getEntityId(),
                    "neta" => $prima,
                    "isc" => $isc,
                    "total" => $total,
                ];
            }

            //en caso de no haber creado algun plan, continua creando la cotizacion
            //en caso de que no, el banco no tiene ninguna tasa y por tanto, nigun plan incendio
            if (!empty($planes)) {
                //tomar los valores del formulario para luego crear un json que sera usado despues
                $cotizacion = [
                    "cliente" => $this->request->getPost("cliente"),
                    "plazo" => $this->request->getPost("plazo"),
                    "propiedad" => $this->request->getPost("propiedad"),
                    "prestamo" => $this->request->getPost("prestamo"),
                    "construccion" => $this->request->getPost("construccion"),
                    "riesgo" => $this->request->getPost("riesgo"),
                    "direccion" => $this->request->getPost("direccion"),
                    "planes" => $planes
                ];

                //pasar el array a la vista
                return view('cotizaciones/incendio', ["cotizacion" => $cotizacion]);
            }

            //alerta en caso de no haber encontrado ningun plan
            session()->setFlashdata('alerta', 'No existen planes incendio para ' . session("usuario")->getFieldValue("Account_Name")->getLookupLabel());
        }

        return view('cotizaciones/incendio');
    }

    //la plantilla carga mas rapido porque no tiene coberturas que cargar, por tanto json es mas eficiente
    public function cotizacionincendio($detalles)
    {
        $detalles = json_decode($detalles, true);
        return view('plantillas/cotizacionincendio', ["detalles" => $detalles]);
    }
}
