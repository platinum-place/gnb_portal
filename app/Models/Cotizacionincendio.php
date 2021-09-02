<?php

namespace App\Models;

class Cotizacionincendio extends Cotizacion
{
    public $plazo;
    public $propiedad;
    public $prestamo;
    public $construccion;
    public $riesgo;

    public function establecer_cotizacion(
        $plazo,
        $propiedad,
        $prestamo,
        $construccion,
        $riesgo
    ) {
        $this->plazo = $plazo;
        $this->propiedad = $propiedad;
        $this->prestamo = $prestamo;
        $this->construccion = $construccion;
        $this->riesgo = $riesgo;
    }

    public function obtener_planes($propiedad)
    {
        //se comparan los requisitos
        //crear un plan solo con el valor de la prima, ya que no require coberturas
        foreach ($this->tasas as $tasa) {
            $prima = ($propiedad / 100) * ($tasa->getFieldValue('Name') / 100);
            $isc = $prima * 0.16;
            $total = $prima + $isc;
            $this->planes[] = [
                "nombre" => $tasa->getFieldValue("Aseguradora")->getLookupLabel(),
                "id" => $tasa->getFieldValue("Aseguradora")->getEntityId(),
                "neta" => $prima,
                "isc" => $isc,
                "total" => $total
            ];
        }
    }

    public function plantilla()
    {
        $plantilla = [
            "plazo" => $this->plazo,
            "propiedad" => $this->propiedad,
            "prestamo" => $this->prestamo,
            "construccion" => $this->construccion,
            "riesgo" => $this->riesgo,
            "planes" => $this->planes
        ];
        return json_encode($plantilla);
    }
}
