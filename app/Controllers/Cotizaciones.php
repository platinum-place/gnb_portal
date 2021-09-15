<?php

namespace App\Controllers;

use App\Libraries\Zoho;
use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\crm\crud\ZCRMInventoryLineItem;
use zcrmsdk\crm\crud\ZCRMTax;

class Cotizaciones extends BaseController
{
    protected $zoho;

    function __construct()
    {
        $this->zoho = new Zoho;
    }

    public function index()
    {
        $marcas = $this->zoho->getRecords("Marcas");
        asort($marcas);
        return view("cotizaciones/index", ["titulo" => "Cotizar", "marcas" => $marcas]);
    }

    public function buscar()
    {
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
        $cotizaciones = $this->zoho->searchRecordsByCriteria("Quotes", $criteria);
        return view("cotizaciones/buscar", ["titulo" => "Buscar Cotización", "cotizaciones" => $cotizaciones]);
    }

    //crea el registro en el crm, al ser un registro con una tabla de productos es necesario...
    //funciones del sdk relacionadas al inventario y impuestos
    protected function crear_cotizacion_api($registro, array $productos)
    {
        //inicializar el api
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Quotes");
        //inicializar el registro en blanco
        $records = array();
        $record = ZCRMRecord::getInstance("Quotes", null);
        //recorre los datos para crear un registro con los nombres de los campos a los valores que correspondan
        foreach ($registro as $campo => $valor) {
            $record->setFieldValue($campo, $valor);
        }
        //recorre los planes/productos al registro
        foreach ($productos as $producto) {
            $lineItem = ZCRMInventoryLineItem::getInstance(null);
            $lineItem->setListPrice($producto["prima"]);
            $lineItem->setDescription($producto["aseguradora"]);
            $taxInstance1 = ZCRMTax::getInstance("ITBIS 16");
            $taxInstance1->setPercentage(16);
            $taxInstance1->setValue(50);
            $lineItem->addLineTax($taxInstance1);
            $lineItem->setProduct(ZCRMRecord::getInstance("Products", $producto["planid"]));
            $lineItem->setQuantity(1);
            $record->addLineItem($lineItem);
        }
        array_push($records, $record);
        $responseIn = $moduleIns->createRecords($records);
        foreach ($responseIn->getEntityResponses() as $responseIns) {
            echo "HTTP Status Code:" . $responseIn->getHttpStatusCode();
            echo "Status:" . $responseIns->getStatus();
            echo "Message:" . $responseIns->getMessage();
            echo "Code:" . $responseIns->getCode();
            echo "Details:" . json_encode($responseIns->getDetails());
        }
    }

    public function crear()
    {
        //pasa la tabla de cotizacion en array para agregarla al registro
        $planes = json_decode($this->request->getPost("planes"), true);
        //datos generales para crear una cotizacion
        $registro = [
            "Subject" => "Cotización",
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
        //condiciones especificas
        switch ($this->request->getPost("tipo")) {
            case 'Vida':
                $vida = [
                    "Plazo" => $this->request->getPost("plazo")
                ];
                //actualiza el array general
                $registro = array_merge($registro, $vida);
                break;
        }
        //crea la cotizacion el en crm
        $this->crear_cotizacion_api($registro, $planes);
        //alerta general cuando se realiza una cotizacion en el crm
        $alerta = view("alertas/cotizacion_exitosa");
        session()->setFlashdata('alerta', $alerta);
        return redirect()->to(site_url("cotizaciones/buscar"));
    }

    public function mostrarModelos()
    {
        $pag = 1;
        $criterio = "Marca:equals:" . $this->request->getPost("marcaid");
        do {
            $lista_modelos =  $this->zoho->searchRecordsByCriteria("Modelos", $criterio, $pag);
            if ($lista_modelos) {
                $pag++;
                asort($lista_modelos);
                foreach ($lista_modelos as $modelo) {
                    echo '<option value="' . $modelo->getEntityId() . "," . $modelo->getFieldValue('Tipo') . '">' . strtoupper($modelo->getFieldValue('Name')) . '</option>';
                }
            } else {
                $pag = 0;
            }
        } while ($pag > 0);
    }

    public function cotizarAuto()
    {
        if ($this->request->getPost()) {
            switch ($this->request->getPost("cotizacion")) {
                case 'auto':
                    $edad_codeudor = 0;
                    $edad_deudor = $this->libreria->calcular_edad($this->request->getPost("deudor"));
                    if ($this->request->getPost("codeudor")) {
                        $edad_codeudor = $this->libreria->calcular_edad($this->request->getPost("codeudor"));
                    }
                    $tasas = $this->libreria->lista_tasas("Vida");
                    foreach ($tasas as $tasa) {
                        $prima = 0;
                        $comentario = "";
                        $comentario = $this->libreria->verificar_limites_deudor_codeudor(
                            $tasa,
                            $this->request->getPost("plazo"),
                            $this->request->getPost("suma"),
                            $edad_deudor,
                            $edad_codeudor
                        );
                        if (empty($comentario)) {
                            $prima = $this->libreria->calcular_prima_vida(
                                $tasa,
                                $this->request->getPost("suma"),
                                $edad_deudor,
                                $edad_codeudor
                            );
                        }
                        $cotizacion[] = [
                            "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                            "prima" => $prima,
                            "neta" => $prima * 0.16,
                            "total" => $prima * 1.16,
                            "suma" => $this->request->getPost("suma"),
                            "cotizacion" => $this->request->getPost("cotizacion"),
                            "comentario" => $comentario
                        ];
                    }
                    break;



                case 'desempleo':
                    $edad_codeudor = 0;
                    $edad_deudor = $this->libreria->calcular_edad($this->request->getPost("fecha"));
                    $tasas = $this->libreria->lista_tasas("Desempleo");
                    foreach ($tasas as $tasa) {
                        $prima = 0;
                        $comentario = "";
                        $comentario = $this->libreria->verificar_limites_deudor_codeudor(
                            $tasa,
                            $this->request->getPost("plazo"),
                            $this->request->getPost("suma"),
                            $edad_deudor
                        );
                        if (empty($comentario)) {
                            $prima = $this->libreria->calcular_prima_desempleo(
                                $tasa,
                                $this->request->getPost("suma"),
                                $this->request->getPost("cuota"),
                            );
                        }
                        $cotizacion[] = [
                            "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                            "prima" => $prima,
                            "neta" => $prima * 0.16,
                            "total" => $prima * 1.16,
                            "suma" => $this->request->getPost("suma"),
                            "cotizacion" => $this->request->getPost("cotizacion"),
                            "comentario" => $comentario
                        ];
                    }
                    break;

                case 'incendio':
                    $tasas = $this->libreria->lista_tasas("Incendio");
                    foreach ($tasas as $tasa) {
                        $prima = 0;
                        $comentario = "";
                        if (empty($comentario)) {
                            $prima = $this->libreria->calcular_prima_incendio($tasa, $this->request->getPost("suma"));
                        }
                        $cotizacion[] = [
                            "aseguradora" => $tasa->getFieldValue('Aseguradora')->getLookupLabel(),
                            "prima" => $prima,
                            "neta" => $prima * 0.16,
                            "total" => $prima * 1.16,
                            "suma" => $this->request->getPost("suma"),
                            "cotizacion" => $this->request->getPost("cotizacion"),
                            "comentario" => $comentario
                        ];
                    }
                    break;
            }
        }
        $planes = $this->auto->lista_planes("Auto");
        $modelo = explode(",", $this->request->getPost("modelo"));
        $modeloid = $modelo[0];
        $modelotipo = $modelo[1];
        $cotizacion = array();
        foreach ($planes as $plan) {
            $comentario = "";
            $prima = 0;
            if (in_array($this->request->getPost("uso"), $plan->getFieldValue('Restringir_veh_culos_de_uso'))) {
                $comentario = "Uso del vehículo restringido.";
            }
            $criterio = "((Marca:equals:" . $this->request->getPost("marca") . ") and (Plan:equals:" . $plan->getEntityId() . "))";
            $marcas = $this->auto->searchRecordsByCriteria("Restringidos", $criterio, 1, 200);
            foreach ((array)$marcas as $marca) {
                if (empty($marca->getFieldValue('Modelo'))) {
                    $comentario = "Marca restrigida.";
                }
            }
            $criterio = "((Modelo:equals:$modeloid) and (Plan:equals:" . $plan->getEntityId() . "))";
            $modelos = $this->auto->searchRecordsByCriteria("Restringidos", $criterio, 1, 200);
            foreach ((array)$modelos as $modelo) {
                $comentario = "Modelo restrigido.";
            }
            if (empty($comentario)) {
                $valortasa = 0;
                //encontrar la tasa
                $criterio = "Plan:equals:" . $plan->getEntityId();
                $tasas = $this->auto->searchRecordsByCriteria("Tasas", $criterio, 1, 200);
                foreach ($tasas as $tasa) {
                    if (in_array($modelotipo, $tasa->getFieldValue('Grupo_de_veh_culo')) and $tasa->getFieldValue('A_o') == $this->request->getPost("ano")) {
                        $valortasa = $tasa->getFieldValue('Name') / 100;
                    }
                }
                $valorrecargo = 0;
                //verificar si la aseguradora tiene algun recargo para la marca o modelo
                $recargos = $this->auto->lista_recargos($this->request->getPost("marca"), $plan->getFieldValue('Vendor_Name')->getEntityId());
                foreach ((array)$recargos as $recargo) {
                    if (
                        $modelotipo == $recargo->getFieldValue("Tipo")
                        and
                        $this->request->getPost("ano") >= $recargo->getFieldValue('Desde')
                        and
                        $this->request->getPost("ano") <= $recargo->getFieldValue('Hasta')
                    ) {
                        $valorrecargo = $recargo->getFieldValue('Name') / 100;
                    }
                }
                //calculo para cotizacion auto
                $prima = $this->request->getPost("suma") * ($valortasa + ($valortasa * $valorrecargo));
                echo  $prima;
                if ($prima > 0 and $prima < $plan->getFieldValue('Prima_m_nima')) {
                    $prima = $plan->getFieldValue('Prima_m_nima');
                }
                if ($this->request->getPost("plan") == "Mensual full") {
                    $prima = $prima / 12;
                }
                if ($prima == 0) {
                    $comentario = "No existen tasas para el año o tipo del vehículo.";
                }
            }
            $cotizacion[] = [
                "aseguradora" => $plan->getFieldValue('Vendor_Name')->getLookupLabel(),
                "planid" => $plan->getEntityId(),
                "prima" => $prima,
                "neta" => $prima * 0.16,
                "total" => $prima * 1.16,
                "suma" => $this->request->getPost("suma"),
                "cotizacion" => $this->request->getPost("cotizacion"),
                "comentario" => $comentario
            ];
        }
        return $cotizacion;
    }
}
