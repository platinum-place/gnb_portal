<?php

namespace App\Models;

use App\Libraries\Zoho;

class Emision extends Zoho
{
    protected $aseguradoraid;
    protected $prima;
    protected $coberturaid;
    protected $emisionid;

    public function establecer_aseguradora($aseguradora, $prima)
    {
        $this->aseguradora = $aseguradora;
        $this->prima = $prima;
    }

    public function adjuntar_documento($ruta)
    {
        //adjuntar documento al registro creado
        $this->zoho->uploadAttachment("Deals", $this->emisionid, $ruta);
        //eliminar documento subido al servidor
        unlink($ruta);
    }
}
