<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Adjuntos extends BaseController
{
    protected $libreria;

    function __construct()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $this->libreria = new Zoho;
    }

    public function cotizacion($id)
    {
        //obtener los todos los adjuntos del plan, normalmente es solo uno
        $attachments = $this->libreria->getAttachments("Products", $id);

        foreach ($attachments as $attchmentIns) {
            //descargar un documento en el servidor local
            $file = $this->libreria->downloadAttachment("Products", $id, $attchmentIns->getId(), WRITEPATH . "uploads");

            //forzar al navegador a descargar el archivo
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            //eliminar el archivo descargado
            unlink($file);
            exit;
        }
    }

    public function adjunto_emision($json)
    {
        //el valor de url es un json porque se necesitan dos id
        $json = json_decode($json, true);

        //descargar un documento en el servidor local
        $file = $this->libreria->downloadAttachment("Sales_Orders", $json[0], $json[1], WRITEPATH . "uploads");

        //forzar al navegador a descargar el archivo
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        //eliminar el archivo descargado
        unlink($file);
        exit;
    }

    public function emisiones($id)
    {
        if ($this->request->getPost()) {
            //los archivos debe ser subida al servidor para luego ser adjuntados al registro
            if ($documentos = $this->request->getFiles()) {
                foreach ($documentos['documentos'] as $documento) {
                    if ($documento->isValid() && !$documento->hasMoved()) {
                        //subir el archivo al servidor
                        $documento->move(WRITEPATH . 'uploads');

                        //ruta del archivo subido
                        $ruta = WRITEPATH . 'uploads/' . $documento->getClientName();

                        //funcion para adjuntar el archivo
                        $this->libreria->uploadAttachment("Sales_Orders", $id, $ruta);

                        //borrar el archivo del servidor local
                        unlink($ruta);
                    }
                }

                //alerta
                session()->setFlashdata('alerta', "¡Documentos adjuntados correctamente!.");

                //limpiar post
                return redirect()->to(site_url("adjuntos/emisiones"));
            }
        }

        $emision = $this->libreria->getRecord("Sales_Orders", $id);
        $titulo = "Adjuntar documentos a emisión a nombre de " . $emision->getFieldValue('Nombre') . ' ' . $emision->getFieldValue('Apellido');
        $adjuntos = $this->libreria->getAttachments("Sales_Orders", $id);
        return view("adjuntos/emisiones", ["titulo" => $titulo, "emision" => $emision, "adjuntos" => $adjuntos]);
    }
}
