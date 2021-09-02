<?php

namespace App\Models;

class cotizaciondesempleo extends Cotizacion
{
    public $plazo;
    public $suma;
    public $cuota;
    public $edad;
    public $fecha;

    public function establecer_cotizacion($plazo, $suma, $cuota, $edad, $fecha)
    {
        $this->plazo = $plazo;
        $this->suma = $suma;
        $this->cuota = $cuota;
        $this->edad = $edad;
        $this->fecha = $fecha;
    }

    public function obtener_planes($plazo, $suma, $edad, $cuota)
    {
        $alerta = array();
        //se comparan los requisitos
        //crear un plan solo con el valor de la prima, ya que no require coberturas
        foreach ($this->tasas as $tasa) {
            if ($plazo > $tasa->getFieldValue('Plazo')) {
                $alerta[] = array(
                    "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                    "motivo" => "El plazo es mayor al limite establecido."
                );
            }
            if ($suma > $tasa->getFieldValue('Suma_asegurada')) {
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
                $vida = ($suma / 1000) * ($tasa->getFieldValue('Name') / 100);
                $desempleo = ($cuota / 1000) * $tasa->getFieldValue('Desempleo');
            } else {
                $vida = 0;
                $desempleo = 0;
            }
            $prima =  $vida + $desempleo;
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
            "cuota" => $this->cuota,
            "edad" => $this->edad,
            "fecha" => $this->fecha,
            "planes" => $this->planes
        ];
        return json_encode($plantilla);
    }
}
