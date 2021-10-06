<?php

namespace App\Controllers;

use App\Libraries\Emisiones as LibrariesEmisiones;

class Emisiones extends BaseController
{
    protected $libreria;

    function __construct()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $this->libreria = new LibrariesEmisiones;
    }

    public function index()
    {;
        if ($this->request->getPost()) {
            switch ($this->request->getPost("opcion")) {
                case 'codigo':
                    $criteria = "((Numeraci_n:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;
            }

            $emisiones = $this->libreria->searchRecordsByCriteria("Deals", $criteria);
            return view("emisiones/index", ["titulo" => "Emisiones de " . $this->request->getPost("busqueda"), "emisiones" => $emisiones]);
        }

        //lista de emisiones
        $emisiones = $this->libreria->lista();
        return view("emisiones/index", ["titulo" => "Emisiones", "emisiones" => $emisiones]);
    }

    public function descargar($id)
    {
        //obtener datoss de la emision
        $emision = $this->libreria->getRecord("Deals", $id);

        //informacion sobre las coberturas, la aseguradora,las coberturas
        $plan = $this->libreria->getRecord("Products", $emision->getFieldValue("Coberturas")->getEntityId());

        //informacion sobre el cliente
        $deudor = $this->libreria->getRecord("Leads", $emision->getFieldValue("Cliente")->getEntityId());
        if (!empty($emision->getFieldValue("Codeudor"))) {
            $codeudor = $this->libreria->getRecord("Leads", $emision->getFieldValue("Codeudor")->getEntityId());
        }

        //resumen de las primas
        $neta = $emision->getFieldValue("Amount") - ($emision->getFieldValue("Amount") * 0.16);
        $isc = $emision->getFieldValue("Amount") * 0.16;
        $total = $emision->getFieldValue("Amount");

        //selecion de vista
        switch ($emision->getFieldValue("Tipo_portal")) {
            case 'Auto':
                //buscar todo los bienes, normalmente solo uno, que representa el vehiculo
                $criterio = "Trato:equals:$id";
                $bienes = $this->libreria->searchRecordsByCriteria("Bienes", $criterio, 1, 1);
                foreach ($bienes as $bien) {
                    $vehiculo = $bien;
                }

                return view('emisiones/descargar_auto', [
                    "emision" => $emision,
                    "plan" => $plan,
                    "deudor" => $deudor,
                    "vehiculo" => $vehiculo,
                    "neta" => $neta,
                    "isc" => $isc,
                    "total" => $total,
                ]);
                break;

            case 'Vida':
                return view('emisiones/descargar_vida', [
                    "emision" => $emision,
                    "plan" => $plan,
                    "deudor" => $deudor,
                    "codeudor" => $codeudor,
                    "neta" => $neta,
                    "isc" => $isc,
                    "total" => $total,
                ]);
                break;

            case 'incendio':
                return view('emisiones/descargar_incendio', [
                    "emision" => $emision,
                    "plan" => $plan,
                    "deudor" => $deudor,
                    "neta" => $neta,
                    "isc" => $isc,
                    "total" => $total,
                ]);
                break;

            case 'desempleo':
                return view('emisiones/descargar_desempleo', [
                    "emision" => $emision,
                    "plan" => $plan,
                    "deudor" => $deudor,
                    "neta" => $neta,
                    "isc" => $isc,
                    "total" => $total,
                ]);
                break;
        }
    }

    public function emitir($cotizacionid)
    {
        //obtener los datos de la cotizacion, la funcion es heredada de la libreria del api
        $cotizacion = $this->libreria->getRecord("Quotes", $cotizacionid);

        if ($this->request->getPost()) {
            //obtener los datos del plan elegido
            foreach ($cotizacion->getLineItems() as $lineItem) {
                if ($this->request->getPost("planid") == $lineItem->getProduct()->getEntityId()) {
                    $total = round($lineItem->getNetTotal(), 2);
                    $planid = $lineItem->getProduct()->getEntityId();
                }
            }

            //
            //crear cliente
            //
            $clienteid = $this->libreria->crear_cliente($cotizacion);

            //
            //crear codeudor
            //
            if (!empty($cotizacion->getFieldValue("Nombre_codeudor"))) {
                $codeudorid = $this->libreria->crear_codeudor($cotizacion);
            } else {
                $codeudorid = null;
            }

            //
            //Crear emision
            //
            //array que representa al registro que se creara
            //necesita los valores de la cotizacion y el formulario
            //algunos valores, como el plan y la fecha, son fijos
            $emisionid = $this->libreria->crear_emision($cotizacion, $total, $planid, $clienteid, $codeudorid);

            //
            //crear bien
            //
            if (!empty($cotizacion->getFieldValue("Marca"))) {
                $bienid = $this->libreria->crear_bien($cotizacion, $emisionid);
            }

            //eliminar la cotizacion
            $this->libreria->delete("Quotes", $cotizacionid);

            //los archivos debe ser subida al servidor para luego ser adjuntados al registro
            if ($documentos = $this->request->getFiles()) {
                foreach ($documentos['documentos'] as $documento) {
                    if ($documento->isValid() && !$documento->hasMoved()) {
                        //subir el archivo al servidor
                        $documento->move(WRITEPATH . 'uploads');

                        //ruta del archivo subido
                        $ruta = WRITEPATH . 'uploads/' . $documento->getClientName();

                        //funcion para adjuntar el archivo
                        $this->libreria->uploadAttachment("Deals", $emisionid, $ruta);

                        //borrar el archivo del servidor local
                        unlink($ruta);
                    }
                }
            }

            //alerta
            session()->setFlashdata('alerta', "¡Cotización emitida correctamente! La emisión estará en proceso de validación. Mientras, puedes descargar un certificado de emisión o adjuntar mas documentos.");

            //limpiar post
            return redirect()->to(site_url("emisiones"));
        }

        //vista
        return view("emisiones/emitir", ["titulo" => "Emitir Cotización No. " . $cotizacion->getFieldValue('Quote_Number'), "cotizacion" => $cotizacion]);
    }
}
