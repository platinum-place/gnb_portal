<?php

namespace App\Models;

use App\Zoho;
use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\exception\ZCRMException;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;

class Emision extends Zoho
{
    public function obtener_lista()
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Deals"); // To get module instance
        $criteria = "Account_Name:equals:" . session()->get("usuario")->getFieldValue("Account_Name")->getEntityId(); //criteria to search for
        $param_map = array("page" => 1, "per_page" => 200); // key-value pair containing all the parameters
        try {
            $response = $moduleIns->searchRecordsByCriteria($criteria, $param_map); // To get module records// $criteria to search for  to search for// $param_map-parameters key-value pair - optional
            $records = $response->getData(); // To get response data
            return $records;
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo $ex->getFile(); // To get the file name that throws the Exception
        }
    }

    public function obtener_registro($id)
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Deals"); // To get module instance
        $response = $moduleIns->getRecord($id); // To get module record
        $record = $response->getData(); // To get response data
        try {
            return $record;
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo $ex->getFile(); // To get the file name that throws the Exception
        }
    }

    public function obtener_requisitos($id)
    {
        $requisitos = array();
        $corequisitos = array();
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Products"); // To get module instance
        $response = $moduleIns->getRecord($id); // To get module record
        $record = $response->getData(); // To get response data
        try {
            $requisitos = $record->getFieldValue("Requisitos_deudor");
            $corequisitos = $record->getFieldValue("Requisitos_codeudor");
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo $ex->getFile(); // To get the file name that throws the Exception
        }
        return [$requisitos, $corequisitos];
    }
}
