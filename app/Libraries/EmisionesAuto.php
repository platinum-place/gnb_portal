<?php

namespace App\Libraries;

use App\Models\Reporte;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EmisionesAuto extends Emisiones
{
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
        $sheet->setCellValue('C12', 'Estado');
        $sheet->setCellValue('D12', 'Plan');
        $sheet->setCellValue('E12', 'Aseguradora');
        $sheet->setCellValue('F12', 'Suma asegurada');
        $sheet->setCellValue('G12', 'Prima');
        $sheet->setCellValue('H12', 'Cliente');
        $sheet->setCellValue('I12', 'RNC/Cédula');
        $sheet->setCellValue('J12', 'Tel. Residencia');
        $sheet->setCellValue('K12', 'Fecha de nacimiento');
        $sheet->setCellValue('L12', 'Dirección');
        $sheet->setCellValue('M12', 'Marca');
        $sheet->setCellValue('N12', 'Modelo');
        $sheet->setCellValue('O12', 'Año');
        $sheet->setCellValue('P12', 'Color');
        $sheet->setCellValue('Q12', 'Placa');
        $sheet->setCellValue('R12', 'Chasis');
        $sheet->setCellValue('S12', 'Tipo vehículo');
        $sheet->setCellValue('T12', 'Estado vehículo');

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
                $sheet->setCellValue('C' . $pos, $emision->getFieldValue('Status'));
                $sheet->setCellValue('D' . $pos, $emision->getFieldValue('Plan'));
                $sheet->setCellValue('E' . $pos, $aseguradora);
                $sheet->setCellValue('F' . $pos, $emision->getFieldValue('Suma_asegurada'));
                $sheet->setCellValue('G' . $pos, $emision->getFieldValue('Prima'));

                //valores relacionados al cliente
                $sheet->setCellValue('H' . $pos, $emision->getFieldValue('Nombre') . " " . $emision->getFieldValue('Apellido'));
                $sheet->setCellValue('I' . $pos, $emision->getFieldValue('RNC_C_dula'));
                $sheet->setCellValue('J' . $pos, $emision->getFieldValue('Tel_Residencia'));
                $sheet->setCellValue('K' . $pos, $emision->getFieldValue('Fecha_de_nacimiento'));
                $sheet->setCellValue('L' . $pos, $emision->getFieldValue('Direcci_n'));

                //valores relacionados al vehiculo
                $sheet->setCellValue('M' . $pos, $emision->getFieldValue('Marca')->getLookupLabel());
                $sheet->setCellValue('N' . $pos, $emision->getFieldValue('Modelo')->getLookupLabel());
                $sheet->setCellValue('O' . $pos, $emision->getFieldValue('A_o'));
                $sheet->setCellValue('P' . $pos, $emision->getFieldValue('Color'));
                $sheet->setCellValue('Q' . $pos, $emision->getFieldValue('Placa'));
                $sheet->setCellValue('R' . $pos, $emision->getFieldValue('Chasis'));
                $sheet->setCellValue('S' . $pos, $emision->getFieldValue('Tipo_veh_culo'));
                $sheet->setCellValue('T' . $pos, $emision->getFieldValue('Condiciones'));

                //contadores
                $cont++;
                $pos++;
            }
        }

        //cambiar el color de fondo de un rango de celdas
        $spreadsheet
            ->getActiveSheet()
            ->getStyle('A12:T12')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('004F97');

        //cambiar el color de fuente de un rango de celdas
        $spreadsheet->getActiveSheet()
            ->getStyle('A12:T12')
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
