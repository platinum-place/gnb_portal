<?php

namespace App\Controllers;

use App\Libraries\Auto;
use App\Libraries\Cotizaciones as LibrariesCotizaciones;
use App\Libraries\Desempleo;
use App\Libraries\Incendio;
use App\Libraries\Reportes;
use App\Libraries\Vida;

class Cotizaciones extends BaseController
{
    public function index()
    {
        $libreria = new LibrariesCotizaciones;
        //libreria del api para obtener todo los registros de un modulo, en este caso del de marcas
        $marcas = $libreria->getRecords("Marcas");
        //formatear el resultado para ordenarlo alfabeticamente en forma descendente
        asort($marcas);
        return view("cotizar", ["titulo" => "Cotizar", "marcas" => $marcas]);
    }

    //funcion post
    public function lista_modelos()
    {
        //inicializar el contador
        $pag = 1;
        $libreria = new Auto;

        //criterio de aseguir por la api
        $criteria = "Marca:equals:" . $this->request->getPost("marcaid");

        //repetir en secuencia para obtener todo los modelos de una misma marca, 
        //teniendo en cuenta que pueden ser mas de 200 en algunos casos
        // por tanto en necesario recontar la sentencia pero variando en paginas para superar el limite de la api
        do {

            //obtener los modelos empezando por la primera pagina
            $modelos =  $libreria->searchRecordsByCriteria("Modelos", $criteria, $pag);

            //en caso de encontrar valores
            if (!empty($modelos)) {

                //formatear el resultado para ordenarlo alfabeticamente en forma descendente
                asort($modelos);

                //aumentar el contador
                $pag++;

                //mostrar los valores en forma de option para luego ser mostrados en dentro de un select
                foreach ($modelos as $modelo) {
                    echo '<option value="' . $modelo->getEntityId() . "," . $modelo->getFieldValue('Tipo') . '">' . strtoupper($modelo->getFieldValue('Name')) . '</option>';
                }
            } else {
                //igualar a 0 el contador para salir del ciclo
                $pag = 0;
            }
        } while ($pag > 0);
    }

    public function cotizar()
    {
        $modeloid = null;
        $modelotipo = null;

        switch ($this->request->getPost("plan")) {
            case 'Vida':
                $libreria = new Vida;
                $cotizaciones = $libreria->cotizar(
                    $this->request->getPost("suma"),
                    $this->request->getPost("plazo"),
                    $this->request->getPost("deudor"),
                    $this->request->getPost("codeudor")
                );
                break;

            case 'Vida/Desempleo':
                $libreria = new Desempleo;
                $cotizaciones = $libreria->cotizar(
                    $this->request->getPost("deudor"),
                    $this->request->getPost("suma"),
                    $this->request->getPost("cuota"),
                    $this->request->getPost("plazo")
                );
                break;

            case 'Seguro Incendio Hipotecario':
                $libreria = new Incendio;
                $cotizaciones = $libreria->cotizar(
                    $this->request->getPost("suma"),
                );
                break;

            default:
                $libreria = new Auto;
                //datos relacionados al modelo, dividios en un array
                $modelo = explode(",", $this->request->getPost("modelo"));

                //asignando valores al objeto
                $modeloid = $modelo[0];
                $modelotipo = $modelo[1];

                $cotizaciones = $libreria->cotizar(
                    $this->request->getPost("uso"),
                    $this->request->getPost("suma"),
                    $this->request->getPost("ano"),
                    $this->request->getPost("marca"),
                    $modeloid,
                    $modelotipo,
                    $this->request->getPost("plan"),
                );
                break;
        }

        if (!empty($cotizaciones)) {
            //libreria del api para obtener todo los registros de un modulo, en este caso del de marcas
            $marcas = $libreria->getRecords("Marcas");
            //formatear el resultado para ordenarlo alfabeticamente en forma descendente
            asort($marcas);
            return view("cotizar", [
                "titulo" => "Cotizar",
                "marcas" => $marcas,
                "cotizaciones" => $cotizaciones,
                "plan" => $this->request->getPost("plan"),
                "uso" => $this->request->getPost("uso"),
                "suma" => $this->request->getPost("suma"),
                "ano" => $this->request->getPost("ano"),
                "estado" => $this->request->getPost("estado"),
                "marcaid" => $this->request->getPost("marca"),
                "modeloid" => $modeloid,
                "modelotipo" => $modelotipo,
                "cuota" => $this->request->getPost("cuota"),
                "plazo" => $this->request->getPost("plazo"),
                "prestamo" => $this->request->getPost("prestamo"),
                "fecha_deudor" => $this->request->getPost("deudor"),
                "fecha_codeudor" => $this->request->getPost("codeudor"),
                "direccion" => $this->request->getPost("direccion"),
                "construccion" => $this->request->getPost("construccion"),
                "riesgo" => $this->request->getPost("riesgo"),
            ]);
        } else {
            session()->setFlashdata('alerta', 'No existen planes para cotizar.');
            return redirect()->to(site_url("cotizaciones"));
        }
    }

    //funcion post
    public function completar()
    {
        //pasa la tabla de cotizacion en array para agregarla al registro
        $cotizaciones = json_decode($this->request->getPost("cotizaciones"), true);

        $registro = [
            "Subject" => "Portal IT",
            "Valid_Till" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 30 days")),
            "Account_Name" =>  session('cuenta_id'),
            "Contact_Name" =>  session('usuario_id'),
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
            "Tipo" =>  $this->request->getPost("tipo"),
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

        //crea la cotizacion el en crm
        $libreria = new LibrariesCotizaciones;
        $id = $libreria->crear_cotizacion($registro, $cotizaciones);

        $cliente = $this->request->getPost("nombre") . " " . $this->request->getPost("apellido");
        $alerta = view("alertas/completar_cotizacion", ["cliente" => $cliente]);
        session()->setFlashdata('alerta', $alerta);

        return redirect()->to(site_url("cotizaciones/buscar/Cotizando"));
    }

    public function buscar($filtro)
    {
        $libreria = new LibrariesCotizaciones;
        $cotizaciones = $libreria->lista_cotizaciones();
        return view("buscar", ["titulo" => "Buscar", "cotizaciones" => $cotizaciones, "filtro" => $filtro]);
    }

    public function condicionado($id)
    {
        $libreria = new LibrariesCotizaciones;
        //obtener los todos los adjuntos del plan, normalmente es solo uno
        $attachments = $libreria->getAttachments("Products", $id);

        foreach ($attachments as $attchmentIns) {
            //descargar un documento en el servidor local
            $file = $libreria->downloadAttachment("Products", $id, $attchmentIns->getId(), WRITEPATH . "uploads");

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

    public function editar($id)
    {
        $libreria = new LibrariesCotizaciones;

        //datos generales para crear una cotizacion
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
            "Placa" => $this->request->getPost("placa"),
        ];

        //agregar los cambios al registro en el crm
        $libreria->update("Quotes", $id, $registro);
        //alerta general cuando se edita una cotizacion en el crm

        $cliente = $this->request->getPost("nombre") . " " . $this->request->getPost("apellido");

        session()->setFlashdata('alerta', "¡Cotización, a nombre de $cliente, editada exitosamente!.");
        return redirect()->to(site_url("cotizaciones/buscar/Cotizando"));
    }

    public function emitir($id)
    {
        $libreria = new LibrariesCotizaciones;
        //obtener los datos de la cotizacion, la funcion es heredada de la libreria del api
        $cotizacion = $libreria->getRecord("Quotes", $id);

        if ($this->request->getPost()) {
            //obtener los datos del plan elegido
            foreach ($cotizacion->getLineItems() as $lineItem) {
                if ($this->request->getPost("planid") == $lineItem->getProduct()->getEntityId()) {
                    $total = round($lineItem->getNetTotal(), 2);
                    $planid = $lineItem->getProduct()->getEntityId();
                }
            }

            $cambios = [
                "Prima" => $total,
                "Coberturas" => $planid,
                "Quote_Stage" => "Emitida",
                "Valid_Till" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years")),
            ];

            $libreria->update("Quotes", $id, $cambios);

            //los archivos debe ser subida al servidor para luego ser adjuntados al registro
            if ($documentos = $this->request->getFiles()) {
                $libreria->adjuntar_documentos($documentos['documentos'], $id);
            }

            $cliente = $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido');
            $alerta = view("alertas/emitir_cotizacion", ["cliente" => $cliente]);
            session()->setFlashdata('alerta', $alerta);

            return redirect()->to(site_url("cotizaciones/buscar/Emitida"));
        }
    }

    public function adjuntar($id)
    {
        $libreria = new LibrariesCotizaciones;
        //los archivos debe ser subida al servidor para luego ser adjuntados al registro
        if ($documentos = $this->request->getFiles()) {
            $libreria->adjuntar_documentos($documentos['documentos'], $id);
            session()->setFlashdata('alerta', "¡Documentos adjuntados correctamente!.");
            return redirect()->to(site_url("cotizaciones/buscar/Emitida"));
        }
    }

    public function reportes()
    {
        $libreria = new Reportes;
        $ruta_reporte = $libreria->generar_reporte($this->request->getPost("plan"), $this->request->getPost("desde"), $this->request->getPost("hasta"));

        //si no encontro registros
        if (empty($ruta_reporte)) {
            session()->setFlashdata('alerta', 'No existen emisiones dentro del rango de tiempo.');
            return redirect()->to(site_url());
        }

        //forzar al navegador a descargar el archivo

        //funciona en ambos ambientes
        $nombre = "Reporte " . date("d-m-Y");
        return $this->response->download($ruta_reporte, null)->setFileName("$nombre.xlsx");;

        //no funciona en ambiente de produccion, solo en desarrollo local
        //es necesario no tener echo antes de descargar
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
