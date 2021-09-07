<?php

namespace App\Models;

use App\Zoho;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;

class Adjunto extends Zoho
{
    protected function lista_adjuntos_coberturas($record_id)
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance("Products", $record_id); // To get record instance
        $param_map = array("page" => "1", "per_page" => "200"); // key-value pair containing all the parameters - optional
        $responseIns = $record->getAttachments($param_map); // to get the attachments
        return $responseIns->getData(); // to get the attachments in form of ZCRMAttachment instance array
    }

    protected function descargar_adjunto_coberturas_local($record_id, $attachment_id)
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance("Products", $record_id); // To get record instance
        $fileResponseIns = $record->downloadAttachment($attachment_id);
        $filePath = storage_path("app/public");
        $fp = fopen($filePath . "/" . $fileResponseIns->getFileName(), "w"); // $filePath - absolute path where downloaded file has to be stored.
        //echo "HTTP Status Code:" . $fileResponseIns->getHttpStatusCode();
        //echo "File Name:" . $fileResponseIns->getFileName();
        $stream = $fileResponseIns->getFileContent();
        var_dump($stream);
        fputs($fp, $stream);
        fclose($fp);
        return $filePath . "/" . $fileResponseIns->getFileName();
    }

    public function descargar_adjunto_coberturas($id)
    {
        $lista_adjuntos = $this->lista_adjuntos_coberturas($id);
        foreach ($lista_adjuntos as $adjunto) {
            //descargar adjunto al servidor
            $ruta = $this->descargar_adjunto_coberturas_local($id, $adjunto->getId());
            return $ruta;
        }
    }

    protected function adjuntar_documento($path, $record_id)
    {
        $record = ZCRMRestClient::getInstance()->getRecordInstance("Deals", $record_id); // To get record instance
        $responseIns = $record->uploadAttachment($path); // $filePath - absolute path of the attachment to be uploaded.
        echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
        echo "Status:" . $responseIns->getStatus(); // To get response status
        echo "Message:" . $responseIns->getMessage(); // To get response message
        echo "Code:" . $responseIns->getCode(); // To get status code
        echo "Details:" . $responseIns->getDetails()['id'];
    }

    public function adjuntar_documentos($documentos, $id)
    {
        //recorre todo los archivos cargados
        foreach ($documentos as $file) {
            //guarda los archivos en el servidor
            $path = $file->storeAs('public', $file->getClientOriginalName());
            //subi el archivo del servidor al registro en el crm
            $this->adjuntar_documento(storage_path("app/$path"), $id);
            //borra el archivo del servidor
            unlink(storage_path("app/$path"));
        }
    }
}
