<?php

namespace App\Models;

use App\Libraries\Zoho;

class Reporte extends Zoho
{
    protected $emisiones;
    protected $desde;
    protected $hasta;

    public function comprobar($tipo)
    {
        $criterio = "((Type:equals:$tipo) and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
        $this->emisiones = $this->searchRecordsByCriteria("Deals", $criterio);
        return $this->emisiones;
    }

    public function establecer($desde, $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }
}
