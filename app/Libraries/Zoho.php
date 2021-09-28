<?php

namespace App\Libraries;

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\exception\ZCRMException;

class Zoho
{
    function __construct()
    {
        ZCRMRestClient::initialize([
            "client_id" => "1000.7FJQ4A2KDH9S2IJWDYL13HATQFMA2H",
            "client_secret" => "c3f1d0589803f294a7c5b27e3968ae1658927da9d7",
            "currentUserEmail" => "tecnologia@gruponobe.com",
            "redirect_uri" => base_url(),
            "token_persistence_path" => ROOTPATH
        ]);
    }

    //total access scope
    //aaaserver.profile.READ,ZohoCRM.users.ALL,ZohoCRM.modules.ALL,ZohoCRM.settings.all,ZohoCRM.settings.fields.ALL
    public function generateTokens($grant_token)
    {
        $oAuthClient = ZohoOAuth::getClientInstance();
        $oAuthClient->generateAccessToken($grant_token);
    }

    public function getRecord($module_api_name, $record_id)
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance($module_api_name); // To get module instance
        try {
            $response = $moduleIns->getRecord($record_id); // To get module record
            return $response->getData(); // To get response data
        } catch (ZCRMException $ex) {
            //echo $ex->getMessage(); // To get ZCRMException error message
            //echo $ex->getExceptionCode(); // To get ZCRMException error code
            //echo $ex->getFile(); // To get the file name that throws the Exception
        }
    }

    public function getAttachments($module_api_name, $record_id, $page = 1, $per_page = 200)
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance($module_api_name, $record_id); // To get record instance
        $param_map = array("page" => $page, "per_page" => $per_page); // key-value pair containing all the parameters - optional
        $responseIns = $record->getAttachments($param_map); // to get the attachments
        return $responseIns->getData(); // to get the attachments in form of ZCRMAttachment instance array
    }

    public function downloadAttachment($module_api_name, $record_id, $attachment_id, $filePath)
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance($module_api_name, $record_id); // To get record instance
        $fileResponseIns = $record->downloadAttachment($attachment_id);
        $file = $filePath . "/" . $fileResponseIns->getFileName();
        $fp = fopen($file, "w");
        //echo "HTTP Status Code:" . $fileResponseIns->getHttpStatusCode();
        //echo "File Name:" . $fileResponseIns->getFileName();
        $stream = $fileResponseIns->getFileContent();
        //var_dump($stream);
        fputs($fp, $stream);
        fclose($fp);
        return $file;
    }

    public function searchRecordsByCriteria($module_api_name, $criteria, $page = 1, $per_page = 200)
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance($module_api_name); // To get module instance
        $param_map = array("page" => $page, "per_page" => $per_page); // key-value pair containing all the parameters
        try {
            $response = $moduleIns->searchRecordsByCriteria($criteria, $param_map); // To get module records// $criteria to search for  to search for// $param_map-parameters key-value pair - optional
            return $response->getData(); // To get response data
        } catch (ZCRMException $ex) {
            //echo $ex->getMessage(); // To get ZCRMException error message
            //echo $ex->getExceptionCode(); // To get ZCRMException error code
            //echo $ex->getFile(); // To get the file name that throws the Exception
        }
    }

    public function update($module_api_name, $record_id, $cambios)
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance($module_api_name, $record_id); // To get record instance
        foreach ($cambios as $campo => $valor) {
            $record->setFieldValue($campo,  $valor); // This function use to set FieldApiName and value similar to all other FieldApis and Custom field
        }
        $responseIns = $record->update(); // to update the record
        //echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
        //echo "Status:" . $responseIns->getStatus(); // To get response status
        //echo "Message:" . $responseIns->getMessage(); // To get response message
        //echo "Code:" . $responseIns->getCode(); // To get status code
        //echo "Details:" . json_encode($responseIns->getDetails());
    }

    public function createRecords($module_api_name, $registro = array())
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance($module_api_name); //to get the instance of the module
        $records = array();
        $record = ZCRMRecord::getInstance($module_api_name, null);  //To get ZCRMRecord instance
        foreach ($registro as $campo => $valor) {
            $record->setFieldValue($campo, $valor); //This function use to set FieldApiName and value similar to all other FieldApis and Custom field
        }
        array_push($records, $record); // pushing the record to the array.
        $responseIn = $moduleIns->createRecords($records); // updating the records.$trigger,$lar_id are optional
        foreach ($responseIn->getEntityResponses() as $responseIns) {
            //echo "HTTP Status Code:" . $responseIn->getHttpStatusCode(); // To get http response code
            //echo "Status:" . $responseIns->getStatus(); // To get response status
            //echo "Message:" . $responseIns->getMessage(); // To get response message
            //echo "Code:" . $responseIns->getCode(); // To get status code
            //echo "Details:" . json_encode($responseIns->getDetails());
            $details = json_decode(json_encode($responseIns->getDetails()), true);
        }
        return $details["id"];
    }

    public function uploadAttachment($module_api_name, $record_id, $path)
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance($module_api_name, $record_id); // To get record instance
        $responseIns = $record->uploadAttachment($path); // $filePath - absolute path of the attachment to be uploaded.
        //echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
        //echo "Status:" . $responseIns->getStatus(); // To get response status
        //echo "Message:" . $responseIns->getMessage(); // To get response message
        //echo "Code:" . $responseIns->getCode(); // To get status code
        //echo "Details:" . $responseIns->getDetails()['id'];
    }

    public function getRecords($module_api_name, $page = 1, $per_page = 200)
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance($module_api_name);
        $param_map = array("page" => $page, "per_page" => $per_page);
        try {
            $response = $moduleIns->getRecords($param_map);
            return $response->getData();
        } catch (ZCRMException $ex) {
            //echo $ex->getMessage(); // To get ZCRMException error message
            //echo $ex->getExceptionCode(); // To get ZCRMException error code
            //echo $ex->getFile(); // To get the file name that throws the Exception
        }
    }

    public function delete($module_api_name, $record_id)
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance($module_api_name, $record_id); // To get record instance
        $responseIns = $record->delete();
        //echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
        //echo "Status:" . $responseIns->getStatus(); // To get response status
        //echo "Message:" . $responseIns->getMessage(); // To get response message
        //echo "Code:" . $responseIns->getCode(); // To get status code
        //echo "Details:" . json_encode($responseIns->getDetails());
    }
}
