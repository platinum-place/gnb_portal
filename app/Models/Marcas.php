<?php

namespace App\Models;

use App\Zoho;
use zcrmsdk\crm\exception\ZCRMException;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;

class Marcas extends Zoho
{
    public function obtener_lista()
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Marcas"); // To get module instance
        /* For VERSION <=2.0.6 $response = $moduleIns->getRecords(null, null, null, 1, 100, null); // to get the records(parameter - custom_view_id,field_api_name,sort_order,customHeaders is optional and can be given null if not required), customheader is a keyvalue pair for eg("if-modified-since"=>"2008-09-15T15:53:00")*/
        $param_map = array("page" => 1, "per_page" => 200); // key-value pair containing all the parameters - optional
        //$header_map = array("if-modified-since" => "2019-11-15T15:26:49+05:30"); // key-value pair containing all the headers - optional
        $response = $moduleIns->getRecords($param_map); // to get the records($param_map - parameter map,$header_map - header map
        $records = $response->getData(); // To get response data
        $marcas = array();
        try {
            foreach ($records as $record) {
                $marcas[$record->getEntityId()] = $record->getFieldValue("Name");
            }
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo $ex->getFile(); // To get the file name that throws the Exception
        }
        return $marcas;
    }
}
