<?php

namespace App\Controllers;

use App\Libraries\Cotizaciones as LibrariesCotizaciones;
use App\Libraries\CotizacionesAuto;
use App\Models\Cotizacion;

class Cotizaciones extends BaseController
{
    public function index()
    {
        $cotizaciones = new LibrariesCotizaciones;
        return view("cotizaciones/index", [
            "titulo" => "Cotizar",
            "marcas" => $cotizaciones->lista_marcas()
        ]);
    }

    //funcion post
    public function mostrarModelos()
    {
        $cotizaciones = new LibrariesCotizaciones;
        $pag = 1;
        do {
            $modelos = $cotizaciones->lista_modelos($this->request->getPost("marcaid"), $pag);
            if (!empty($modelos)) {
                asort($modelos);
                $pag++;
                foreach ($modelos as $modelo) {
                    echo '<option value="' . $modelo->getEntityId() . "," . $modelo->getFieldValue('Tipo') . '">' . strtoupper($modelo->getFieldValue('Name')) . '</option>';
                }
            } else {
                $pag = 0;
            }
        } while ($pag > 0);
    }

    //funcion post
    public function cotizarAuto()
    {
        //datos relacionados al modelo, dividios en un array
        $modelo = explode(",", $this->request->getPost("modelo"));
        //libreria para cotizar auto
        $libreria = new CotizacionesAuto;
        //modelo para cotizacion
        $cotizacion = new Cotizacion;
        //asignando valores al objeto
        $cotizacion->tipo = "Auto";
        $cotizacion->modeloid = $modelo[0];
        $cotizacion->modelotipo = $modelo[1];
        $cotizacion->suma = $this->request->getPost("suma");
        $cotizacion->ano = $this->request->getPost("ano");
        $cotizacion->uso = $this->request->getPost("uso");
        $cotizacion->plan = $this->request->getPost("plan");
        $cotizacion->estado = $this->request->getPost("estado");
        $cotizacion->marcaid = $this->request->getPost("marca");
        //planes relacionados al banco
        $planes = $libreria->lista_planes("Auto");
        foreach ($planes as $plan) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;
            //verificaciones
            $comentario = $libreria->verificar_limites($cotizacion, $plan);
            $comentario = $libreria->verificar_restringido($cotizacion, $plan);
            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular tasa
                $tasa = $libreria->calcular_tasa($cotizacion, $plan);
                //calcular recargo
                $recargo = $libreria->calcular_recargo($cotizacion, $plan);
                //calcular prima
                $prima = $libreria->calcular_prima($cotizacion, $tasa, $recargo);
                //si el valor de la prima es muy bajo
                if ($prima > 0 and $prima < $plan->getFieldValue('Prima_m_nima')) {
                    $prima = $plan->getFieldValue('Prima_m_nima');
                }
                //en caso de ser mensual
                if ($cotizacion->plan == "Mensual full") {
                    $prima = $prima / 12;
                }
                //en caso de haber algun problema
                if ($prima == 0) {
                    $comentario = "No existen tasas para el año o tipo del vehículo.";
                }
            }
            //lista con los resultados de cada calculo
            $cotizacion->planes[] = [
                "aseguradora" => $plan->getFieldValue('Vendor_Name')->getLookupLabel(),
                "aseguradoraid" => $plan->getFieldValue('Vendor_Name')->getEntityId(),
                "planid" => $plan->getEntityId(),
                "prima" => round($prima - ($prima * 0.16)),
                "neta" => round($prima * 0.16),
                "total" => round($prima),
                "suma" =>  $cotizacion->suma,
                "comentario" => $comentario
            ];
        }
        session()->setFlashdata('alerta', '¡Cotización creada exitosamente! Para descargar la cotización, haz clic en "Continuar" y completa el formulario.');
        //valores de la vista, en caso de querer hacer otra cotizacion
        $marcas = $libreria->getRecords("Marcas");
        asort($marcas);
        return view("cotizaciones/index", ["titulo" => "Cotizar", "marcas" => $marcas, "cotizacion" => $cotizacion]);
    }

    public function cotizar()
    {
        //pasa la tabla de cotizacion en array para agregarla al registro
        $planes = json_decode($this->request->getPost("planes"), true);
        //datos generales para crear una cotizacion
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
            "Quote_Stage" => "Negociación",
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
        session()->setFlashdata('alerta', "¡Cotización completada exitosamente! Descarga la cotización y los documentos asociados a la aseguradora elegida. Luego, adjunta todos los documentos necesarios al formulario. A continuación, haz clic en “Emitir”.");
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
                    $criteria = "((Quote_Number:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;
            }
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
        $cotizacion = $libreria->getRecord("Quotes", $id);
        switch ($cotizacion->getFieldValue("Tipo")) {
            case 'Vida':
                return view('cotizaciones/vida', ["cotizacion" => $cotizacion, "libreria" => $libreria]);
                break;

            case 'Auto':
                return view('cotizaciones/auto', ["cotizacion" => $cotizacion, "libreria" => $libreria]);
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
}
