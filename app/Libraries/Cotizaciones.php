<?php

namespace App\Libraries;

class Cotizaciones
{
    protected $zoho;

    function __construct(Zoho $zoho)
    {
        $this->zoho = $zoho;
    }

    public function calcularplanesincendio($propiedad)
    {
        $planes = array();
        $criterio = "((Intermediario:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Tipo:equals:Incendio))";
        $tasas = $this->zoho->searchRecordsByCriteria("Tasas", $criterio);

        foreach ($tasas as $tasa) {
            $prima = ($propiedad / 100) * ($tasa->getFieldValue('Name') / 100);
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

        return $planes;
    }
}
