<?php

namespace App\Controllers;

use App\Libraries\Auto;
use App\Libraries\Cotizaciones as LibrariesCotizaciones;
use App\Libraries\Desempleo;
use App\Libraries\Incendio;
use App\Libraries\Reportes;
use App\Libraries\Vida;
use App\Models\Cotizacion;

class Cotizaciones extends BaseController
{
    public function index()
    {
        $cotizacion = new Cotizacion;
        if ($this->request->getPost()) {
            switch ($this->request->getPost("plan")) {
                case 'Seguro Incendio Hipotecario':
                    $libreria = new Incendio;
                    $planes = $libreria->cotizar(
                        $this->request->getPost("suma"),
                    );
                    $cotizacion->incendio(
                        $this->request->getPost("suma"),
                        $this->request->getPost("prestamo"),
                        $this->request->getPost("plazo"),
                        $this->request->getPost("riesgo"),
                        $this->request->getPost("construccion"),
                        $this->request->getPost("direccion"),
                        $this->request->getPost("plan"),
                        $planes
                    );
                    break;

                case 'Vida/Desempleo':
                    $libreria = new Desempleo;
                    $planes = $libreria->cotizar(
                        $this->request->getPost("deudor"),
                        $this->request->getPost("suma"),
                        $this->request->getPost("cuota"),
                        $this->request->getPost("plazo")
                    );
                    $cotizacion->desempleo(
                        $this->request->getPost("deudor"),
                        $this->request->getPost("cuota"),
                        $this->request->getPost("plazo"),
                        $this->request->getPost("suma"),
                        $this->request->getPost("plan"),
                        $planes
                    );
                    break;

                case 'Vida':
                    $libreria = new Vida;
                    $planes = $libreria->cotizar(
                        $this->request->getPost("suma"),
                        $this->request->getPost("plazo"),
                        $this->request->getPost("deudor"),
                        $this->request->getPost("codeudor")
                    );
                    $cotizacion->vida(
                        $this->request->getPost("deudor"),
                        $this->request->getPost("codeudor"),
                        $this->request->getPost("plazo"),
                        $this->request->getPost("suma"),
                        $this->request->getPost("plan"),
                        $planes
                    );
                    break;

                default:
                    $libreria = new Auto;
                    //datos relacionados al modelo, dividios en un array
                    $modelo = explode(",", $this->request->getPost("modelo"));
                    //asignando valores al objeto
                    $modeloid = $modelo[0];
                    $modelotipo = $modelo[1];
                    $planes = $libreria->cotizar(
                        $this->request->getPost("uso"),
                        $this->request->getPost("suma"),
                        $this->request->getPost("ano"),
                        $this->request->getPost("marca"),
                        $modeloid,
                        $modelotipo,
                        $this->request->getPost("plan"),
                    );
                    $cotizacion->auto(
                        $this->request->getPost("marca"),
                        $modeloid,
                        $modelotipo,
                        $this->request->getPost("plan"),
                        $this->request->getPost("ano"),
                        $this->request->getPost("uso"),
                        $this->request->getPost("estado"),
                        $this->request->getPost("suma"),
                        $planes
                    );
                    break;
            }
            if (empty($planes)) {
                session()->setFlashdata('alerta', 'No existen planes para cotizar.');
            }
        }
        $libreria = new LibrariesCotizaciones;
        //libreria del api para obtener todo los registros de un modulo, en este caso del de marcas
        $marcas = $libreria->getRecords("Marcas");
        //formatear el resultado para ordenarlo alfabeticamente en forma descendente
        asort($marcas);
        return view("cotizar", ["titulo" => "Cotizar", "marcas" => $marcas, "cotizacion" => $cotizacion]);
    }

    public function lista_modelos()
    {
        //inicializar el contador
        $pag = 1;
        $libreria = new LibrariesCotizaciones;
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

    //funcion post
    public function completar()
    {
        //pasa la tabla de cotizacion en array para agregarla al registro
        $planes = json_decode($this->request->getPost("planes"), true);
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
        $id = $libreria->crear_cotizacion($registro, $planes);
        $alerta = view("alertas/completar_cotizacion");
        session()->setFlashdata('alerta', $alerta);
        return redirect()->to(site_url("cotizaciones/emitir/$id"));
    }

    public function buscar($filtro)
    {
        $libreria = new LibrariesCotizaciones;
        $cotizaciones = $libreria->lista_cotizaciones();
        switch ($filtro) {
            case 'Emitida':
                $titulo = "Buscar Emisiones";
                break;

            default:
                $titulo = "Buscar Cotizaciones";
                break;
        }
        return view("buscar", ["titulo" => $titulo, "cotizaciones" => $cotizaciones, "filtro" => $filtro]);
    }

    public function editar($id)
    {
        $libreria = new LibrariesCotizaciones;
        //obtener datos de la cotizacion
        $cotizacion = $libreria->getRecord("Quotes", $id);
        if ($this->request->getPost()) {
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
            session()->setFlashdata('alerta', "¡Cotización editada exitosamente!.");
            return redirect()->to(site_url("cotizaciones/emitir/$id"));
        }
        return view("editar", [
            "titulo" => "Editar Cotización, a nombre de " . $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido'),
            "cotizacion" => $cotizacion
        ]);
    }

    public function emitir($id)
    {
        $libreria = new LibrariesCotizaciones;
        
        //obtener los datos de la cotizacion, la funcion es heredada de la libreria del api
        $cotizacion = $libreria->getRecord("Quotes", $id);

        $cliente =  $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido');

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

            $alerta = view("alertas/emitir_cotizacion");
            session()->setFlashdata('alerta', $alerta);
            return redirect()->to(site_url("cotizaciones/buscar/Emitida"));
        }
        return view("emitir", [
            "titulo" => "Emitir Cotización, a nombre de $cliente",
            "cotizacion" => $cotizacion
        ]);
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

    public function adjuntar($id)
    {
        $libreria = new LibrariesCotizaciones;
        $cotizacion = $libreria->getRecord("Quotes", $id);
        //los archivos debe ser subida al servidor para luego ser adjuntados al registro
        if ($documentos = $this->request->getFiles()) {
            $cantidad= $libreria->adjuntar_documentos($documentos['documentos'], $id);
            session()->setFlashdata('alerta', "Documentos adjuntados: $cantidad, en la emisión a nombre de " . $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido'));
            return redirect()->to(site_url("cotizaciones/buscar/Emitida"));
        }
        return view("adjuntar", [
            "titulo" => "Adjuntar a emisión, a nombre de " . $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido'),
            "cotizacion" => $cotizacion
        ]);
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
