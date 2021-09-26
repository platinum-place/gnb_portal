<?php

namespace App\Models;

use App\Libraries\Zoho;

class Reporte
{
    //es un array que albergara objetos del api del tipo ZCRMRecord que a su vez tiene mas objetos del api del mismo tipo
    public $emisiones = array();
    public $desde;
    public $hasta;
    public $tipo;

    //verifica si existen reportes del tipo definido
    public function emisiones_existentes(Zoho $libreria, $pag = 1)
    {
        //en caso de que el usuario sea admin
        if (session('usuario')->getFieldValue("Title") == "Administrador") {
            $criteria = "((Tipo:equals:" . $this->tipo . ") and (Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . "))";
        } else {
            $criteria = "((Tipo:equals:" . $this->tipo . ") and (Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . ") and (Contact_Name:equals:" . session('usuario')->getEntityId() . "))";
        }

        //genera emisiones
        if ($emisiones = $libreria->searchRecordsByCriteria("Sales_Orders", $criteria, $pag)) {
            //actumula las emisiones debajo de las existentes
            $this->emisiones = array_merge($this->emisiones, $emisiones);
        }
    }
}
