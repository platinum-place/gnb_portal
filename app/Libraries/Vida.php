<?php

namespace App\Libraries;

use App\Models\Cotizacion;
use App\Models\Reporte;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Vida extends Zoho
{
    protected function calcular_edad($fecha)
    {
        list($ano, $mes, $dia) = explode("-", $fecha);
        $ano_diferencia  = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia   = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
            $ano_diferencia--;
        return $ano_diferencia;
    }

    public function verificar_limites(Cotizacion $cotizacion, $plan)
    {
        //verificar limite de plazo
        if ($cotizacion->plazo > $plan->getFieldValue('Plazo_max')) {
            return "El plazo es mayor al limite establecido.";
        }

        //verificar limite suma
        if ($cotizacion->suma > $plan->getFieldValue('Suma_asegurada_max')) {
            return "La suma asegurada es mayor al limite establecido.";
        }
    }

    public function calcular_prima(Cotizacion $cotizacion, $plan)
    {
        //inicializar valores vacios
        $deudor = 0;
        $codeudor = 0;

        //encontrar la tasa
        $criterio = "Plan:equals:" . $plan->getEntityId();
        $tasas = $this->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

        foreach ((array)$tasas as $tasa) {
            //verificar limite de edad
            if (
                $this->calcular_edad($cotizacion->fecha_deudor) > $tasa->getFieldValue('Edad_min')
                and
                $this->calcular_edad($cotizacion->fecha_deudor) < $tasa->getFieldValue('Edad_max')
            ) {
                $deudor = $tasa->getFieldValue('Name') / 100;
            }

            if (!empty($cotizacion->fecha_codeudor)) {
                if (
                    $this->calcular_edad($cotizacion->fecha_codeudor) > $tasa->getFieldValue('Edad_min')
                    and
                    $this->calcular_edad($cotizacion->fecha_codeudor) < $tasa->getFieldValue('Edad_max')
                ) {
                    $codeudor = $tasa->getFieldValue('Name') / 100;
                }
            }
        }

        if ($deudor == 0) {
            return "La edad del deudor no esta dentro del limite permitido.";
        }

        //calcular prima un deudor
        $prima_deudor = ($cotizacion->suma / 1000) * $deudor;

        //calcular prima si existe un codeudor
        if (!empty($cotizacion->fecha_codeudor)) {
            if ($codeudor == 0) {
                return "La edad del codeudor no esta dentro del limite permitido.";
            }

            $prima_codeudor = ($cotizacion->suma / 1000) * ($codeudor - $deudor);
        } else {
            $prima_codeudor = 0;
        }

        //retornar la union de ambas primas
        return $prima_deudor + $prima_codeudor;
    }

    public function cotizar(Cotizacion $cotizacion)
    {
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Product_Category:equals:Vida))";
        $planes =  $this->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$planes as $plan) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones
            $comentario = $this->verificar_limites($cotizacion, $plan);

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular prima
                $prima = $this->calcular_prima($cotizacion, $plan);

                //en caso de haber algun problema
                if (is_string($prima)) {
                    $comentario = $prima;
                    $prima = 0;
                }
            }

            //lista con los resultados de cada calculo
            $cotizacion->planes[] = [
                "aseguradora" => $plan->getFieldValue('Vendor_Name')->getLookupLabel(),
                "aseguradoraid" => $plan->getFieldValue('Vendor_Name')->getEntityId(),
                "planid" => $plan->getEntityId(),
                "prima" => $prima - ($prima * 0.16),
                "neta" => $prima * 0.16,
                "total" => $prima,
                "suma" =>  $cotizacion->suma,
                "comentario" => $comentario
            ];
        }
    }

    public function generar_reporte(Reporte $reporte)
    {
        //iniciar las librerias de la api para generar excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add a drawing to the worksheet
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(FCPATH . 'img/nobe.png');
        $drawing->setCoordinates('A1');
        $drawing->setHeight(200);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        //celdas en negrita
        $sheet->getStyle('D1')->getFont()->setBold(true)->setName('Arial')->setSize(14);
        $sheet->getStyle('D2')->getFont()->setBold(true)->setName('Arial')->setSize(12);
        $sheet->getStyle('D4')->getFont()->setBold(true);
        $sheet->getStyle('D5')->getFont()->setBold(true);
        $sheet->getStyle('D6')->getFont()->setBold(true);
        $sheet->getStyle('D7')->getFont()->setBold(true);

        //titulos del reporte
        $sheet->setCellValue('D1', session("usuario")->getFieldValue("Account_Name")->getLookupLabel());
        $sheet->setCellValue('D2', 'EMISIONES PLAN VIDA');
        $sheet->setCellValue('D4', 'Generado por:');
        $sheet->setCellValue('E4', session("usuario")->getFieldValue("First_Name") . " " . session("usuario")->getFieldValue("Last_Name"));
        $sheet->setCellValue('D5', 'Desde:');
        $sheet->setCellValue('E5', $reporte->desde);
        $sheet->setCellValue('D6', 'Hasta:');
        $sheet->setCellValue('E6', $reporte->hasta);

        //titulos de las columnas de tabla
        $sheet->setCellValue('A12', 'Num');
        $sheet->setCellValue('B12', 'Referidor');
        $sheet->setCellValue('C12', 'Plazo');
        $sheet->setCellValue('D12', 'Plan');
        $sheet->setCellValue('E12', 'Aseguradora');
        $sheet->setCellValue('F12', 'Suma asegurada');
        $sheet->setCellValue('G12', 'Prima');
        $sheet->setCellValue('H12', 'Deudor');
        $sheet->setCellValue('I12', 'RNC/Cédula');
        $sheet->setCellValue('J12', 'Tel. Residencia');
        $sheet->setCellValue('K12', 'Fecha de nacimiento');
        $sheet->setCellValue('L12', 'Dirección');
        $sheet->setCellValue('M12', 'Aplica Codeudor');

        //inicializar contadores
        $cont = 1;
        $pos = 13;

        foreach ($reporte->emisiones as $emisiones => $emision) {
            if (
                date("Y-m-d", strtotime($emision->getFieldValue('Fecha_de_inicio'))) >= $reporte->desde
                and
                date("Y-m-d", strtotime($emision->getFieldValue('Fecha_de_inicio'))) <= $reporte->hasta
            ) {
                //obtener los datos del plan
                $plan = $this->getRecord("Products", $emision->getFieldValue("Coberturas")->getEntityId());

                //valores de la tabla
                $sheet->setCellValue('A' . $pos, $cont);
                $sheet->setCellValue('B' . $pos, $emision->getFieldValue('Contact_Name')->getLookupLabel());
                $sheet->setCellValue('C' . $pos, $emision->getFieldValue('Plazo'));
                $sheet->setCellValue('D' . $pos, $emision->getFieldValue('Plan'));
                $sheet->setCellValue('E' . $pos, $plan->getFieldValue('Vendor_Name')->getLookupLabel());
                $sheet->setCellValue('F' . $pos, $emision->getFieldValue('Suma_asegurada'));
                $sheet->setCellValue('G' . $pos, $emision->getFieldValue('Amount'));

                //valores relacionados al deudor
                $deudor = $this->getRecord("Leads", $emision->getFieldValue("Cliente")->getEntityId());
                $sheet->setCellValue('H' . $pos, $deudor->getFieldValue('First_Name') . " " . $deudor->getFieldValue('Last_Name'));
                $sheet->setCellValue('I' . $pos, $deudor->getFieldValue('RNC_C_dula'));
                $sheet->setCellValue('J' . $pos, $deudor->getFieldValue('Mobile'));
                $sheet->setCellValue('K' . $pos, $deudor->getFieldValue('Fecha_de_nacimiento'));
                $sheet->setCellValue('L' . $pos, $deudor->getFieldValue('Street'));

                if (!empty($emision->getFieldValue("Codeudor"))) {
                    $sheet->setCellValue('M' . $pos, "Si");
                } else {
                    $sheet->setCellValue('M' . $pos, "No");
                }

                //contadores
                $cont++;
                $pos++;
            }
        }

        //cambiar el color de fondo de un rango de celdas
        $spreadsheet
            ->getActiveSheet()
            ->getStyle('A12:M12')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('004F97');

        //cambiar el color de fuente de un rango de celdas
        $spreadsheet->getActiveSheet()
            ->getStyle('A12:M12')
            ->getFont()
            ->getColor()
            ->setARGB("FFFFFF");

        //ajustar tamaño de las columnas
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(30);

        //ruta del excel
        $doc = WRITEPATH . 'uploads/reporte.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($doc);

        return $doc;
    }
}
