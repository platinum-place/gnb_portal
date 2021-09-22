<?php

namespace App\Controllers;

use App\Libraries\Emisiones as LibrariesEmisiones;

class Emisiones extends BaseController
{
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
                    $criteria = "((RNC_C_dula:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;

                case 'codigo':
                    $criteria = "((SO_Number:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;
            }
        }
        //libreria para emisiones
        $libreria = new LibrariesEmisiones;
        $emisiones = $libreria->searchRecordsByCriteria("Sales_Orders", $criteria);
        return view("emisiones/index", ["titulo" => "Emisiones", "emisiones" => $emisiones, "libreria" => $libreria]);
    }

    public function emitir($cotizacionid)
    {
        //libreria para emisiones
        $libreria = new LibrariesEmisiones;
        //obtener los datos de la cotizacion, la funcion es heredada de la libreria del api
        $cotizacion = $libreria->getRecord("Quotes", $cotizacionid);
        if ($this->request->getPost()) {
            //obtener los datos del plan elegido
            foreach ($cotizacion->getLineItems() as $lineItem) {
                if ($this->request->getPost("planid") == $lineItem->getProduct()->getEntityId()) {
                    $plan["total"] = round($lineItem->getNetTotal(), 2);
                    $plan["aseguradora"] = $lineItem->getDescription();
                    $plan["planid"] = $lineItem->getProduct()->getEntityId();
                }
            }
            //array que representa al registro que se creara
            //necesita los valores de la cotizacion y el formulario
            //algunos valores, como el plan y la fecha, son fijos
            $emision = [
                "Subject" => "Emisión de póliza desde el portal IT",
                "Due_Date" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years")),
                "Prima" => round($plan["total"], 2),
                "Cuota" => $cotizacion->getFieldValue("Cuota"),
                "Tipo" =>  $cotizacion->getFieldValue("Tipo"),
                "Account_Name" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
                "Contact_Name" =>  session("usuario")->getEntityId(),
                "Plan" => $cotizacion->getFieldValue("Plan"),
                "Suma_asegurada" =>  $cotizacion->getFieldValue("Suma_asegurada"),
                "Status" => "Pendiente",
                "Plazo" => $cotizacion->getFieldValue("Plazo"),
                "Nombre" => $cotizacion->getFieldValue("Nombre"),
                "Apellido" => $cotizacion->getFieldValue("Apellido"),
                "Fecha_de_nacimiento" => $cotizacion->getFieldValue("Fecha_de_nacimiento"),
                "RNC_C_dula" => $cotizacion->getFieldValue("RNC_C_dula"),
                "Correo_electr_nico" => $cotizacion->getFieldValue("Correo_electr_nico"),
                "Direcci_n" => $cotizacion->getFieldValue("Direcci_n"),
                "Tel_Celular" => $cotizacion->getFieldValue("Tel_Celular"),
                "Tel_Residencia" => $cotizacion->getFieldValue("Tel_Residencia"),
                "Tel_Trabajo" => $cotizacion->getFieldValue("Tel_Trabajo"),
                "Nombre_codeudor" => $cotizacion->getFieldValue("Nombre_codeudor"),
                "Apellido_codeudor" => $cotizacion->getFieldValue("Apellido_codeudor"),
                "Tel_Celular_codeudor" => $cotizacion->getFieldValue("Tel_Celular_codeudor"),
                "Tel_Residencia_codeudor" => $cotizacion->getFieldValue("Tel_Residencia_codeudor"),
                "Tel_Trabajo_codeudor" => $cotizacion->getFieldValue("Tel_Trabajo_codeudor"),
                "RNC_C_dula_codeudor" => $cotizacion->getFieldValue("RNC_C_dula_codeudor"),
                "Fecha_de_nacimiento_codeudor" => $cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"),
                "Direcci_n_codeudor" => $cotizacion->getFieldValue("Direcci_n_codeudor"),
                "Correo_electr_nico_codeudor" => $cotizacion->getFieldValue("Correo_electr_nico_codeudor"),
                "A_o" => $cotizacion->getFieldValue("A_o"),
                "Marca" => $cotizacion->getFieldValue("Marca"),
                "Modelo" => $cotizacion->getFieldValue("Modelo"),
                "Uso" => $cotizacion->getFieldValue("Uso"),
                "Tipo_veh_culo" => $cotizacion->getFieldValue("Tipo_veh_culo"),
                "Chasis" => $cotizacion->getFieldValue("Chasis"),
                "Color" => $cotizacion->getFieldValue("Color"),
                "Placa" => $cotizacion->getFieldValue("Placa"),
                "Condiciones" => $cotizacion->getFieldValue("Condiciones"),
            ];
            //crea la cotizacion el en crm
            $emisionid = $libreria->crear_emision($emision, $plan);
            //eliminar la cotizacion
            $libreria->delete("Quotes", $cotizacionid);
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
                        $libreria->uploadAttachment("Sales_Orders", $emisionid, $ruta);
                        //borrar el archivo del servidor local
                        unlink($ruta);
                    }
                }
            }
            session()->setFlashdata('alerta', "¡Cotización emitida correctamente! La emisión estará en estado proceso de validación. Mientras, puedes descargar un certificado de emisión o adjuntar mas documentos.");
            return redirect()->to(site_url("emisiones"));
        }
        return view("emisiones/emitir", [
            "titulo" => "Emitir Cotización No. " . $cotizacion->getFieldValue('Quote_Number'),
            "cotizacion" => $cotizacion
        ]);
    }

    public function descargar($id)
    {
        //libreria para emisiones
        $libreria = new LibrariesEmisiones;
        //obtener datoss de la emision
        $detalles = $libreria->getRecord("Sales_Orders", $id);
        foreach ($detalles->getLineItems() as $lineItem) {
            //obtener datos del plan
            $plan = $libreria->getRecord("Products", $lineItem->getProduct()->getEntityId());
            $neta = number_format($lineItem->getNetTotal() - $lineItem->getNetTotal() * 0.16, 2);
            $isc = number_format($lineItem->getNetTotal() * 0.16, 2);
            $total = number_format($lineItem->getNetTotal(), 2);
        }
        switch ($detalles->getFieldValue("Tipo")) {
            case 'Auto':
                return view('emisiones/auto', [
                    "detalles" => $detalles,
                    "plan" => $plan,
                    "neta" => $neta,
                    "isc" => $isc,
                    "total" => $total,
                ]);
                break;
        }
    }

    public function adjuntar($id)
    {
        //libreria para emisiones
        $libreria = new LibrariesEmisiones;
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
                    $libreria->uploadAttachment("Sales_Orders", $id, $ruta);
                    //borrar el archivo del servidor local
                    unlink($ruta);
                }
            }
            session()->setFlashdata('alerta', "¡Documentos adjuntados correctamente!.");
            //limpiar post
            return redirect()->to(site_url("emisiones"));
        }
    }
}
