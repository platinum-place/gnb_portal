<?php

namespace App\Controllers;

use App\Libraries\Cotizaciones as LibrariesCotizaciones;

class Cotizaciones extends BaseController
{
    public function index()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $libreria = new LibrariesCotizaciones;
        //libreria del api para obtener todo los registros de un modulo, en este caso del de marcas
        $marcas = $libreria->getRecords("Marcas");
        //formatear el resultado para ordenarlo alfabeticamente en forma descendente
        asort($marcas);
        return view("cotizaciones/index", ["titulo" => "Cotizar", "marcas" => $marcas]);
    }

    //funcion post
    public function mostrarModelos()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $libreria = new LibrariesCotizaciones;

        //inicializar el contador
        $pag = 1;

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
        //pasa la tabla de cotizacion en array para agregarla al registro
        $planes = json_decode($this->request->getPost("planes"), true);
        //datos generales para crear una cotizacion
        $fecha_limite = date("Y-m-d", strtotime(date("Y-m-d") . "+ 10 days"));
        $registro = [
            "Subject" => "Cotización",
            "Valid_Till" => $fecha_limite,
            "Account_Name" =>  session('usuario')->getFieldValue("Account_Name")->getEntityId(),
            "Contact_Name" =>  session('usuario')->getEntityId(),
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
        ];
        //en caso de haber un codeudor
        if ($this->request->getPost("nombre_codeudor")) {
            $codeudor = [
                "Nombre_codeudor" => $this->request->getPost("nombre_codeudor"),
                "Apellido_codeudor" => $this->request->getPost("apellido_codeudor"),
                "Tel_Celular_codeudor" => $this->request->getPost("telefono_codeudor"),
                "Tel_Residencia_codeudor" => $this->request->getPost("tel_residencia_codeudor"),
                "Tel_Trabajo_codeudor" => $this->request->getPost("tel_trabajo_codeudor"),
                "RNC_C_dula_codeudor" => $this->request->getPost("rnc_cedula_codeudor"),
                "Fecha_de_nacimiento_codeudor" => $this->request->getPost("fecha_nacimiento_codeudor"),
                "Direcci_n_codeudor" => $this->request->getPost("direccion_codeudor"),
                "Correo_electr_nico_codeudor" => $this->request->getPost("correo_codeudor")
            ];
            //actualiza el array general
            $registro = array_merge($registro, $codeudor);
        }
        //en caso de haber un vehiculo
        if ($this->request->getPost("chasis")) {
            $vehiculo = [
                "A_o" => $this->request->getPost("ano"),
                "Marca" => $this->request->getPost("marcaid"),
                "Modelo" => $this->request->getPost("modeloid"),
                "Uso" => $this->request->getPost("uso"),
                "Tipo_veh_culo" => $this->request->getPost("modelotipo"),
                "Chasis" => $this->request->getPost("chasis"),
                "Color" => $this->request->getPost("color"),
                "Placa" => $this->request->getPost("placa"),
                "Condiciones" => $this->request->getPost("estado")
            ];
            //actualiza el array general
            $registro = array_merge($registro, $vehiculo);
        }
        //en caso de vida
        if ($this->request->getPost("plazo")) {
            $vida = [
                "Plazo" => $this->request->getPost("plazo")
            ];
            //actualiza el array general
            $registro = array_merge($registro, $vida);
        }
        //libreria para cotizaciones
        $libreria = new LibrariesCotizaciones;
        //crea la cotizacion el en crm
        $id = $libreria->crear_cotizacion($registro, $planes);
        //alerta general cuando se realiza una cotizacion en el crm
        session()->setFlashdata('alerta', "¡Cotización completada exitosamente! A continuación, pues descargar, emitir o editar la cotización. Para emitir, descarga la cotización y los documentos asociados a la aseguradora elegida. Luego, adjunta todos los documentos necesarios al formulario. Por último, haz clic en “Emitir”. De no hacerlo, es posible retomar la cotización en otro momento. La cotización estara activa hasta " . $fecha_limite);
        return redirect()->to(site_url("emisiones/emitir/$id"));
    }

    public function buscar()
    {
        //libreria para cotizaciones
        $libreria = new LibrariesCotizaciones;

        if (session('usuario')->getFieldValue("Title") == "Administrador") {
            $criteria = "Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId();
        } else {
            $criteria = "((Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . ") and (Contact_Name:equals:" . session('usuario')->getEntityId() . "))";
        }

        //lista de todas las cotizaciones
        $cotizaciones = $libreria->searchRecordsByCriteria("Quotes", $criteria);
        return view("cotizaciones/buscar", ["titulo" => "Buscar Cotización", "cotizaciones" => $cotizaciones]);
    }

    public function descargar($id)
    {
        //libreria para cotizaciones
        $libreria = new LibrariesCotizaciones;
        
        //obtener datos de la cotizacion
        $detalles = $libreria->getRecord("Quotes", $id);

        switch ($detalles->getFieldValue("Tipo")) {
            case 'Vida':
                return view('cotizaciones/vida', ["detalles" => $detalles, "libreria" => $libreria]);
                break;

            case 'Auto':
                return view('cotizaciones/auto', ["detalles" => $detalles, "libreria" => $libreria]);
                break;
        }
    }

    public function documentos($id)
    {
        //libreria para cotizaciones
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
        //libreria para cotizaciones
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
            ];

            //en caso de haber un codeudor
            if ($this->request->getPost("nombre_codeudor")) {
                $codeudor = [
                    "Nombre_codeudor" => $this->request->getPost("nombre_codeudor"),
                    "Apellido_codeudor" => $this->request->getPost("apellido_codeudor"),
                    "Tel_Celular_codeudor" => $this->request->getPost("telefono_codeudor"),
                    "Tel_Residencia_codeudor" => $this->request->getPost("tel_residencia_codeudor"),
                    "Tel_Trabajo_codeudor" => $this->request->getPost("tel_trabajo_codeudor"),
                    "RNC_C_dula_codeudor" => $this->request->getPost("rnc_cedula_codeudor"),
                    "Direcci_n_codeudor" => $this->request->getPost("direccion_codeudor"),
                    "Correo_electr_nico_codeudor" => $this->request->getPost("correo_codeudor")
                ];
                
                //actualiza el array general
                $registro = array_merge($registro, $codeudor);
            }

            //en caso de haber un vehiculo
            if ($this->request->getPost("chasis")) {
                $vehiculo = [
                    "Chasis" => $this->request->getPost("chasis"),
                    "Color" => $this->request->getPost("color"),
                    "Placa" => $this->request->getPost("placa"),
                ];

                //actualiza el array general
                $registro = array_merge($registro, $vehiculo);
            }

            //agregar los cambios al registro en el crm
            $libreria->update("Quotes", $id, $registro);
            //alerta general cuando se edita una cotizacion en el crm
            session()->setFlashdata('alerta', "¡Cotización No. " . $cotizacion->getFieldValue('Quote_Number') . " editada exitosamente!.");
            
            return redirect()->to(site_url("emisiones/emitir/$id"));
        }

        return view("cotizaciones/editar", [
            "titulo" => "Editar Cotización No. " . $cotizacion->getFieldValue('Quote_Number'),
            "cotizacion" => $cotizacion
        ]);
    }
}
