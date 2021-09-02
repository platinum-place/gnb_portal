<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Plantillas extends BaseController
{
    public function mostrar($id)
    {
        $zoho = new Zoho;
        $detalles = $zoho->getRecord("Deals", $id);
        switch ($detalles->getFieldValue("Type")) {
            case 'Incendio':
                return view('plantillas/emisionincendio', ["detalles" => $detalles]);
                break;
        }
    }
}
