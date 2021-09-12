<?php

namespace App\Models;

use App\Libraries\Zoho;

class Modelo extends Zoho
{
    public function lista_modelos($marcaid, $pagina = 1)
    {
        $criterio = "Marca:equals:$marcaid";
        return $this->searchRecordsByCriteria("Modelos", $criterio, $pagina);
    }
}
