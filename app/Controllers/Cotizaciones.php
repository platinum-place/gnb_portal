<?php

namespace App\Controllers;

use App\Libraries\Auto;
use App\Libraries\Cotizaciones as LibrariesCotizaciones;
use App\Libraries\Desempleo;
use App\Libraries\Incendio;
use App\Libraries\Vida;
use App\Libraries\Zoho;
use App\Models\Cotizacion;

class Cotizaciones extends BaseController
{
    public function index()
    {
        return view("cotizaciones/index", ["titulo" => "Cotizar"]);
    }

    public function buscar()
    {
        $libreria = new LibrariesCotizaciones;
        $cotizaciones = $libreria->lista();
        return view("cotizaciones/buscar", ["titulo" => "Buscar Cotización", "cotizaciones" => $cotizaciones]);
    }

    public function descargar($id)
    {
        $libreria = new Zoho;

        //obtener datos de la cotizacion
        $detalles = $libreria->getRecord("Quotes", $id);

        switch ($detalles->getFieldValue("Tipo")) {
            case 'Vida':
                return view('cotizaciones/descargar_vida', ["detalles" => $detalles, "libreria" => $libreria]);
                break;

            case 'Auto':
                return view('cotizaciones/descargar_auto', ["detalles" => $detalles, "libreria" => $libreria]);
                break;

            case 'Desempleo':
                return view('cotizaciones/descargar_desempleo', ["detalles" => $detalles, "libreria" => $libreria]);
                break;

            case 'Incendio':
                return view('cotizaciones/descargar_incendio', ["detalles" => $detalles, "libreria" => $libreria]);
                break;
        }
    }

    public function documentos($id)
    {
        $libreria = new Zoho;

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
        $libreria = new Zoho;

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
            session()->setFlashdata('alerta', "¡Cotización No. " . $cotizacion->getFieldValue('Quote_Number') . " editada exitosamente!.");

            return redirect()->to(site_url("emisiones/emitir/$id"));
        }

        return view("cotizaciones/editar", [
            "titulo" => "Editar Cotización No. " . $cotizacion->getFieldValue('Quote_Number'),
            "cotizacion" => $cotizacion
        ]);
    }

    //funcion post
    public function completar()
    {
        //pasa la tabla de cotizacion en array para agregarla al registro
        $planes = json_decode($this->request->getPost("planes"), true);

        $registro = [
            "Subject" => "Cotización",
            "Valid_Till" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 10 days")),
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
            "Plazo" => $this->request->getPost("plazo"),
            "Cuota" => $this->request->getPost("cuota"),
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

        $libreria = new LibrariesCotizaciones;
        //crea la cotizacion el en crm
        $id = $libreria->crear_cotizacion($registro, $planes);

        //alerta general cuando se realiza una cotizacion en el crm
        session()->setFlashdata('alerta', "¡Cotización completada exitosamente! A continuación, pues descargar, emitir o editar la cotización. Para emitir, descarga la cotización y los documentos asociados a la aseguradora elegida. Luego, adjunta todos los documentos necesarios al formulario. Por último, haz clic en “Emitir”. De no hacerlo, es posible retomar la cotización en otro momento.");
        //vista para emitir
        return redirect()->to(site_url("emisiones/emitir/$id"));
    }

    //funcion post
    public function mostrarModelos()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $libreria = new Zoho;

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

    public function cotizar_auto()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $libreria = new Auto;
        //libreria del api para obtener todo los registros de un modulo, en este caso del de marcas
        $marcas = $libreria->getRecords("Marcas");
        //formatear el resultado para ordenarlo alfabeticamente en forma descendente
        asort($marcas);

        if ($this->request->getPost()) {
            //modelo para cotizacion
            $cotizacion = new Cotizacion;

            $cotizacion->suma = $this->request->getPost("suma");
            $cotizacion->ano = $this->request->getPost("ano");
            $cotizacion->uso = $this->request->getPost("uso");
            $cotizacion->plan = $this->request->getPost("plan");
            $cotizacion->estado = $this->request->getPost("estado");
            $cotizacion->marcaid = $this->request->getPost("marca");
            $cotizacion->tipo = "Auto";

            //datos relacionados al modelo, dividios en un array
            $modelo = explode(",", $this->request->getPost("modelo"));

            //asignando valores al objeto
            $cotizacion->modeloid = $modelo[0];
            $cotizacion->modelotipo = $modelo[1];

            //cotizar en libreria
            $libreria->cotizar($cotizacion);

            if (!empty($cotizacion->planes)) {
                session()->setFlashdata('alerta', '¡Cotización creada exitosamente! Para descargar la cotización, haz clic en "Continuar" y completa el formulario.');
            } else {
                session()->setFlashdata('alerta', 'No existen planes para cotizar.');
            }

            return view("cotizaciones/cotizar_auto", ["titulo" => "Cotización de Plan Auto", "marcas" => $marcas, "cotizacion" => $cotizacion]);
        }

        return view("cotizaciones/cotizar_auto", ["titulo" => "Cotización de Plan Auto", "marcas" => $marcas]);
    }

    public function cotizar_incendio()
    {
        if ($this->request->getPost()) {
            //modelo para cotizacion
            $cotizacion = new Cotizacion;

            $cotizacion->suma = $this->request->getPost("suma");
            $cotizacion->plazo = $this->request->getPost("plazo");
            $cotizacion->tipo = "Incendio";
            $cotizacion->plan = "Incendio Hipotecario";
            $cotizacion->cuota = $this->request->getPost("cuota");

            //cargar la libreria para hacer uso de una funcion de la api
            $libreria = new Incendio;

            //cotizar en libreria
            $libreria->cotizar($cotizacion);

            if (!empty($cotizacion->planes)) {
                session()->setFlashdata('alerta', '¡Cotización creada exitosamente! Para descargar la cotización, haz clic en "Continuar" y completa el formulario.');
            } else {
                session()->setFlashdata('alerta', 'No existen planes para cotizar.');
            }

            return view("cotizaciones/cotizar_incendio", ["titulo" => "Cotización de Seguro Incendio Hipotecario", "cotizacion" => $cotizacion]);
        }

        return view("cotizaciones/cotizar_incendio", ["titulo" => "Cotización de Seguro Incendio Hipotecario"]);
    }

    public function cotizar_desempleo()
    {
        if ($this->request->getPost()) {
            //modelo para cotizacion
            $cotizacion = new Cotizacion;

            $cotizacion->suma = $this->request->getPost("suma");
            $cotizacion->plazo = $this->request->getPost("plazo");
            $cotizacion->tipo = "Desempleo";
            $cotizacion->plan = "Vida/Desempleo";
            $cotizacion->fecha_deudor = $this->request->getPost("deudor");
            $cotizacion->cuota = $this->request->getPost("cuota");

            //cargar la libreria para hacer uso de una funcion de la api
            $libreria = new Desempleo;

            //cotizar en libreria
            $libreria->cotizar($cotizacion);

            if (!empty($cotizacion->planes)) {
                session()->setFlashdata('alerta', '¡Cotización creada exitosamente! Para descargar la cotización, haz clic en "Continuar" y completa el formulario.');
            } else {
                session()->setFlashdata('alerta', 'No existen planes para cotizar.');
            }

            return view("cotizaciones/cotizar_desempleo", ["titulo" => "Cotización de Plan Vida/Desempleo", "cotizacion" => $cotizacion]);
        }

        return view("cotizaciones/cotizar_desempleo", ["titulo" => "Cotización de Plan Vida/Desempleo"]);
    }

    public function cotizar_vida()
    {
        if ($this->request->getPost()) {
            //modelo para cotizacion
            $cotizacion = new Cotizacion;

            $cotizacion->suma = $this->request->getPost("suma");
            $cotizacion->plazo = $this->request->getPost("plazo");
            $cotizacion->tipo = "Vida";
            $cotizacion->plan = "Vida";
            $cotizacion->fecha_deudor = $this->request->getPost("deudor");
            $cotizacion->fecha_codeudor = $this->request->getPost("codeudor");

            //cargar la libreria para hacer uso de una funcion de la api
            $libreria = new Vida;

            //cotizar en libreria
            $libreria->cotizar($cotizacion);

            if (!empty($cotizacion->planes)) {
                session()->setFlashdata('alerta', '¡Cotización creada exitosamente! Para descargar la cotización, haz clic en "Continuar" y completa el formulario.');
            } else {
                session()->setFlashdata('alerta', 'No existen planes para cotizar.');
            }

            return view("cotizaciones/cotizar_vida", ["titulo" => "Cotización de Plan Vida", "cotizacion" => $cotizacion]);
        }

        return view("cotizaciones/cotizar_vida", ["titulo" => "Cotización de Plan Vida"]);
    }
}
