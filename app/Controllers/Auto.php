<?php

namespace App\Controllers;

use App\Models\Cotizacion;

class Auto extends BaseController
{
    protected $cotizacion;

    function __construct()
    {
        $this->cotizacion = new Cotizacion;
    }

    public function mostrarModelos()
    {
        $pag = 1;
        do {
            $lista_modelos = $this->modelo->lista_modelos($this->request->getPost("marcaid"), $pag);
            if ($lista_modelos) {
                $pag++;
                asort($lista_modelos);
                foreach ($lista_modelos as $modelo) {
                    echo '<option value="' . $modelo->getEntityId() . "," . $modelo->getFieldValue('Tipo') . '">' . strtoupper($modelo->getFieldValue('Name')) . '</option>';
                }
            } else {
                $pag = 0;
            }
        } while ($pag > 0);
    }

    public function cotizar()
    {

    }
}
