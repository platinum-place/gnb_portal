<?php

namespace App\Models;

use App\Libraries\Zoho;

class Emision extends Zoho
{
    protected $aseguradoraid;
    protected $prima;
    protected $coberturaid;
    protected $emisionid;

    public function establecer_aseguradora($aseguradoraid, $prima)
    {
        $this->aseguradoraid = $aseguradoraid;
        $this->prima = $prima;
    }

    public function adjuntar_documento($ruta)
    {
        //adjuntar documento al registro creado
        $this->uploadAttachment("Deals", $this->emisionid, $ruta);
        //eliminar documento subido al servidor
        unlink($ruta);
    }

    public function obtener_coberturas($tipo)
    {
        //planes o coberturas de plan elegido, solo es un registro,
        //debe haber un registro, si no, debe ser posible avanzar
        //estan ubicados en el modulo de productos
        $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Vendor_Name:equals:" . $this->aseguradoraid . ") and (Product_Category:equals:$tipo))";
        $coberturas = $this->searchRecordsByCriteria("Products", $criterio);
        foreach ($coberturas as $cobertura) {
            $this->coberturaid = $cobertura->getEntityId();
        }
    }
}
