<?php

namespace App\Controllers;

use App\Libraries\Zoho;
use App\Models\cotizaciondesempleo;
use App\Models\Cotizacionvida;
use App\Models\Cotizacionincendio;

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
            $cotizacion = new Cotizacionincendio;
            //en caso de no haber creado algun plan, continua creando la cotizacion
            //en caso de que no, el banco no tiene ninguna tasa y por tanto, nigun plan incendio
            if ($cotizacion->obtener_tasas("Incendio") == true) {
                $cotizacion->obtener_planes($this->request->getPost("propiedad"));
                $cotizacion->establecer_cotizacion(
                    $this->request->getPost("plazo"),
                    $this->request->getPost("propiedad"),
                    $this->request->getPost("prestamo"),
                    $this->request->getPost("construccion"),
                    $this->request->getPost("riesgo")
                );
                return view('cotizaciones/incendio', ["cotizacion" => $cotizacion]);
            }
            //alerta en caso de no haber encontrado ningun plan
            session()->setFlashdata('alerta', 'No existen planes incendio para ' . session("usuario")->getFieldValue("Account_Name")->getLookupLabel());
        }
        return view('cotizaciones/incendio');
    }

    public function desempleo()
    {
        if ($this->request->getPost()) {
            $cotizacion = new cotizaciondesempleo;
            $edad = $cotizacion->calcular_edad($this->request->getPost("fecha"));
            //obtener todas las tasas relacionadas con el banco
            //las tasas tiene los requisitos que se compararan para saber si aplican o no
            if ($cotizacion->obtener_tasas("Desempleo") == true) {
                $alerta = $cotizacion->obtener_planes(
                    $this->request->getPost("plazo"),
                    $this->request->getPost("suma"),
                    $edad,
                    $this->request->getPost("cuota")
                );
                $cotizacion->establecer_cotizacion(
                    $this->request->getPost("plazo"),
                    $this->request->getPost("suma"),
                    $this->request->getPost("cuota"),
                    $edad,
                    $this->request->getPost("fecha")
                );
                if (!empty($alerta)) {
                    $html = "<ul>";
                    foreach ($alerta as $mensaje) {
                        $html .= "<li><b>" . $mensaje["aseguradora"] . "</b>:" . $mensaje["motivo"] . "</li>";
                    }
                    $html .= "</ul>";
                    session()->setFlashdata('alerta', $html);
                }
                return view('cotizaciones/desempleo', ["cotizacion" => $cotizacion]);
            }
            //alerta en caso de no haber encontrado ningun plan
            session()->setFlashdata('alerta', 'No existen planes incendio.');
        }
        return view('cotizaciones/desempleo');
    }

    public function vida()
    {
        if ($this->request->getPost()) {
            $cotizacion = new Cotizacionvida;
            $edad_codeudor = 0;
            $edad_deudor = $cotizacion->calcular_edad($this->request->getPost("deudor"));
            if ($this->request->getPost("codeudor")) {
                $edad_codeudor = $cotizacion->calcular_edad($this->request->getPost("codeudor"));
            }
            //obtener todas las tasas relacionadas con el banco
            //las tasas tiene los requisitos que se compararan para saber si aplican o no
            if ($cotizacion->obtener_tasas("Vida") == true) {
                $cotizacion->establecer_cotizacion(
                    $this->request->getPost("plazo"),
                    $this->request->getPost("suma"),
                    $edad_deudor,
                    $edad_codeudor,
                    $this->request->getPost("deudor"),
                    $this->request->getPost("codeudor")
                );
                $alerta = $cotizacion->obtener_planes();
                if (!empty($alerta)) {
                    $html = "<ul>";
                    foreach ($alerta as $mensaje) {
                        $html .= "<li><b>" . $mensaje["aseguradora"] . "</b>:" . $mensaje["motivo"] . "</li>";
                    }
                    $html .= "</ul>";
                    session()->setFlashdata('alerta', $html);
                }
                return view('cotizaciones/vida', ["cotizacion" => $cotizacion]);
            }
            //alerta en caso de no haber encontrado ningun plan
            session()->setFlashdata('alerta', 'No existen planes vida.');
        }
        return view('cotizaciones/vida');
    }
}
