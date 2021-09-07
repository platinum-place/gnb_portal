<?php

namespace App\Models;

use App\Zoho;
use zcrmsdk\crm\exception\ZCRMException;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;

class Sesion extends Zoho
{
    protected $correo;
    protected $pass;

    public function establecer($correo, $pass)
    {
        $this->correo = $correo;
        $this->pass = $pass;
    }

    //buscar el todos los usuarios con el correo y contraseña sean iguales
    //los correos son campos unicos en el crm
    //en caso de error, estas lineas interumpen la carga del codigo,
    //pero en caso de no encontrar resultados, es correcto que el codigo continue.
    //Para evitarlo, es necesario entrar la seguientes dos lineas dentro del try para que el error se capture y
    //el codigo continue
    public function validar_en_crm()
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Contacts"); // To get module instance
        $criteria = "((Email:equals:" . $this->correo . ") and (Contrase_a:equals:" . $this->pass . "))"; //criteria to search for
        $param_map = array("page" => 1, "per_page" => 1); // key-value pair containing all the parameters
        try {
            $response = $moduleIns->searchRecordsByCriteria($criteria, $param_map); // To get module records// $criteria to search for  to search for// $param_map-parameters key-value pair - optional
            $records = $response->getData(); // To get response data
            foreach ($records as $record) {
                return $record;
            }
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo $ex->getFile(); // To get the file name that throws the Exception
        }
    }

    public function cambiar_contrasena()
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance("Contacts", session()->get("usuario")->getEntityId()); // To get record instance
        $record->setFieldValue("Contrase_a", $this->pass); // This function use to set FieldApiName and value similar to all other FieldApis and Custom field
        $responseIns = $record->update(); // to update the record
        echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
        echo "Status:" . $responseIns->getStatus(); // To get response status
        echo "Message:" . $responseIns->getMessage(); // To get response message
        echo "Code:" . $responseIns->getCode(); // To get status code
        echo "Details:" . json_encode($responseIns->getDetails());
    }
}
