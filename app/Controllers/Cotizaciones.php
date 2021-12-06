<?php

namespace App\Controllers;

use App\Libraries\Cotizar\CotizarAuto;
use App\Libraries\Cotizar\CotizarDesempleo;
use App\Libraries\Cotizar\CotizarIncendio;
use App\Libraries\Cotizar\CotizarVida;
use App\Libraries\Reporte\Reporte;
use App\Libraries\Reporte\ReporteAuto;
use App\Libraries\Reporte\ReporteDesempleo;
use App\Libraries\Reporte\ReporteIncendio;
use App\Libraries\Reporte\ReporteVida;
use App\Libraries\Zoho;
use App\Models\Cotizacion;
use CodeIgniter\HTTP\RedirectResponse;
use zcrmsdk\crm\exception\ZCRMException;

class Cotizaciones extends BaseController
{
    public function index(): string
    {
        //libreria con funciones de zoho
        $libreria = new \App\Libraries\Cotizaciones();

        //objeto para almacenar valores relacionados a la cotizacion
        $cotizacion = new Cotizacion();

        if ($this->request->getPost()) {
            // informacion general
            $cotizacion->suma = $this->request->getPost("suma");
            $cotizacion->plan = $this->request->getPost("plan");
            $cotizacion->plazo = $this->request->getPost("plazo");
            $cotizacion->fecha_deudor = $this->request->getPost("deudor");

            switch ($cotizacion->plan) {
                case "Vida":
                    //informacion del plan vida
                    $cotizacion->fecha_codeudor = $this->request->getPost("codeudor");

                    $cotizar = new CotizarVida($cotizacion, $libreria);
                    break;

                case "Seguro Incendio Hipotecario":
                    //informacion sobre plan incendio
                    $cotizacion->direccion = $this->request->getPost("direccion");
                    $cotizacion->prestamo = $this->request->getPost("prestamo");
                    $cotizacion->construccion = $this->request->getPost("construccion");
                    $cotizacion->riesgo = $this->request->getPost("riesgo");

                    $cotizar = new CotizarIncendio($cotizacion, $libreria);
                    break;

                case "Vida/Desempleo":
                    //informacion del plan desempleo
                    $cotizacion->cuota = $this->request->getPost("cuota");

                    $cotizar = new CotizarDesempleo($cotizacion, $libreria);
                    break;

                default:
                    //informacion para el plan auto
                    $cotizacion->plan = $this->request->getPost("plan");
                    $cotizacion->ano = $this->request->getPost("ano");
                    $cotizacion->uso = $this->request->getPost("uso");
                    $cotizacion->estado = $this->request->getPost("estado");
                    $cotizacion->marcaid = $this->request->getPost("marca");
                    // datos relacionados al modelo, dividios en un array
                    $modelo = explode(",", $this->request->getPost("modelo"));
                    // asignando valores al objeto
                    $modeloid = $modelo[0];
                    $modelotipo = $modelo[1];
                    $cotizacion->modeloid = $modeloid;
                    $cotizacion->modelotipo = $modelotipo;

                    $cotizar = new CotizarAuto($cotizacion, $libreria);
                    break;
            }

            $cotizar->cotizar_planes();

            //en caso de no encontrar planes para cotizar
            if (empty($cotizacion->planes)) {
                session()->setFlashdata('alerta', 'No existen planes para cotizar.');
            }
        }

        // libreria del api para obtener todo los registros de un modulo, en este caso del de marcas
        $marcas = $libreria->getRecords("Marcas");

        // formatear el resultado para ordenarlo alfabeticamente en forma descendente
        asort($marcas);
        return view("cotizaciones/cotizar", [
            "titulo" => "Cotizar",
            "marcas" => $marcas,
            "cotizacion" => $cotizacion
        ]);
    }

    // funcion ajax
    public function lista_modelos()
    {
        // inicializar el contador
        $pag = 1;
        $libreria = new Zoho();
        // criterio de aseguir por la api
        $criteria = "Marca:equals:" . $this->request->getPost("marcaid");
        // repetir en secuencia para obtener todo los modelos de una misma marca,
        // teniendo en cuenta que pueden ser mas de 200 en algunos casos
        // por tanto en necesario recontar la sentencia pero variando en paginas para superar el limite de la api
        do {
            // obtener los modelos empezando por la primera pagina
            $modelos = $libreria->searchRecordsByCriteria("Modelos", $criteria, $pag);
            // en caso de encontrar valores
            if (!empty($modelos)) {
                // formatear el resultado para ordenarlo alfabeticamente en forma descendente
                asort($modelos);
                // aumentar el contador
                $pag++;
                // mostrar los valores en forma de option para luego ser mostrados en dentro de un select
                foreach ($modelos as $modelo) {
                    echo '<option value="' . $modelo->getEntityId() . "," . $modelo->getFieldValue('Tipo') . '">' . strtoupper($modelo->getFieldValue('Name')) . '</option>';
                }
            } else {
                // igualar a 0 el contador para salir del ciclo
                $pag = 0;
            }
        } while ($pag > 0);
    }

    public function buscar_cotizaciones(): string
    {
        $libreria = new \App\Libraries\Cotizaciones();
        $cotizaciones = $libreria->lista_cotizaciones();
        return view("cotizaciones/buscar_cotizaciones", [
            "titulo" => "Buscar Cotizaciones",
            "cotizaciones" => $cotizaciones
        ]);
    }

    public function buscar_emisiones(): string
    {
        $libreria = new \App\Libraries\Cotizaciones();
        $cotizaciones = $libreria->lista_emisiones();
        return view("cotizaciones/buscar_emisiones", [
            "titulo" => "Buscar Emisiones",
            "cotizaciones" => $cotizaciones
        ]);
    }

    // funcion post
    public function completar(): RedirectResponse
    {
        // pasa la tabla de cotizacion en array para agregarla al registro
        $planes = json_decode($this->request->getPost("planes"), true);
        $registro = [
            "Subject" => $this->request->getPost("nombre") . " " . $this->request->getPost("apellido"),
            "Valid_Till" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 30 days")),
            "Vigencia_desde" => date("Y-m-d"),
            "Account_Name" => session('cuenta_id'),
            "Contact_Name" => session('usuario_id'),
            "Construcci_n" => $this->request->getPost("construccion"),
            "Riesgo" => $this->request->getPost("riesgo"),
            "Quote_Stage" => "Cotizando",
            "Nombre" => $this->request->getPost("nombre"),
            "Apellido" => $this->request->getPost("apellido"),
            "Fecha_de_nacimiento" => $this->request->getPost("fecha"),
            "RNC_C_dula" => $this->request->getPost("rnc_cedula"),
            "Correo_electr_nico" => $this->request->getPost("correo"),
            "Direcci_n" => $this->request->getPost("direccion"),
            "Tel_Celular" => $this->request->getPost("telefono"),
            "Tel_Residencia" => $this->request->getPost("tel_residencia"),
            "Tel_Trabajo" => $this->request->getPost("tel_trabajo"),
            "Plan" => $this->request->getPost("plan"),
            "Tipo" => $this->request->getPost("tipo"),
            "Suma_asegurada" => $this->request->getPost("suma"),
            "Plazo" => $this->request->getPost("plazo"),
            "Cuota" => $this->request->getPost("cuota"),
            "Prestamo" => $this->request->getPost("prestamo"),
            "A_o" => $this->request->getPost("ano"),
            "Marca" => $this->request->getPost("marcaid"),
            "Modelo" => $this->request->getPost("modeloid"),
            "Uso" => $this->request->getPost("uso"),
            "Tipo_veh_culo" => $this->request->getPost("modelotipo"),
            "Chasis" => $this->request->getPost("chasis"),
            "Color" => $this->request->getPost("color"),
            "Placa" => $this->request->getPost("placa"),
            "Condiciones" => $this->request->getPost("estado"),
            "Nombre_codeudor" => $this->request->getPost("nombre_codeudor"),
            "Apellido_codeudor" => $this->request->getPost("apellido_codeudor"),
            "Tel_Celular_codeudor" => $this->request->getPost("telefono_codeudor"),
            "Tel_Residencia_codeudor" => $this->request->getPost("tel_residencia_codeudor"),
            "Tel_Trabajo_codeudor" => $this->request->getPost("tel_trabajo_codeudor"),
            "RNC_C_dula_codeudor" => $this->request->getPost("rnc_cedula_codeudor"),
            "Fecha_de_nacimiento_codeudor" => $this->request->getPost("fecha_codeudor"),
            "Direcci_n_codeudor" => $this->request->getPost("direccion_codeudor"),
            "Correo_electr_nico_codeudor" => $this->request->getPost("correo_codeudor")
        ];

        $libreria = new Zoho();
        $id = $libreria->createRecords("Quotes", $registro, $planes);

        $alerta = view("alertas/completar_cotizacion");
        session()->setFlashdata('alerta', $alerta);
        return redirect()->to(site_url("cotizaciones/emitir/" . $id));
    }

    /**
     * @throws ZCRMException
     */
    public function editar($id)
    {
        $libreria = new Zoho();
        // obtener datos de la cotizacion
        $cotizacion = $libreria->getRecord("Quotes", $id);

        if ($this->request->getPost()) {
            // datos generales para crear una cotizacion
            $registro = [
                "Nombre" => $this->request->getPost("nombre"),
                "Apellido" => $this->request->getPost("apellido"),
                "Fecha_de_nacimiento" => $this->request->getPost("fecha"),
                "RNC_C_dula" => $this->request->getPost("rnc_cedula"),
                "Correo_electr_nico" => $this->request->getPost("correo"),
                "Direcci_n" => $this->request->getPost("direccion"),
                "Tel_Celular" => $this->request->getPost("telefono"),
                "Tel_Residencia" => $this->request->getPost("tel_residencia"),
                "Tel_Trabajo" => $this->request->getPost("tel_trabajo"),
                "Nombre_codeudor" => $this->request->getPost("nombre_codeudor"),
                "Apellido_codeudor" => $this->request->getPost("apellido_codeudor"),
                "Tel_Celular_codeudor" => $this->request->getPost("telefono_codeudor"),
                "Tel_Residencia_codeudor" => $this->request->getPost("tel_residencia_codeudor"),
                "Tel_Trabajo_codeudor" => $this->request->getPost("tel_trabajo_codeudor"),
                "RNC_C_dula_codeudor" => $this->request->getPost("rnc_cedula_codeudor"),
                "Direcci_n_codeudor" => $this->request->getPost("direccion_codeudor"),
                "Correo_electr_nico_codeudor" => $this->request->getPost("correo_codeudor"),
                "Chasis" => $this->request->getPost("chasis"),
                "Color" => $this->request->getPost("color"),
                "Placa" => $this->request->getPost("placa")
            ];

            // agregar los cambios al registro en el crm
            $libreria->update("Quotes", $id, $registro);

            // alerta general cuando se edita una cotizacion en el crm
            session()->setFlashdata('alerta', "¡Cotización editada exitosamente!.");
            return redirect()->to(site_url("cotizaciones/emitir/$id"));
        }

        return view("cotizaciones/editar", [
            "titulo" => "Editar Cotización, a nombre de " . $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido'),
            "cotizacion" => $cotizacion
        ]);
    }

    /**
     * @throws ZCRMException
     */
    public function emitir($id)
    {
        $libreria = new \App\Libraries\Cotizaciones();

        // obtener los datos de la cotizacion, la funcion es heredada de la libreria del api
        $cotizacion = $libreria->getRecord("Quotes", $id);

        $cliente = $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido');

        if ($this->request->getPost()) {
            //actualizar los datos de la cotizacion
            $libreria->actualizar_cotizacion($cotizacion, $this->request->getPost("planid"));

            // los archivos debe ser subida al servidor para luego ser adjuntados al registro
            if ($documentos = $this->request->getFiles()) {
                $libreria->adjuntar_archivo($documentos['documentos'], $id);
            }

            $alerta = view("alertas/emitir_cotizacion", ["id" => $id, "cliente" => $cliente]);
            session()->setFlashdata('alerta', $alerta);
            return redirect()->to(site_url("cotizaciones/buscar_emisiones"));
        }

        return view("cotizaciones/emitir", [
            "titulo" => "Emitir Cotización, a nombre de $cliente",
            "cotizacion" => $cotizacion
        ]);
    }

    public function condicionado($id)
    {
        $libreria = new Zoho();
        // obtener los todos los adjuntos del plan, normalmente es solo uno
        $attachments = $libreria->getAttachments("Products", $id);
        foreach ($attachments as $attchmentIns) {
            // descargar un documento en el servidor local
            $file = $libreria->downloadAttachment("Products", $id, $attchmentIns->getId(), WRITEPATH . "uploads");
            // forzar al navegador a descargar el archivo
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            // eliminar el archivo descargado
            unlink($file);
            exit();
        }
    }

    public function adjuntar($id)
    {
        $libreria = new Zoho();
        $cotizacion = $libreria->getRecord("Quotes", $id);

        // los archivos debe ser subida al servidor para luego ser adjuntados al registro
        if ($documentos = $this->request->getFiles()) {
            $cantidad = 0;
            foreach ($documentos['documentos'] as $documento) {
                if ($documento->isValid() && !$documento->hasMoved()) {
                    // subir el archivo al servidor
                    $documento->move(WRITEPATH . 'uploads');
                    // ruta del archivo subido
                    $ruta = WRITEPATH . 'uploads/' . $documento->getClientName();
                    // funcion para adjuntar el archivo
                    $libreria->uploadAttachment("Quotes", $id, $ruta);
                    // borrar el archivo del servidor local
                    unlink($ruta);
                    $cantidad++;
                }
            }

            session()->setFlashdata('alerta', "Documentos adjuntados: $cantidad, en la emisión a nombre de " . $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido'));
            return redirect()->to(site_url("cotizaciones/buscar/Emitida"));
        }
        return view("cotizaciones/adjuntar", [
            "titulo" => "Adjuntar a emisión, a nombre de " . $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido'),
            "cotizacion" => $cotizacion
        ]);
    }

    public function descargar($id): string
    {
        $libreria = new Zoho();
        // obtener datos de la cotizacion
        $cotizacion = $libreria->getRecord("Quotes", $id);

        if ($cotizacion->getFieldValue('Quote_Stage') == "Cotizando") {
            return view('cotizaciones/cotizacion', [
                "cotizacion" => $cotizacion,
                "libreria" => $libreria
            ]);
        } else {
            // informacion sobre las coberturas, la aseguradora,las coberturas
            $plan = $libreria->getRecord("Products", $cotizacion->getFieldValue("Coberturas")
                ->getEntityId());
            return view('cotizaciones/emision', [
                "cotizacion" => $cotizacion,
                "plan" => $plan
            ]);
        }
    }

    public function reportes()
    {
        $libreria = new \App\Libraries\Cotizaciones();

        $emisiones = $libreria->lista_emisiones();

        if (empty($emisiones)) {
            return null;
        }

        switch ($this->request->getPost("plan")) {
            case 'Auto':
                $reporte = new ReporteAuto();
                break;

            case 'Vida':
                $reporte = new ReporteVida();
                break;

            case 'Vida/Desempleo':
                $reporte = new ReporteDesempleo();
                break;

            case 'Seguro Incendio Hipotecario':
                $reporte = new ReporteIncendio();
                break;
        }

        if (!empty($reporte)) {
            $ruta_reporte = $reporte->generar_reporte($emisiones, $this->request->getPost("desde"), $this->request->getPost("hasta"));
        }

        // si no encontro registros
        if (empty($ruta_reporte)) {
            session()->setFlashdata('alerta', 'No existen emisiones dentro del rango de tiempo.');
            return redirect()->to(site_url());
        }

        // forzar al navegador a descargar el archivo

        // funciona en ambos ambientes
        $nombre = "Reporte " . date("d-m-Y");
        return $this->response->download($ruta_reporte, null)->setFileName("$nombre.xlsx");

        // no funciona en ambiente de produccion, solo en desarrollo local
        // es necesario no tener echo antes de descargar
        /*
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
         */
    }
}
