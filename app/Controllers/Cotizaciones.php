<?php

namespace App\Controllers;

use App\Libraries\Cotizaciones as LibrariesCotizaciones;

class Cotizaciones extends BaseController
{
    protected $libreria;

    function __construct()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $this->libreria = new LibrariesCotizaciones;
    }

    public function index()
    {
        return view("cotizaciones/index", ["titulo" => "Cotizar"]);
    }

    public function buscar()
    {
        $cotizaciones = $this->libreria->lista();
        return view("cotizaciones/buscar", ["titulo" => "Buscar Cotización", "cotizaciones" => $cotizaciones]);
    }

    public function descargar($id)
    {
        //obtener datos de la cotizacion
        $detalles = $this->libreria->getRecord("Quotes", $id);

        switch ($detalles->getFieldValue("Tipo")) {
            case 'Vida':
                return view('vida/descargar', ["detalles" => $detalles, "libreria" => $this->libreria]);
                break;

            case 'Auto':
                return view('auto/descargar', ["detalles" => $detalles, "libreria" => $this->libreria]);
                break;

            case 'Desempleo':
                return view('desempleo/descargar', ["detalles" => $detalles, "libreria" => $this->libreria]);
                break;

            case 'Incendio':
                return view('incendio/descargar', ["detalles" => $detalles, "libreria" => $this->libreria]);
                break;
        }
    }

    public function editar($id)
    {
        //obtener datos de la cotizacion
        $cotizacion = $this->libreria->getRecord("Quotes", $id);

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
                "Tipo_crm" => $this->request->getPost("tipo_crm"),
            ];

            //agregar los cambios al registro en el crm
            $this->libreria->update("Quotes", $id, $registro);
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

        //crea la cotizacion el en crm
        $id = $this->libreria->crear_cotizacion($registro, $planes);

        //alerta general cuando se realiza una cotizacion en el crm
        session()->setFlashdata('alerta', "¡Cotización completada exitosamente! A continuación, pues descargar, emitir o editar la cotización. Para emitir, descarga la cotización y los documentos asociados a la aseguradora elegida. Luego, adjunta todos los documentos necesarios al formulario. Por último, haz clic en “Emitir”. De no hacerlo, es posible retomar la cotización en otro momento.");
        //vista para emitir
        return redirect()->to(site_url("emisiones/emitir/$id"));
    }
}
