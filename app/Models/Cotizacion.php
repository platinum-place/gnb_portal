<?php

namespace App\Models;

use App\Libraries\Zoho;

class Cotizacion extends Zoho
{
    protected $tasas;
    public $planes = array();

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

    public function obtener_tasas($tipo)
    {
        //obtener todas las tasas relacionadas con el banco
        //las tasas tiene los requisitos que se compararan para saber si aplican o no
        $criterio = "((Intermediario:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Tipo:equals:$tipo))";
        $this->tasas = $this->searchRecordsByCriteria("Tasas", $criterio);
        if (!empty($this->tasas)) {
            return true;
        }
    }
}
