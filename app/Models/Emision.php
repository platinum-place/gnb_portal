<?php

namespace App\Models;

use App\Libraries\Zoho;

class Emision extends Zoho
{
    public function lista_emisiones()
    {
        $criterio = "Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId();
        return $this->searchRecordsByCriteria("Deals", $criterio);
    }
}
