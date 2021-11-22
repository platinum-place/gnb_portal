<?php

namespace App\Models;

class Cotizacion
{
    public $plan;
    public $suma;
    public $planes = array();
    public $plazo;
    public $fecha_deudor;
    public $direccion;

    public function calcular_edad($fecha)
    {
        list($ano, $mes, $dia) = explode("-", $fecha);
        $ano_diferencia  = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia   = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
            $ano_diferencia--;
        return $ano_diferencia;
    }
}
