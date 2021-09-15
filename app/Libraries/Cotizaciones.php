<?php

namespace App\Libraries;

class Cotizaciones extends Zoho
{ 
 

    public function lista_recargos($marcaid, $aseguradoraid)
    {
        $criterio = "((Marca:equals:$marcaid) and (Aseguradora:equals:$aseguradoraid))";
        return $this->searchRecordsByCriteria("Recargos", $criterio, 1, 200);
    }

    public function calcular_prima_incendio($tasa, $suma)
    {
        return ($suma / 100) * ($tasa->getFieldValue('Name') / 100);
    }

    public function calcular_prima_desempleo($tasa, $suma, $cuota)
    {
        $vida = ($suma / 1000) * ($tasa->getFieldValue('Name') / 100);
        $desempleo = ($cuota / 1000) * $tasa->getFieldValue('Desempleo');
        return $vida + $desempleo;
    }

 
  

 

    public function lista_planes($tipo)
    {
        $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Product_Category:equals:$tipo))";
        return $this->searchRecordsByCriteria("Products", $criterio);
    }
}
