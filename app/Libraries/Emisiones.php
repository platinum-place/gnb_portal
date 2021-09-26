<?php

namespace App\Libraries;

use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\crm\crud\ZCRMInventoryLineItem;

class Emisiones extends Zoho
{
    public $lista = array();
    public $polizas = 0;
    public $vencidas = 0;
    public $pendiente = 0;

    public function lista($pag = 1, $cantidad = 200)
    {
        //en caso de que el usuario sea admin
        if (session('usuario')->getFieldValue("Title") == "Administrador") {
            $criterio = "Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId();
        } else {
            $criterio = "((Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . ") and (Contact_Name:equals:" . session('usuario')->getEntityId() . "))";
        }

        //retornar todas las emisiones
        return $this->searchRecordsByCriteria("Sales_Orders", $criterio, $pag, $cantidad);
    }

    public function resumen()
    {
        //obtener las emisiones
        $emisiones = $this->lista();

        foreach ((array)$emisiones as $emision) {
            //filtrar por  mes y año actual
            if (date("Y-m", strtotime($emision->getCreatedTime())) == date("Y-m")) {
                foreach ($emision->getLineItems() as $lineItem) {
                    //contador del nombre de las aseguradoras
                    $this->lista[] =  $lineItem->getDescription();
                }

                //contador en general
                $this->polizas++;

                //contador para las emisiones que aun no ha sido revisadas
                if ($emision->getFieldValue('Status') == "Pendiente") {
                    $this->pendiente++;
                }
            }

            //contador para las emisiones que vencen en el mes y año actual
            if (date("Y-m", strtotime($emision->getFieldValue('Due_Date'))) == date("Y-m")) {
                $this->vencidas++;
            }
        }
    }

    public function crear_emision($emision, $plan)
    {
        //inicializar el api
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Sales_Orders");

        //inicializar el registro en blanco
        $records = array();
        $record = ZCRMRecord::getInstance("Sales_Orders", null);

        //recorre los datos para crear un registro con los nombres de los campos a los valores que correspondan
        foreach ($emision as $campo => $valor) {
            $record->setFieldValue($campo, $valor);
        }

        //recorre los planes/productos al registro
        $lineItem = ZCRMInventoryLineItem::getInstance(null);
        $lineItem->setDescription($plan["aseguradora"]);
        $lineItem->setProduct(ZCRMRecord::getInstance("Products", $plan["planid"]));
        $lineItem->setQuantity(1);
        $record->addLineItem($lineItem);

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
}
