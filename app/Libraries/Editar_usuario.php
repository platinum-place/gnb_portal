<?php

namespace App\Libraries;

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;

class Editar_usuario extends Zoho
{
    public function cambiar_clave($pass)
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance("Contacts", session("usuario")->getEntityId()); // To get record instance
        $record->setFieldValue("Contrase_a",  $pass); // This function use to set FieldApiName and value similar to all other FieldApis and Custom field
        $responseIns = $record->update(); // to update the record
        echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
        echo "Status:" . $responseIns->getStatus(); // To get response status
        echo "Message:" . $responseIns->getMessage(); // To get response message
        echo "Code:" . $responseIns->getCode(); // To get status code
        echo "Details:" . json_encode($responseIns->getDetails());
    }
}
