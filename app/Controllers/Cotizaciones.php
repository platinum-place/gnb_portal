<?php

namespace App\Controllers;

use App\Libraries\Cotizaciones as LibrariesCotizaciones;
use App\Models\Cotizacion;

class Cotizaciones extends BaseController
{
    protected $libreria;

    function __construct()
    {
        $this->libreria = new LibrariesCotizaciones;
    }

    public function index()
    {
        $cotizacion = array();
        if ($this->request->getPost()) {
            switch ($this->request->getPost("cotizacion")) {
                case 'auto':
                    //
                    break;

                case 'vida':
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
        $marcas = $this->libreria->lista_marcas();
        asort($marcas);
        return view("cotizaciones/index", ["titulo" => "Cotizar", "marcas" => $marcas, "cotizacion" => $cotizacion]);
    }

    public function mostrarModelos()
    {
        $pag = 1;
        do {
            $lista_modelos = $this->libreria->lista_modelos($this->request->getPost("marcaid"), $pag);
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
