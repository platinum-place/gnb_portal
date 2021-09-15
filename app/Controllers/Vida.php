<?php

namespace App\Controllers;

use App\Libraries\Zoho;
use App\Models\Cotizacion;

class Vida extends BaseController
{
    protected $zoho;

    function __construct()
    {
        $this->zoho = new Zoho;
    }

    protected function calcular_edad($fecha)
    {
        list($ano, $mes, $dia) = explode("-", $fecha);
        $ano_diferencia  = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia   = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
            $ano_diferencia--;
        return $ano_diferencia;
    }

    protected function verificar_limites_deudor_codeudor($tasa, $plazo, $suma, $edad_deudor, $edad_codeudor = 0)
    {
        //verificar limite de plazo
        if ($plazo > $tasa->getFieldValue('Plazo')) {
            return "El plazo es mayor al limite establecido.";
        }
        //verificar limite suma
        if ($suma > $tasa->getFieldValue('Suma_asegurada')) {
            return "La suma asegurada es mayor al limite establecido.";
        }
        //verificar limite edad minima
        if ($edad_deudor < $tasa->getFieldValue('Edad_min')) {
            return "La edad del deudor es menor al limite permitido.";
        }
        //verificar limite edad maximo
        if ($edad_deudor > $tasa->getFieldValue('Edad_max')) {
            return "La edad del deudor es mayor al limite permitido.";
        }
        //verificar limite edad minima del codeudor
        if ($edad_codeudor > 0 and $edad_codeudor < $tasa->getFieldValue('Edad_min')) {
            return "La edad del codeudor es menor al limite permitido.";
        }
        //verificar limite edad maximo del codeudor
        if ($edad_codeudor > 0 and $edad_codeudor > $tasa->getFieldValue('Edad_max')) {
            return "La edad del codeudor es mayor al limite permitido.";
        }
    }

    protected function calcular_prima($tasa, $suma, $deudor, $codeudor)
    {
        $tasa_deudor = $tasa->getFieldValue('Name') / 100;
        $deudor = ($suma / 1000) * $tasa_deudor;
        if (!empty($edad_codeudor)) {
            $tasa_codeudor = $tasa->getFieldValue('Codeudor') / 100;
            $codeudor = ($suma / 1000) * ($tasa_codeudor - $tasa_deudor);
        }
        return $deudor + $codeudor;
    }

    public function cotizar()
    {
        //instanciar el modelo de cotizacion y solo usar los valores relacionados
        $cotizacion = new Cotizacion;
        $cotizacion->tipo = "Vida";
        $cotizacion->plan = "Vida";
        $cotizacion->fecha_deudor = $this->request->getPost("deudor");
        $cotizacion->fecha_codeudor = $this->request->getPost("codeudor");
        $cotizacion->plazo = $this->request->getPost("plazo");
        $cotizacion->suma = $this->request->getPost("suma");
        //convertir la edad en fecha a valor numerico
        $edad_deudor = $this->calcular_edad($cotizacion->fecha_deudor);
        //inicializar valores en blanco
        $edad_codeudor = 0;
        if ($this->request->getPost("codeudor")) {
            $edad_codeudor = $this->calcular_edad($cotizacion->fecha_codeudor);
        }
        //para mas rapides, y como las tasas son menores en cantidad, invocamos todas las tasas del intermediario
        $criterio = "((Intermediario:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Tipo:equals:Vida))";
        $tasas = $this->zoho->searchRecordsByCriteria("Tasas", $criterio);
        foreach ($tasas as $tasa) {
            //inicializar valores en blanco
            $prima = 0;
            $comentario = "";
            //comprobar limites o restricciones que debe cumplir para cotizar
            $comentario = $this->verificar_limites_deudor_codeudor(
                $tasa,
                $this->request->getPost("plazo"),
                $this->request->getPost("suma"),
                $edad_deudor,
                $edad_codeudor
            );
            //en caso de no haber comentarrios, que representan las alertas, proceder a realizar el calculo
            if (empty($comentario)) {
                $prima = $this->calcular_prima(
                    $tasa,
                    $this->request->getPost("suma"),
                    $edad_deudor,
                    $edad_codeudor
                );
            }
            //lista con los resultados de cada calculo
            $cotizacion->planes[] = [
                "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                "aseguradoraid" => $tasa->getFieldValue('Aseguradora')->getEntityId(),
                "planid" => $tasa->getFieldValue('Plan')->getEntityId(),
                "prima" => $prima,
                "neta" => $prima * 0.16,
                "total" => $prima * 1.16,
                "suma" => $this->request->getPost("suma"),
                "comentario" => $comentario
            ];
        }
        //valores de la vista, en caso de querer hacer otra cotizacion
        $marcas = $this->zoho->getRecords("Marcas");
        asort($marcas);
        return view("cotizaciones/index", ["titulo" => "Cotizar", "marcas" => $marcas, "cotizacion" => $cotizacion]);
    }
}
