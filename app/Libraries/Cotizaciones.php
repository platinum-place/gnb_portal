<?php

namespace App\Libraries;

use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\crm\crud\ZCRMInventoryLineItem;

class Cotizaciones extends Zoho
{
    public function lista_marcas()
    {
        $marcas = $this->getRecords("Marcas");
        asort($marcas);
        return $marcas;
    }

    public function lista_modelos($marcaid, $pag)
    {
        $criterio = "Marca:equals:$marcaid";
        $modelos =  $this->searchRecordsByCriteria("Modelos", $criterio, $pag);
        return $modelos;
    }

    public function lista_planes($tipo)
    {
        $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Product_Category:equals:$tipo))";
        return $this->searchRecordsByCriteria("Products", $criterio);
    }

    //crea el registro en el crm, al ser un registro con una tabla de productos es necesario...
    //funciones del sdk relacionadas al inventario y impuestos
    public function crear_cotizacion($cotizacion, array $planes)
    {
        //inicializar el api
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Quotes");
        //inicializar el registro en blanco
        $records = array();
        $record = ZCRMRecord::getInstance("Quotes", null);
        //recorre los datos para crear un registro con los nombres de los campos a los valores que correspondan
        foreach ($cotizacion as $campo => $valor) {
            $record->setFieldValue($campo, $valor);
        }
        //recorre los planes/productos al registro
        foreach ($planes as $plan) {
            $lineItem = ZCRMInventoryLineItem::getInstance(null);
            $lineItem->setListPrice($plan["total"]);
            $lineItem->setDescription($plan["aseguradora"]);
            $lineItem->setProduct(ZCRMRecord::getInstance("Products", $plan["planid"]));
            $lineItem->setQuantity(1);
            $record->addLineItem($lineItem);
        }
        array_push($records, $record);
        $responseIn = $moduleIns->createRecords($records);
        foreach ($responseIn->getEntityResponses() as $responseIns) {
            echo "HTTP Status Code:" . $responseIn->getHttpStatusCode();
            echo "Status:" . $responseIns->getStatus();
            echo "Message:" . $responseIns->getMessage();
            echo "Code:" . $responseIns->getCode();
            echo "Details:" . json_encode($responseIns->getDetails());
            $details = json_decode(json_encode($responseIns->getDetails()), true);
        }
        return $details["id"];
    }

    public function calcular_prima_incendio($tasa, $suma)
    {
        return ($suma / 100) * ($tasa->getFieldValue('Name') / 100);
    }

    public function calcular_prima_desempleo($tasa, $suma, $cuota)
    {
        $vida = ($suma / 1000) * ($tasa->getFieldValue('Name') / 100);
        $desempleo = ($cuota / 1000) * $tasa->getFieldValue('Desempleo');
        return $vida + $desempleo;
    }
}
