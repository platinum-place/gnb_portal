<?php
namespace App\Models;

use App\Libraries\Zoho;

class Reporte
{

    public $emisiones;

    function listar_emisiones()
    {
        $libreria = new Zoho();

        if (session('admin') == true) {
            $criterio = "((Account_Name:equals:" . session('cuenta_id') . ") and (Quote_Stage:starts_with:E))";
        } else {
            $criterio = "((Account_Name:equals:" . session('cuenta_id') . ") and (Contact_Name:equals:" . session('usuario_id') . ") and (Quote_Stage:starts_with:E))";
        }

        $this->emisiones = $libreria->searchRecordsByCriteria("Quotes", $criterio);
    }
}

