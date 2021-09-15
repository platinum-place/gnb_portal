<?php

namespace App\Models;

class Cotizacion
{
    public $tipo;
    public $fecha_deudor;
    public $fecha_codeudor;
    public $plazo;
    public $suma;
    public $direccion;
    public $planes = array();
}
