<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Emisiones extends BaseController
{
    protected $zoho;

    function __construct()
    {
        $this->zoho = new Zoho;
    }

    public function emitir($id)
    {
        $cotizacion = $this->zoho->getRecord("Quotes", $id);
        return view("emisiones/emitir", ["titulo" => "Emitir Cotización", "cotizacion" => $cotizacion]);
    }
}
