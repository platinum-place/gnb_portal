<?php

namespace App\Models;

class Cotizacionvida extends Cotizacion
{
    public $plazo;
    public $suma;
    public $edad_deudor;
    public $edad_codeudor;
    public $fecha_deudor;
    public $fecha_codeudor;

    public function establecer_cotizacion($plazo, $suma, $edad_deudor, $edad_codeudor, $fecha_deudor, $fecha_codeudor)
    {
        $this->plazo = $plazo;
        $this->suma = $suma;
        $this->edad_deudor = $edad_deudor;
        $this->edad_codeudor = $edad_codeudor;
        $this->fecha_deudor = $fecha_deudor;
        $this->fecha_codeudor = $fecha_codeudor;
    }

    public function obtener_planes()
    {
        $alerta = array();
        //se comparan los requisitos
        //crear un plan solo con el valor de la prima, ya que no require coberturas
        foreach ($this->tasas as $tasa) {
            if ($this->plazo > $tasa->getFieldValue('Plazo')) {
                $alerta[] = array(
                    "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                    "motivo" => "El plazo es mayor al limite establecido."
                );
            }
            if ($this->suma > $tasa->getFieldValue('Suma_asegurada')) {
                $alerta[] = array(
                    "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                    "motivo" => "La suma asegurada es mayor al limite establecido."
                );
            }
            if ($this->edad_deudor < $tasa->getFieldValue('Edad_min')) {
                $alerta[] = array(
                    "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                    "motivo" => "La edad del deudor es menor al limite permitido."
                );
            }
            if ($this->edad_deudor > $tasa->getFieldValue('Edad_max')) {
                $alerta[] = array(
                    "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                    "motivo" => "La edad del deudor es mayor al limite permitido."
                );
            }
            if ($this->edad_codeudor > 0 and $this->edad_codeudor < $tasa->getFieldValue('Edad_min')) {
                $alerta[] = array(
                    "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                    "motivo" => "La edad del codeudor es menor al limite permitido."
                );
            }
            if ($this->edad_codeudor > 0 and $this->edad_codeudor > $tasa->getFieldValue('Edad_max')) {
                $alerta[] = array(
                    "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                    "motivo" => "La edad del codeudor es mayor al limite permitido."
                );
            }
            $deudor = 0;
            $codeudor = 0;
            if (empty($alerta)) {
                $tasa_deudor = $tasa->getFieldValue('Name') / 100;
                $deudor = ($this->suma / 1000) * $tasa_deudor;
                if (!empty($this->edad_codeudor)) {
                    $tasa_codeudor = $tasa->getFieldValue('Codeudor') / 100;
                    $codeudor = ($this->suma / 1000) * ($tasa_codeudor - $tasa_deudor);
                }
            }
            $prima = $deudor + $codeudor;
            $isc = $prima * 0.16;
            $total = $prima + $isc;
            $this->planes[] = [
                "nombre" => $tasa->getFieldValue("Aseguradora")->getLookupLabel(),
                "id" => $tasa->getFieldValue("Aseguradora")->getEntityId(),
                "neta" => $prima,
                "isc" => $isc,
                "total" => $total,
            ];
        }
        return $alerta;
    }

    public function plantilla()
    {
        $plantilla = [
            "plazo" => $this->plazo,
            "suma" => $this->suma,
            "edad_deudor" => $this->edad_deudor,
            "edad_codeudor" => $this->edad_codeudor,
            "fecha_deudor" => $this->fecha_deudor,
            "fecha_codeudor" => $this->fecha_codeudor,
            "planes" => $this->planes
        ];
        return json_encode($plantilla);
    }
}
