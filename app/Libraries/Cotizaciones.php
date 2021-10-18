<?php

namespace App\Libraries;

use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\crm\crud\ZCRMInventoryLineItem;

class Cotizaciones extends Zoho
{
    public function lista_cotizaciones()
    {
        if (session('puesto') == "Administrador") {
            $criterio = "Account_Name:equals:" . session('cuenta_id');
        } else {
            $criterio = "((Account_Name:equals:" . session('cuenta_id') . ") and (Contact_Name:equals:" . session('usuario_id') . "))";
        }
        return $this->searchRecordsByCriteria("Quotes", $criterio);
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
            $lineItem->setProduct(ZCRMRecord::getInstance("Products", $plan["planid"]));
            $lineItem->setQuantity(1);
            $record->addLineItem($lineItem);
        }

        array_push($records, $record);
        $responseIn = $moduleIns->createRecords($records);

        foreach ($responseIn->getEntityResponses() as $responseIns) {
            //echo "HTTP Status Code:" . $responseIn->getHttpStatusCode();
            //echo "Status:" . $responseIns->getStatus();
            //echo "Message:" . $responseIns->getMessage();
            //echo "Code:" . $responseIns->getCode();
            //echo "Details:" . json_encode($responseIns->getDetails());
            $details = json_decode(json_encode($responseIns->getDetails()), true);
        }

        return $details["id"];
    }

    public function adjuntar_documentos($documentos, $id)
    {
        foreach ($documentos as $documento) {
            if ($documento->isValid() && !$documento->hasMoved()) {
                //subir el archivo al servidor
                $documento->move(WRITEPATH . 'uploads');
                //ruta del archivo subido
                $ruta = WRITEPATH . 'uploads/' . $documento->getClientName();
                //funcion para adjuntar el archivo
                $this->uploadAttachment("Quotes", $id, $ruta);
                //borrar el archivo del servidor local
                unlink($ruta);
            }
        }
    }
}
