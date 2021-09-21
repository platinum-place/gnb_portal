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
        if ($this->request->getPost()) {
            switch ($this->request->getPost("opcion")) {
                case 'nombre':
                    $criteria = "((Nombre:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;

                case 'apellido':
                    $criteria = "((Apellido:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;

                case 'id':
                    $criteria = "((Identificaci_n:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;

                case 'codigo':
                    $criteria = "((TUA:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;
            }
        }
        $emisiones = $this->zoho->searchRecordsByCriteria("Deals", $criteria);
        return view("emisiones/index", ["titulo" => "Emisiones", "emisiones" => $emisiones]);
    }

    public function emitir($cotizacionid)
    {
        $cotizacion = $this->zoho->getRecord("Quotes", $cotizacionid);
        if ($this->request->getPost()) {
            //toma varios valores de un mismo campo
            $aseguradora = explode(",", $this->request->getPost("aseguradora"));
            //array que representa al registro que se creara
            //necesita los valores de la cotizacion y el formulario
            //algunos valores, como el plan y la fecha, son fijos
            $emision = [
                "Deal_Name" => "Emisión de póliza desde el portal IT",
                "Fecha_de_inicio" => date("Y-m-d"),
                "Closing_Date" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years")),
                "Amount" => round($aseguradora[0]),
                "Cuota" => round($cotizacion->getFieldValue("Cuota")),
                "Type" =>  $cotizacion->getFieldValue("Tipo"),
                "Account_Name" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
                "Contact_Name" =>  session("usuario")->getEntityId(),
                "Plan" => $cotizacion->getFieldValue("Plan"),
                "Suma_asegurada" =>  $cotizacion->getFieldValue("Suma_asegurada"),
                "Coberturas" => $aseguradora[1],
                "Stage" => "Proceso de evaluación",
                "Estado" => "Activo",
                "Plazo" => $cotizacion->getFieldValue("Plazo"),
                "Nombre" => $cotizacion->getFieldValue("Nombre"),
                "Apellido" => $cotizacion->getFieldValue("Apellido"),
                "Identificaci_n" => $cotizacion->getFieldValue("RNC_C_dula")
            ];
            //crear la emision
            $emisionid = $this->zoho->createRecords("Deals", $emision);
            $cliente = [
                "Account_Name" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
                "Reporting_To" =>  session("usuario")->getEntityId(),
                "First_Name" => $cotizacion->getFieldValue("Nombre"),
                "Last_Name" => $cotizacion->getFieldValue("Apellido"),
                "RNC_C_dula" => $cotizacion->getFieldValue("RNC_C_dula"),
                "Date_of_Birth" => $cotizacion->getFieldValue("Fecha_de_nacimiento"),
                "Email" => $cotizacion->getFieldValue("Correo_electr_nico"),
                "Home_Phone" => $cotizacion->getFieldValue("Tel_Residencia"),
                "Mobile" => $cotizacion->getFieldValue("Tel_Celular"),
                "Other_Phone" => $cotizacion->getFieldValue("Tel_Trabajo"),
                "Mailing_Street" => $cotizacion->getFieldValue("Direcci_n"),
                "Lead_Source" => "Portal",
                "Trato" => $emisionid,
                "Tipo" => "Deudor"
            ];
            //crear el deudor
            $clienteid = $this->zoho->createRecords("Contacts", $cliente);
            if (!empty($cotizacion->getFieldValue("Nombre_codeudor"))) {
                $codeudor = [
                    "Account_Name" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
                    "Reporting_To" =>  $clienteid,
                    "First_Name" => $cotizacion->getFieldValue("Nombre_codeudor"),
                    "Last_Name" => $cotizacion->getFieldValue("Apellido_codeudor"),
                    "RNC_C_dula" => $cotizacion->getFieldValue("RNC_C_dula_codeudor"),
                    "Date_of_Birth" => $cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"),
                    "Email" => $cotizacion->getFieldValue("Correo_electr_nico_codeudor"),
                    "Home_Phone" => $cotizacion->getFieldValue("Tel_Residencia_codeudor"),
                    "Mobile" => $cotizacion->getFieldValue("Tel_Celular_codeudor"),
                    "Other_Phone" => $cotizacion->getFieldValue("Tel_Trabajo_codeudor"),
                    "Mailing_Street" => $cotizacion->getFieldValue("Direcci_n_codeudor"),
                    "Lead_Source" => "Portal",
                    "Trato" => $emisionid,
                    "Tipo" => "Codeudor"
                ];
                //crear el codeudor
                $codeudorid = $this->zoho->createRecords("Contacts", $codeudor);
            }
            $bien = [
                "Aseguradora" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
                "Nombre" => $cotizacion->getFieldValue("Nombre"),
                "Apellido" => $cotizacion->getFieldValue("Apellido"),
                "Name" => $cotizacion->getFieldValue("RNC_C_dula"),
                "Fecha_de_nacimiento" => $cotizacion->getFieldValue("Fecha_de_nacimiento"),
                "Email" => $cotizacion->getFieldValue("Correo_electr_nico"),
                "Direcci_n" => $cotizacion->getFieldValue("Direcci_n"),
                "RNC_C_dula" => $cotizacion->getFieldValue("RNC_C_dula"),
                "Email" => $cotizacion->getFieldValue("Correo_electr_nico"),
                "Tel_Residencia" => $cotizacion->getFieldValue("Tel_Residencia"),
                "Tel_Celular" => $cotizacion->getFieldValue("Tel_Celular"),
                "Tel_Trabajo" => $cotizacion->getFieldValue("Tel_Trabajo"),
                "Estado" => "2",
                "Trato" => $emisionid,
                "Vigencia_hasta" => date("Y-m-d"),
                "Vigencia_desde" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years")),
            ];
            //crear el bien
            $bienid = $this->zoho->createRecords("Bienes", $bien);
            //eliminar la cotizacion
            $this->zoho->delete("Quotes", $cotizacionid);
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
                        $this->zoho->uploadAttachment("Deals", $emisionid, $ruta);
                        //borrar el archivo del servidor local
                        unlink($ruta);
                    }
                }
            }
            session()->setFlashdata('alerta', "¡Cotización emitida correctamente! La emisión estará en estado “Proceso de evaluación” mientras es validada. Mientras, puedes descargar un certificado de emisión.");
            return redirect()->to(site_url("emisiones"));
        }
        return view("emisiones/emitir", [
            "titulo" => "Emitir Cotización No. " . $cotizacion->getFieldValue('Quote_Number'),
            "cotizacion" => $cotizacion
        ]);
    }

    public function descargar($id)
    {
        //obtener datoss de la emision
        $emision = $this->zoho->getRecord("Deals", $id);
        //obtener datos del plan
        $plan = $this->zoho->getRecord("Products", $emision->getFieldValue("Coberturas")->getEntityId());
        //obtener datos de los clientes
        $criteria = "Trato:equals:$id";
        $clientes = $this->zoho->searchRecordsByCriteria("Contacts", $criteria);
        $deudor = null;
        $codeudor = null;
        foreach ($clientes as $cliente) {
            switch ($cliente->getFieldValue("Tipo")) {
                case 'Deudor':
                    $deudor = $cliente;
                    break;

                case 'Codeudor':
                    $codeudor = $cliente;
                    break;
            }
        }
        switch ($emision->getFieldValue("Type")) {
            case 'Vida':
                return view('emisiones/vida', ["emision" => $emision, "plan" => $plan, "deudor" => $deudor, "codeudor" => $codeudor]);
                break;
        }
    }
}
