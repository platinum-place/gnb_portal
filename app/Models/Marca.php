<?php

namespace App\Models;

use App\Libraries\Zoho;

class Marca extends Zoho
{
    public function lista_marcas()
    {
        return $this->getRecords("Marcas");
    }
}
