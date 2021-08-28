<?php

namespace App\Controllers;

use App\Libraries\Word;
use App\Libraries\Zoho;

class Casos extends BaseController
{
    protected $zoho;
    protected $libreria;

    function __construct()
    {
        $this->zoho = new Zoho;
    }

    public function accidente($id)
    {
        //libreria para crear archivos word
        $libreria = new Word($this->zoho);

        //obtener datos del caso
        $caso = $this->zoho->getRecord("Cases", $id);

        //crear reporte en word
        $documento = $libreria->reporteaccidente($caso);

        //descargar el reporte
        return $this->response->download($documento, null)->setFileName($caso->getFieldValue("TUA") . ".docx");
    }
}
