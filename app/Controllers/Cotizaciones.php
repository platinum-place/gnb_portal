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

    public function desempleo()
    {
        if ($this->request->getPost()) {
            list($ano, $mes, $dia) = explode("-", $this->request->getPost("fecha"));
            $ano_diferencia  = date("Y") - $ano;
            $mes_diferencia = date("m") - $mes;
            $dia_diferencia   = date("d") - $dia;
            if ($dia_diferencia < 0 || $mes_diferencia < 0)
                $ano_diferencia--;
            $edad = $ano_diferencia;

            //obtener todas las tasas relacionadas con el banco
            //las tasas tiene los requisitos que se compararan para saber si aplican o no
            $criterio = "((Intermediario:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Tipo:equals:Desempleo))";
            $tasas = $this->zoho->searchRecordsByCriteria("Tasas", $criterio);

            //se comparan los requisitos
            //crear un plan solo con el valor de la prima, ya que no require coberturas
            foreach ((array)$tasas as $tasa) {
                if ($this->request->getPost("plazo") > $tasa->getFieldValue('Plazo')) {
                    $alerta[] = array(
                        "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                        "motivo" => "El plazo es mayor al limite establecido."
                    );
                }

                if ($this->request->getPost("suma") > $tasa->getFieldValue('Suma_asegurada')) {
                    $alerta[] = array(
                        "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                        "motivo" => "La suma asegurada es mayor al limite establecido."
                    );
                }

                if ($edad < $tasa->getFieldValue('Edad_min')) {
                    $alerta[] = array(
                        "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                        "motivo" => "La edad es menor al limite permitido."
                    );
                }

                if ($edad > $tasa->getFieldValue('Edad_max')) {
                    $alerta[] = array(
                        "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                        "motivo" => "La edad es mayor al limite permitido."
                    );
                }

                if (empty($alerta)) {
                    $vida = ($this->request->getPost("suma") / 1000) * ($tasa->getFieldValue('Name') / 100);
                    $desempleo = ($this->request->getPost("cuota") / 1000) * $tasa->getFieldValue('Desempleo');
                } else {
                    $vida = 0;
                    $desempleo = 0;
                }

                $prima =  $vida + $desempleo;
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
                    "suma" => $this->request->getPost("suma"),
                    "cuota" => $this->request->getPost("cuota"),
                    "fecha" => $this->request->getPost("fecha"),
                    "planes" => $planes
                ];

                if (!empty($alerta)) {
                    $html = "<ul>";
                    foreach ($alerta as $mensaje) {
                        $html .= "<li><b>" . $mensaje["aseguradora"] . "</b>:" . $mensaje["motivo"] . "</li>";
                    }
                    $html .= "</ul>";

                    session()->setFlashdata('alerta', $html);
                }

                //pasar el array a la vista
                return view('cotizaciones/desempleo', ["cotizacion" => $cotizacion]);
            } else {
                //alerta en caso de no haber encontrado ningun plan
                session()->setFlashdata('alerta', 'No existen planes incendio para ' . session("usuario")->getFieldValue("Account_Name")->getLookupLabel());
            }
        }

        return view('cotizaciones/desempleo');
    }

    //la plantilla carga mas rapido porque no tiene coberturas que cargar, por tanto json es mas eficiente
    public function cotizaciondesempleo($detalles)
    {
        $detalles = json_decode($detalles, true);
        $requisitos = array();

        foreach ($detalles["planes"] as $plan) {
            $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Vendor_Name:equals:" . $plan["id"] . ") and (Product_Category:equals:Desempleo) )";
            $coberturas = $this->zoho->searchRecordsByCriteria("Products", $criterio);

            foreach ($coberturas as $cobertura) {
                $requisitos[$plan["nombre"]] = $cobertura->getFieldValue("Requisitos_deudor");
            }
        }

        return view('plantillas/cotizaciondesempleo', ["detalles" => $detalles, "requisitos" => $requisitos]);
    }
}
