<?php

namespace App\Libraries;

use App\Models\Cotizacion;
use App\Models\Reporte;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Auto extends Zoho
{
    public function verificar_limites(Cotizacion $cotizacion, $plan)
    {
        //verificar limites de uso
        if (in_array($cotizacion->uso, $plan->getFieldValue('Restringir_veh_culos_de_uso'))) {
            return "Uso del vehículo restringido.";
        }

        //verificar suma
        if (
            $cotizacion->suma < $plan->getFieldValue('Suma_asegurada_min')
            and
            $cotizacion->suma > $plan->getFieldValue('Suma_asegurada_max')
        ) {
            return "La suma asegurada no esta dentro de los limites.";
        }

        //verificar antiguedad
        if ((date("Y") - $cotizacion->ano) > $plan->getFieldValue('Max_antig_edad')) {
            return "La antigüedad del vehículo es mayor al limite establecido.";
        }

        $criterio = "((Marca:equals:" . $cotizacion->marcaid . ") and (Aseguradora:equals:" . $plan->getFieldValue('Vendor_Name')->getEntityId() . "))";
        $marcas = $this->searchRecordsByCriteria("Restringidos", $criterio, 1, 200);

        foreach ((array)$marcas as $marca) {
            if (empty($marca->getFieldValue('Modelo'))) {
                return "Marca restrigida.";
            }
            if ($cotizacion->modeloid == $marca->getFieldValue('Modelo')->getEntityId()) {
                return "Modelo restrigido.";
            }
        }
    }

    public function calcular_tasa($cotizacion, $plan)
    {
        //en caso de error que el valor termine en 0
        $valortasa = 0;
        //encontrar la tasa
        $criterio = "((Plan:equals:" . $plan->getEntityId() . ") and (A_o:equals:" . $cotizacion->ano . "))";
        $tasas = $this->searchRecordsByCriteria("Tasas", $criterio, 1, 200);

        foreach ((array)$tasas as $tasa) {
            //bucar entre los grupos de vehiculo
            if (in_array($cotizacion->modelotipo, $tasa->getFieldValue('Grupo_de_veh_culo'))) {
                $valortasa = $tasa->getFieldValue('Name') / 100;
            }
        }

        return $valortasa;
    }

    public function calcular_recargo($cotizacion, $plan)
    {
        //en caso de error que el valor termine en 0
        $valorrecargo = 0;

        //verificar si la aseguradora tiene algun recargo para la marca o modelo
        $criterio = "((Marca:equals:" . $cotizacion->marcaid . ") and (Aseguradora:equals:" . $plan->getFieldValue('Vendor_Name')->getEntityId() . "))";
        $recargos = $this->searchRecordsByCriteria("Recargos", $criterio, 1, 200);

        foreach ((array)$recargos as $recargo) {
            if (
                ($cotizacion->ano > $recargo->getFieldValue('Desde')
                    and
                    $cotizacion->ano < $recargo->getFieldValue('Hasta')
                    and
                    $recargo->getFieldValue('Tipo') == $cotizacion->modelotipo)
                or
                ($cotizacion->modeloid == $recargo->getFieldValue('Modelo'))
                or
                ($recargo->getFieldValue('Tipo') == $cotizacion->modelotipo)
                or
                ($cotizacion->ano > $recargo->getFieldValue('Desde'))
            ) {
                $valorrecargo = $recargo->getFieldValue('Name') / 100;
            }
        }

        return $valorrecargo;
    }

    public function calcular_prima($cotizacion, $tasa, $recargo)
    {
        //calculo para cotizacion auto
        return  $cotizacion->suma * ($tasa + ($tasa * $recargo));
    }

    public function cotizar(Cotizacion $cotizacion)
    {
        //planes relacionados al banco
        $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Product_Category:equals:Auto))";
        $planes =  $this->searchRecordsByCriteria("Products", $criterio);

        foreach ((array)$planes as $plan) {
            //inicializacion de variables
            $comentario = "";
            $prima = 0;

            //verificaciones
            $comentario = $this->verificar_limites($cotizacion, $plan);

            //si no hubo un excepcion
            if (empty($comentario)) {
                //calcular tasa
                $tasa = $this->calcular_tasa($cotizacion, $plan);

                //calcular recargo
                $recargo = $this->calcular_recargo($cotizacion, $plan);

                //calcular prima
                $prima = $this->calcular_prima($cotizacion, $tasa, $recargo);

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
        $sheet->setCellValue('D2', 'EMISIONES PLAN AUTO');
        $sheet->setCellValue('D4', 'Generado por:');
        $sheet->setCellValue('E4', session("usuario")->getFieldValue("First_Name") . " " . session("usuario")->getFieldValue("Last_Name"));
        $sheet->setCellValue('D5', 'Desde:');
        $sheet->setCellValue('E5', $reporte->desde);
        $sheet->setCellValue('D6', 'Hasta:');
        $sheet->setCellValue('E6', $reporte->hasta);

        //titulos de las columnas de tabla
        $sheet->setCellValue('A12', 'Num');
        $sheet->setCellValue('B12', 'Referidor');
        $sheet->setCellValue('C12', 'Plan');
        $sheet->setCellValue('D12', 'Aseguradora');
        $sheet->setCellValue('E12', 'Suma asegurada');
        $sheet->setCellValue('F12', 'Prima');
        $sheet->setCellValue('G12', 'Cliente');
        $sheet->setCellValue('H12', 'RNC/Cédula');
        $sheet->setCellValue('I12', 'Tel. Residencia');
        $sheet->setCellValue('J12', 'Fecha de nacimiento');
        $sheet->setCellValue('K12', 'Dirección');
        $sheet->setCellValue('L12', 'Marca');
        $sheet->setCellValue('M12', 'Modelo');
        $sheet->setCellValue('N12', 'Año');
        $sheet->setCellValue('O12', 'Color');
        $sheet->setCellValue('P12', 'Placa');
        $sheet->setCellValue('Q12', 'Chasis');
        $sheet->setCellValue('R12', 'Tipo vehículo');
        $sheet->setCellValue('S12', 'Estado vehículo');

        //inicializar contadores
        $cont = 1;
        $pos = 13;

        foreach ($reporte->emisiones as $emisiones => $emision) {
            if (
                date("Y-m-d", strtotime($emision->getCreatedTime())) >= $reporte->desde
                and
                date("Y-m-d", strtotime($emision->getCreatedTime())) <= $reporte->hasta
            ) {
                //obtener los datos del plan
                //no tenemos problemas porque solo es un plan
                foreach ($emision->getLineItems() as $lineItem) {
                    $aseguradora = $lineItem->getDescription();
                }

                //valores de la tabla
                $sheet->setCellValue('A' . $pos, $cont);
                $sheet->setCellValue('B' . $pos, $emision->getFieldValue('Contact_Name')->getLookupLabel());
                $sheet->setCellValue('C' . $pos, $emision->getFieldValue('Plan'));
                $sheet->setCellValue('D' . $pos, $aseguradora);
                $sheet->setCellValue('E' . $pos, $emision->getFieldValue('Suma_asegurada'));
                $sheet->setCellValue('F' . $pos, $emision->getFieldValue('Prima'));

                //valores relacionados al cliente
                $sheet->setCellValue('G' . $pos, $emision->getFieldValue('Nombre') . " " . $emision->getFieldValue('Apellido'));
                $sheet->setCellValue('H' . $pos, $emision->getFieldValue('RNC_C_dula'));
                $sheet->setCellValue('I' . $pos, $emision->getFieldValue('Tel_Residencia'));
                $sheet->setCellValue('J' . $pos, $emision->getFieldValue('Fecha_de_nacimiento'));
                $sheet->setCellValue('K' . $pos, $emision->getFieldValue('Direcci_n'));

                //valores relacionados al vehiculo
                $sheet->setCellValue('L' . $pos, $emision->getFieldValue('Marca')->getLookupLabel());
                $sheet->setCellValue('M' . $pos, $emision->getFieldValue('Modelo')->getLookupLabel());
                $sheet->setCellValue('N' . $pos, $emision->getFieldValue('A_o'));
                $sheet->setCellValue('O' . $pos, $emision->getFieldValue('Color'));
                $sheet->setCellValue('P' . $pos, $emision->getFieldValue('Placa'));
                $sheet->setCellValue('Q' . $pos, $emision->getFieldValue('Chasis'));
                $sheet->setCellValue('R' . $pos, $emision->getFieldValue('Tipo_veh_culo'));
                $sheet->setCellValue('S' . $pos, $emision->getFieldValue('Condiciones'));

                //contadores
                $cont++;
                $pos++;
            }
        }

        //cambiar el color de fondo de un rango de celdas
        $spreadsheet
            ->getActiveSheet()
            ->getStyle('A12:S12')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('004F97');

        //cambiar el color de fuente de un rango de celdas
        $spreadsheet->getActiveSheet()
            ->getStyle('A12:S12')
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
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(20);
        $sheet->getColumnDimension('O')->setWidth(20);
        $sheet->getColumnDimension('P')->setWidth(20);
        $sheet->getColumnDimension('Q')->setWidth(20);
        $sheet->getColumnDimension('R')->setWidth(20);
        $sheet->getColumnDimension('T')->setWidth(20);
        $sheet->getColumnDimension('S')->setWidth(20);

        //ruta del excel
        $doc = WRITEPATH . 'uploads/reporte.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($doc);

        return $doc;
    }
}
