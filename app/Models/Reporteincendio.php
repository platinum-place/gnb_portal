<?php

namespace App\Models;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reporteincendio extends Reporte
{
    public function generarreporte()
    {
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

        //negrita
        $sheet->getStyle('E2')->getFont()->setBold(true)->setName('Arial')->setSize(18);
        $sheet->getStyle('D4')->getFont()->setBold(true);
        $sheet->getStyle('D5')->getFont()->setBold(true);
        $sheet->getStyle('D6')->getFont()->setBold(true);
        $sheet->getStyle('D7')->getFont()->setBold(true);

        //titulos
        $sheet->setCellValue('E2', 'EMISIONES SEGURO INCENDIO HIPOTECARIO');
        $sheet->setCellValue('D4', session("usuario")->getFieldValue("Account_Name")->getLookupLabel());
        $sheet->setCellValue('D5', 'Generado por:');
        $sheet->setCellValue('E5', session("usuario")->getFieldValue("First_Name") . " " . session("usuario")->getFieldValue("Last_Name"));
        $sheet->setCellValue('D6', 'Desde:');
        $sheet->setCellValue('E6', $this->desde);
        $sheet->setCellValue('D7', 'Hasta:');
        $sheet->setCellValue('E7', $this->hasta);

        //titulo tabla
        $sheet->setCellValue('A12', 'Num');
        $sheet->setCellValue('B12', 'Cliente');
        $sheet->setCellValue('C12', 'Identificación');
        $sheet->setCellValue('D12', 'Teléfono');
        $sheet->setCellValue('E12', 'Dirección');
        $sheet->setCellValue('F12', 'Aseguradora');
        $sheet->setCellValue('G12', 'Póliza');
        $sheet->setCellValue('H12', 'Plan');
        $sheet->setCellValue('I12', 'Suma Asegurada');
        $sheet->setCellValue('J12', 'Prima');
        $sheet->setCellValue('K12', 'Desde');
        $sheet->setCellValue('L12', 'Hasta');
        $sheet->setCellValue('M12', 'Prestamo');
        $sheet->setCellValue('N12', 'Plazo');
        $sheet->setCellValue('O12', 'Tipo de Construcción');
        $sheet->setCellValue('P12', 'Tipo de Riesgo');

        $cont = 1;
        $pos = 13;

        foreach ($this->emisiones as $emision) {
            if (
                date("Y-m-d", strtotime($emision->getCreatedTime())) >= $this->desde
                and
                date("Y-m-d", strtotime($emision->getCreatedTime())) <= $this->hasta
            ) {
                $sheet->setCellValue('A' . $pos, $cont);
                $sheet->setCellValue('B' . $pos, $emision->getFieldValue('Nombre') . " " . $emision->getFieldValue('Apellido'));
                $sheet->setCellValue('C' . $pos, $emision->getFieldValue('Identificaci_n'));
                $sheet->setCellValue('D' . $pos, $emision->getFieldValue('Tel_Celular'));
                $sheet->setCellValue('E' . $pos, $emision->getFieldValue('Direcci_n'));
                $sheet->setCellValue('F' . $pos, $emision->getFieldValue('Aseguradora')->getLookupLabel());
                $sheet->setCellValue('G' . $pos, $emision->getFieldValue('P_liza'));
                $sheet->setCellValue('H' . $pos, $emision->getFieldValue('Plan'));
                $sheet->setCellValue('I' . $pos, $emision->getFieldValue('Suma_asegurada'));
                $sheet->setCellValue('J' . $pos, $emision->getFieldValue('Amount'));
                $sheet->setCellValue('K' . $pos, date("Y-m-d", strtotime($emision->getCreatedTime())));
                $sheet->setCellValue('L' . $pos, date("Y-m-d", strtotime($emision->getFieldValue('Closing_Date'))));
                $sheet->setCellValue('M' . $pos, $emision->getFieldValue('Prestamo'));
                $sheet->setCellValue('N' . $pos, $emision->getFieldValue("Plazo"));
                $sheet->setCellValue('O' . $pos, $emision->getFieldValue("Tipo_de_Construcci_n"));
                $sheet->setCellValue('P' . $pos, $emision->getFieldValue("Tipo_de_Riesgo"));

                $cont++;
                $pos++;
            }
        }

        //cambiar el color de fondo de un rango de celdas
        $spreadsheet
            ->getActiveSheet()
            ->getStyle('A12:P12')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('004F97');

        //cambiar el color de fuente de un rango de celdas
        $spreadsheet->getActiveSheet()
            ->getStyle('A12:P12')
            ->getFont()
            ->getColor()
            ->setARGB("FFFFFF");

        //ajustar tamaño de las clumnas
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);

        //ruta del excel
        $doc = WRITEPATH . 'uploads/reporte.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($doc);

        return $doc;
    }
}
