<?php

namespace App\Libraries;

class Resumen_emisiones extends Zoho
{

    public $lista = array();
    public $polizas = 0;
    public $vencidas = 0;
    public $evaluacion = 0;

    function __construct()
    {
        parent::__construct();
        $criterio = "Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId();
        $emisiones = $this->searchRecordsByCriteria("Deals", $criterio);
        foreach ($emisiones as $emision) {
            if (date("Y-m", strtotime($emision->getCreatedTime())) == date("Y-m")) {
                $this->lista[] = $emision->getFieldValue('Aseguradora')->getLookupLabel();
                $this->polizas++;
                if ($emision->getFieldValue('Stage') == "Proceso de validación") {
                    $this->evaluacion++;
                }
            }
            if (date("Y-m", strtotime($emision->getFieldValue('Closing_Date'))) == date("Y-m")) {
                $this->vencidas++;
            }
        }
    }
}
