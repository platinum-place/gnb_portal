<?php

namespace App\Libraries;

class Emisiones extends Zoho
{
    public $lista = array();
    public $polizas = 0;
    public $vencidas = 0;
    public $evaluacion = 0;

    public function lista()
    {
        if (session('usuario')->getFieldValue("Title") == "Administrador") {
            $criterio = "Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId();
        } else {
            $criterio = "((Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . ") and (Contact_Name:equals:" . session('usuario')->getEntityId() . "))";
        }
        return $this->searchRecordsByCriteria("Deals", $criterio);
    }

    public function resumen()
    {
        $emisiones = $this->lista();
        foreach ((array)$emisiones as $emision) {
            if (date("Y-m", strtotime($emision->getFieldValue('Fecha_de_inicio'))) == date("Y-m")) {
                $this->lista[] = $emision->getFieldValue('Aseguradora');
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
