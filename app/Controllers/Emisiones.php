<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Emisiones extends BaseController
{
    protected $zoho;

    function __construct()
    {
        $this->zoho = new Zoho;
    }

    public function index()
    {
        if (session('usuario')->getFieldValue("Title") == "Administrador") {
            $criteria = "Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId();
        } else {
            $criteria = "((Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . ") and (Contact_Name:equals:" . session('usuario')->getEntityId() . "))";
        }
        $emisiones = $this->zoho->searchRecordsByCriteria("Deals", $criteria);
        return view("emisiones/index", ["titulo" => "Emisiones", "emisiones" => $emisiones]);
    }

    public function emitir($id)
    {
        $cotizacion = $this->zoho->getRecord("Quotes", $id);
        if ($this->request->getPost()) {
            //toma varios valores de un mismo campo
            $aseguradora = explode(",", $this->request->getPost("aseguradora"));
            //array que representa al registro que se creara
            //necesita los valores de la cotizacion y el formulario
            //algunos valores, como el plan y la fecha, son fijos
            $emision = [
                "Deal_Name" => "Emisión de póliza desde el portal IT",
                "Closing_Date" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years")),
                "Amount" => round($aseguradora[0]),
                "Cuota" => round($cotizacion->getFieldValue("Cuota")),
                "Type" =>  $cotizacion->getFieldValue("Tipo"),
                "Account_Name" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
                "Contact_Name" =>  session("usuario")->getEntityId(),
                "Plan" => "Vida",
                "Suma_asegurada" =>  $cotizacion->getFieldValue("Suma_asegurada"),
                "Nombre" => $cotizacion->getFieldValue("Nombre"),
                "Apellido" => $cotizacion->getFieldValue("Apellido"),
                "Identificaci_n" => $cotizacion->getFieldValue("RNC_C_dula"),
                "Fecha_de_nacimiento" => $cotizacion->getFieldValue("Fecha_de_nacimiento"),
                "Correo_electr_nico" => $cotizacion->getFieldValue("Correo_electr_nico"),
                "Tel_Residencia" => $cotizacion->getFieldValue("Tel_Residencia"),
                "Tel_Celular" => $cotizacion->getFieldValue("Tel_Celular"),
                "Tel_Trabajo" => $cotizacion->getFieldValue("Tel_Trabajo"),
                "Direcci_n" => $cotizacion->getFieldValue("Direcci_n"),
                "Nombre_codeudor" => $cotizacion->getFieldValue("Nombre_codeudor"),
                "Apellido_codeudor" => $cotizacion->getFieldValue("Apellido_codeudor"),
                "Identificaci_n_codeudor" => $cotizacion->getFieldValue("RNC_C_dula_codeudor"),
                "Fecha_de_nacimiento_codeudor" => $cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"),
                "Correo_electr_nico_codeudor" => $cotizacion->getFieldValue("Correo_electr_nico_codeudor"),
                "Tel_Residencia_codeudor" => $cotizacion->getFieldValue("Tel_Residencia_codeudor"),
                "Tel_Celular_codeudor" => $cotizacion->getFieldValue("Tel_Celular_codeudor"),
                "Tel_Trabajo_codeudor" => $cotizacion->getFieldValue("Tel_Trabajo_codeudor"),
                "Direcci_n_codeudor" => $cotizacion->getFieldValue("Direcci_n_codeudor"),
                "Coberturas" => $aseguradora[1],
                "Stage" => "Proceso de evaluación",
                "Plazo" => $cotizacion->getFieldValue("Plazo")
            ];
            $this->zoho->delete("Quotes", $id);
            //crear registro en crm
            $id = $this->zoho->createRecords("Deals", $emision);
            //los archivos debe ser subida al servidor para luego ser adjuntados al registro
            if ($documentos = $this->request->getFiles()) {
                foreach ($documentos['documentos'] as $documento) {
                    if ($documento->isValid() && !$documento->hasMoved()) {
                        //cambiar el nombre del archivo
                        $newName = $documento->getRandomName();
                        //subir el archivo al servidor
                        $documento->move(WRITEPATH . 'uploads', $newName);
                        //ruta del archivo subido
                        $ruta = WRITEPATH . 'uploads/' . $newName;
                        //funcion para adjuntar el archivo
                        $this->zoho->uploadAttachment("Deals", $id, $ruta);
                        //borrar el archivo del servidor local
                        unlink($ruta);
                    }
                }
            }
            session()->setFlashdata('alerta', "¡Cotización emitida correctamente! La emisión estará en estado “Proceso de evaluación” mientras es validado por nosotros, mientras, puedes descargarla.");
            return redirect()->to(site_url("emisiones"));
        }
        return view("emisiones/emitir", [
            "titulo" => "Emitir Cotización No. " . $cotizacion->getFieldValue('Quote_Number'),
            "cotizacion" => $cotizacion
        ]);
    }
}
