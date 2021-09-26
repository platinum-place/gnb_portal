<?php

namespace App\Controllers;

use App\Libraries\Auto as LibrariesAuto;
use App\Libraries\Cotizaciones;
use App\Models\Cotizacion;

class Auto extends BaseController
{
    //funcion post
    public function cotizar()
    {
        //libreria para cotizar
        $libreria = new LibrariesAuto;

        //modelo para cotizacion
        $cotizacion = new Cotizacion;

        //datos relacionados al modelo, dividios en un array
        $modelo = explode(",", $this->request->getPost("modelo"));

        //asignando valores al objeto
        $cotizacion->tipo = "auto";
        $cotizacion->modeloid = $modelo[0];
        $cotizacion->modelotipo = $modelo[1];
        $cotizacion->suma = $this->request->getPost("suma");
        $cotizacion->ano = $this->request->getPost("ano");
        $cotizacion->uso = $this->request->getPost("uso");
        $cotizacion->plan = $this->request->getPost("plan");
        $cotizacion->estado = $this->request->getPost("estado");
        $cotizacion->marcaid = $this->request->getPost("marca");

        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Product_Category:equals:Auto))";
        $planes =  $libreria->searchRecordsByCriteria("Products", $criterio);

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

        //alerta
        session()->setFlashdata('alerta', '¡Cotización creada exitosamente! Para descargar la cotización, haz clic en "Continuar" y completa el formulario.');

        //libreria del api para obtener todo los registros de un modulo, en este caso del de marcas
        $marcas = $libreria->getRecords("Marcas");

        //formatear el resultado para ordenarlo alfabeticamente en forma descendente
        asort($marcas);

        //vista principal
        return view("cotizaciones/index", ["titulo" => "Cotizar", "marcas" => $marcas, "cotizacion" => $cotizacion]);
    }

    public function completar()
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
            "Tipo" =>  "Auto",
            "Suma_asegurada" => $this->request->getPost("suma"),
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

        //libreria para cotizaciones
        $libreria = new Cotizaciones;

        //crea la cotizacion el en crm
        $id = $libreria->crear_cotizacion($registro, $planes);

        //alerta general cuando se realiza una cotizacion en el crm
        session()->setFlashdata('alerta', "¡Cotización completada exitosamente! A continuación, pues descargar, emitir o editar la cotización. Para emitir, descarga la cotización y los documentos asociados a la aseguradora elegida. Luego, adjunta todos los documentos necesarios al formulario. Por último, haz clic en “Emitir”. De no hacerlo, es posible retomar la cotización en otro momento. La cotización estara activa hasta " . $fecha_limite);

        //vista para emitir
        return redirect()->to(site_url("emisiones/emitir/$id"));
    }
}
