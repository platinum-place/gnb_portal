<?php

namespace App\Models;

class Reporte
{
    //es un array que albergara objetos del api del tipo ZCRMRecord que a su vez tiene mas objetos del api del mismo tipo
    public $emisiones = array();
    public $desde;
    public $hasta;
    public $tipo;
}
