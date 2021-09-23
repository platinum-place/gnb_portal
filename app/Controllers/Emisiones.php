<?php

namespace App\Controllers;

use App\Libraries\Emisiones as LibrariesEmisiones;
use App\Libraries\EmisionesAuto;
use App\Models\Reporte;

class Emisiones extends BaseController
{
    public function index()
    {
        //en caso de que el usuario sea admin
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

        //lista de emisiones
        $emisiones = $libreria->searchRecordsByCriteria("Sales_Orders", $criteria);

        //vista
        return view("emisiones/index", ["titulo" => "Emisiones", "emisiones" => $emisiones]);
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
                "Subject" => "Emisión desde portal IT",
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

            //alerta
            session()->setFlashdata('alerta', "¡Cotización emitida correctamente! La emisión estará en estado proceso de validación. Mientras, puedes descargar un certificado de emisión o adjuntar mas documentos.");

            //limpiar post
            return redirect()->to(site_url("emisiones"));
        }

        //vista
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

        //selecion de vista
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

    //funcion post
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

            //alerta
            session()->setFlashdata('alerta', "¡Documentos adjuntados correctamente!.");

            //limpiar post
            return redirect()->to(site_url("emisiones"));
        }
    }

    public function reportes()
    {
        if ($this->request->getPost()) {
            //modelo de reporte
            $reporte = new Reporte;
            $reporte->desde = $this->request->getPost("desde");
            $reporte->hasta = $this->request->getPost("hasta");
            $reporte->tipo = $this->request->getPost("tipo");

            switch ($this->request->getPost("tipo")) {
                case 'Incendio':

                    break;

                case 'Auto':
                    //libreria para emisiones
                    $libreria = new EmisionesAuto;

                    //verifica si existen reportes
                    //en caso de si haber emisiones, el array de emisiones ya tendra la primera pagina de objetos
                    $libreria->emisiones_existentes($reporte);

                    //si no encontro registros sale de la funcion
                    if (empty($reporte->emisiones)) {
                        session()->setFlashdata('alerta', 'No existen emisiones dentro del rango de tiempo.');
                        return redirect()->to(site_url("reportes"));
                    } else {
                        //iniciar el contador desde la segunda pagina
                        $pag = 2;

                        //rellenar el array con todos los objetos posibles
                        do {
                            //contamos la cantidad de objetos
                            $cantidad_actual = count($reporte->emisiones);

                            //si no existe una segunda pagina de objetos, entonces ya tendran los necesarios
                            $libreria->emisiones_existentes($reporte, $pag);

                            //volmeos a contar los objetos
                            $cantidad_aumentada = count($reporte->emisiones);

                            //si el array aumento significa que existen mas objetos que buscar
                            //si no debemos salir
                            if ($cantidad_aumentada > $cantidad_actual) {
                                $pag++;
                            } else {
                                $pag = 0;
                            }
                        } while ($pag > 0);

                        $ruta_reporte = $libreria->generar_reporte($reporte);

                        //forzar al navegador a descargar el archivo
                        //es necesario no tener echo antes de descargar
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="' . basename($ruta_reporte) . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($ruta_reporte));
                        readfile($ruta_reporte);
                        //eliminar el archivo descargado
                        unlink($ruta_reporte);
                    }

                    break;
            }
        }

        //vista
        return view("emisiones/reportes", ["titulo" => "Reporte de Pólizas Emitidas"]);
    }

    public function pendientes()
    {
        //en caso de que el usuario sea admin
        if (session('usuario')->getFieldValue("Title") == "Administrador") {
            $criteria = "((Status:equals:Pendiente) and (Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . "))";
        } else {
            $criteria = "((Statusequals:Pendiente) and (Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . ") and (Contact_Name:equals:" . session('usuario')->getEntityId() . "))";
        }

        //libreria para emisiones
        $libreria = new LibrariesEmisiones;
        //lista de emisiones
        $emisiones = $libreria->searchRecordsByCriteria("Sales_Orders", $criteria);

        //vista
        return view("emisiones/pendientes", ["titulo" => "Emisiones Pendientes", "emisiones" => $emisiones]);
    }

    public function mes()
    {
        //en caso de que el usuario sea admin
        if (session('usuario')->getFieldValue("Title") == "Administrador") {
            $criteria = "Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId();
        } else {
            $criteria = "((Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . ") and (Contact_Name:equals:" . session('usuario')->getEntityId() . "))";
        }

        //libreria para emisiones
        $libreria = new LibrariesEmisiones;
        //lista de emisiones
        $emisiones = $libreria->searchRecordsByCriteria("Sales_Orders", $criteria);

        //vista
        return view("emisiones/pendientes", ["titulo" => "Emisiones Del Mes", "emisiones" => $emisiones]);
    }

    public function vencidas()
    {
        //en caso de que el usuario sea admin
        if (session('usuario')->getFieldValue("Title") == "Administrador") {
            $criteria = "Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId();
        } else {
            $criteria = "((Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . ") and (Contact_Name:equals:" . session('usuario')->getEntityId() . "))";
        }

        //libreria para emisiones
        $libreria = new LibrariesEmisiones;
        //lista de emisiones
        $emisiones = $libreria->searchRecordsByCriteria("Sales_Orders", $criteria);

        //vista
        return view("emisiones/vencidas", ["titulo" => "Emisiones En Vencimiento Este Mes", "emisiones" => $emisiones]);
    }
}
