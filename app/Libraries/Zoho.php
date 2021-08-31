<?php

namespace App\Libraries;

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\exception\ZCRMException;
use zcrmsdk\crm\crud\ZCRMInventoryLineItem;
use zcrmsdk\crm\crud\ZCRMTax;

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
        //$param_map = array("fields" => "Company,Last_Name"); // key-value pair containing all the params - optional
        //$header_map = array("header_name" => "header_value"); // key-value pair containing all the headers - optional
        try {
            $response = $moduleIns->getRecord($record_id); // To get module record
            return $response->getData(); // To get response data
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo "<br>";
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo "<br>";
            echo $ex->getFile(); // To get the file name that throws the Exception
        }
    }

    public function getAttachments($module_api_name, $record_id, $page = 1, $per_page = 200)
    {
        /* For VERSION <=2.0.6 $records = ZCRMRestClient::getInstance()->getRecordInstance("{module_api_name}", "{record_id}"); // To get record instance
        $responseIns = $records->getAttachments(1, 50); // to get the attachments */
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
        echo "HTTP Status Code:" . $fileResponseIns->getHttpStatusCode();
        echo "<br>";
        echo "File Name:" . $fileResponseIns->getFileName();
        $stream = $fileResponseIns->getFileContent();
        var_dump($stream);
        fputs($fp, $stream);
        fclose($fp);

        return $file;
    }

    public function searchRecordsByCriteria($module_api_name, $criteria, $page = 1, $per_page = 200)
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance($module_api_name); // To get module instance
        /* For VERSION <=2.0.6  $page=5;//page number
        $perPage=200;//records per page
        $response = $moduleIns->searchRecordsByCriteria($criteria, $page, $perPage); // To get module records//string $searchWord word to be searched//number $page to get the list of records from the respective pages. Default value for page is 1.//number $perPage To get the list of records available per page. Default value for per page is 200.*/
        $param_map = array("page" => $page, "per_page" => $per_page); // key-value pair containing all the parameters
        try {
            $response = $moduleIns->searchRecordsByCriteria($criteria, $param_map); // To get module records// $criteria to search for  to search for// $param_map-parameters key-value pair - optional
            return $response->getData(); // To get response data
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo "<br>";
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo "<br>";
            echo $ex->getFile(); // To get the file name that throws the Exception
        }
    }

    public function update($module_api_name, $record_id, $cambios)
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance($module_api_name, $record_id); // To get record instance
        /**
         * only for inventory module *
         */

        foreach ($cambios as $campo => $valor) {
            $record->setFieldValue($campo,  $valor); // This function use to set FieldApiName and value similar to all other FieldApis and Custom field
        }

        /*
        $lineItem = ZCRMInventoryLineItem::getInstance("{line_item_id}"); // To get ZCRMInventoryLineItem instance the id of the line item
        $lineItem->setDescription("Product_description"); // To set line item description
        $lineItem->setDiscount(20); // To set line item discount
        $lineItem->setListPrice(3412); // To set line item list price

        $taxInstance1 = ZCRMTax::getInstance("{tax_name}"); // to get the tax instance
        $taxInstance1->setPercentage(20); // to set the tax percentage
        $taxInstance1->setValue(50); // to set the tax value
        $lineItem->addLineTax($taxInstance1); // to add the tax to the line item
        $lineItem->setQuantity(101); // To set product quantity to this line item
        $record->addLineItem($lineItem); // to add the line item to the record of invoice
        */

        /**
         * for price book alone
         * $record->setFieldValue("Pricing_Details", json_decode('[ { "to_range": 5, "discount": 0, "from_range": 1 }, { "to_range": 11, "discount": 1, "from_range": 6 }, { "to_range": 17, "discount": 2, "from_range": 12 }, { "to_range": 23, "discount": 3, "from_range": 18 }, { "to_range": 29, "discount": 4, "from_range": 24 } ]',true));//setting the discount , range of the pricebook record
         * $record->setFieldValue("Pricing_Model","Flat"); //setting the price book model*
         */
        //$trigger = array(); //triggers to include
        //$lar_id = "lar_id"; //lead assignment rule id
        $responseIns = $record->update(); // to update the record

        echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
        echo "<br>";
        echo "Status:" . $responseIns->getStatus(); // To get response status
        echo "<br>";
        echo "Message:" . $responseIns->getMessage(); // To get response message
        echo "<br>";
        echo "Code:" . $responseIns->getCode(); // To get status code
        echo "<br>";
        echo "Details:" . json_encode($responseIns->getDetails());
    }

    public function createRecords($module_api_name, $registro = array())
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance($module_api_name); //to get the instance of the module
        $records = array();
        $record = ZCRMRecord::getInstance($module_api_name, null);  //To get ZCRMRecord instance

        foreach ($registro as $campo => $valor) {
            $record->setFieldValue($campo, $valor); //This function use to set FieldApiName and value similar to all other FieldApis and Custom field
        }


        /** Following methods are being used only by Inventory modules **/
        /*
        $lineItem=ZCRMInventoryLineItem::getInstance(null);  //To get ZCRMInventoryLineItem instance
        $lineItem->setDescription("Product_description");  //To set line item description
        $lineItem ->setDiscount(5);  //To set line item discount
        $lineItem->setListPrice(100);  //To set line item list price
        
        $taxInstance1=ZCRMTax::getInstance("{tax_name}");  //To get ZCRMTax instance
        $taxInstance1->setPercentage(2);  //To set tax percentage
        $taxInstance1->setValue(50);  //To set tax value
        $lineItem->addLineTax($taxInstance1);  //To set line tax to line item
        
        $taxInstance1=ZCRMTax::getInstance("{tax_name}"); //to get the tax instance
        $taxInstance1->setPercentage(12); //to set the tax percentage
        $taxInstance1->setValue(50); //to set the tax value
        $lineItem->addLineTax($taxInstance1); //to add the tax to line item
        
        $lineItem->setProduct(ZCRMRecord::getInstance("{module_api_name}","{record_id}"));  //To set product to line item
        $lineItem->setQuantity(100);  //To set product quantity to this line item
        
        $record->addLineItem($lineItem);   //to add the line item to the record
        */

        array_push($records, $record); // pushing the record to the array.
        //$trigger=array();//triggers to include
        //$lar_id={"lead_assignment_rule_id"};//lead assignment rule id
        $responseIn = $moduleIns->createRecords($records); // updating the records.$trigger,$lar_id are optional
        foreach ($responseIn->getEntityResponses() as $responseIns) {
            echo "HTTP Status Code:" . $responseIn->getHttpStatusCode(); // To get http response code
            echo "<br>";
            echo "Status:" . $responseIns->getStatus(); // To get response status
            echo "<br>";
            echo "Message:" . $responseIns->getMessage(); // To get response message
            echo "<br>";
            echo "Code:" . $responseIns->getCode(); // To get status code
            echo "<br>";
            echo "Details:" . json_encode($responseIns->getDetails());

            $details = json_decode(json_encode($responseIns->getDetails()), true);
        }

        return $details["id"];
    }

    public function uploadAttachment($module_api_name, $record_id, $path)
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance($module_api_name, $record_id); // To get record instance
        $responseIns = $record->uploadAttachment($path); // $filePath - absolute path of the attachment to be uploaded.
        echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
        echo "<br>";
        echo "Status:" . $responseIns->getStatus(); // To get response status
        echo "<br>";
        echo "Message:" . $responseIns->getMessage(); // To get response message
        echo "<br>";
        echo "Code:" . $responseIns->getCode(); // To get status code
        echo "<br>";
        echo "Details:" . $responseIns->getDetails()['id'];
    }
}
