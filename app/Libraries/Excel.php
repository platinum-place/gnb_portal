<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel
{
    protected $zoho;

    function __construct(Zoho $zoho)
    {
        $this->zoho = $zoho;
    }

    public function reporteauto($desde, $hasta)
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
        $sheet->setCellValue('E2', 'EMISIONES PLAN AUTO');
        $sheet->setCellValue('D4', 'CONTRERAS ESTÉVEZ');
        $sheet->setCellValue('D5', 'Generado por: ');
        $sheet->setCellValue('E5', "Nishaly Germosen");
        $sheet->setCellValue('D6', 'Desde: ');
        $sheet->setCellValue('E6', $desde);
        $sheet->setCellValue('D7', 'Hasta: ');
        $sheet->setCellValue('E7', $hasta);

        //titulos tabla
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
        $sheet->setCellValue('M12', 'Marca');
        $sheet->setCellValue('N12', 'Modelo');
        $sheet->setCellValue('O12', 'Año');
        $sheet->setCellValue('P12', 'Color');
        $sheet->setCellValue('Q12', 'Placa');
        $sheet->setCellValue('R12', 'Chasis');

        $cont = 1;
        $pos = 13;
        $criterio = "Account_Name:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId();
        $emisiones = $this->zoho->searchRecordsByCriteria("Deals", $criterio);
        foreach ((array)$emisiones as $emision) {
            if (
                date("Y-m-d", strtotime($emision->getCreatedTime())) >= $desde
                and
                date("Y-m-d", strtotime($emision->getFieldValue('Closing_Date'))) <= $hasta
            ) {
                $sheet->setCellValue('A' . $pos, $cont);
                $sheet->setCellValue('B' . $pos, $emision->getFieldValue('Nombre') . " " . $emision->getFieldValue('Apellido'));
                $sheet->setCellValue('C' . $pos, $emision->getFieldValue('Identificaci_n'));
                $sheet->setCellValue('D' . $pos, $emision->getFieldValue('Tel_Celular'));
                $sheet->setCellValue('E' . $pos, $emision->getFieldValue('Direcci_n'));
                $sheet->setCellValue('F' . $pos, $emision->getFieldValue('Aseguradora')->getLookupLabel());
                $sheet->setCellValue('G' . $pos, $emision->getFieldValue('P_liza'));
                $sheet->setCellValue('H' . $pos, $emision->getFieldValue('Plan'));
                $sheet->setCellValue('I' . $pos, round($emision->getFieldValue('Suma_asegurada'), 2));
                $sheet->setCellValue('J' . $pos, round($emision->getFieldValue('Amount'), 2));
                $sheet->setCellValue('K' . $pos, date("Y-m-d", strtotime($emision->getCreatedTime())));
                $sheet->setCellValue('L' . $pos, date("Y-m-d", strtotime($emision->getFieldValue('Closing_Date'))));

                $criterio = "Trato:equals:" . $emision->getEntityId();
                $vehiculos = $this->zoho->searchRecordsByCriteria("Bienes", $criterio);
                foreach ($vehiculos as $vehiculo) {
                    $sheet->setCellValue('M' . $pos, $vehiculo->getFieldValue("Marca"));
                    $sheet->setCellValue('N' . $pos, $vehiculo->getFieldValue("Modelo"));
                    $sheet->setCellValue('O' . $pos, $vehiculo->getFieldValue("A_o"));
                    $sheet->setCellValue('P' . $pos, $vehiculo->getFieldValue("Color"));
                    $sheet->setCellValue('Q' . $pos, $vehiculo->getFieldValue("Placa"));
                    $sheet->setCellValue('R' . $pos, $vehiculo->getFieldValue("Name"));
                }

                $cont++;
                $pos++;
            }
        }

        //si no encontro registros sale de la funcion
        if ($cont <= 1) {
            return null;
        }

        //cambiar el color de fondo de un rango de celdas
        $spreadsheet
            ->getActiveSheet()
            ->getStyle('A12:R12')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('004F97');


        //cambiar el color de fuente de un rango de celdas
        $spreadsheet->getActiveSheet()
            ->getStyle('A12:R12')
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
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);


        //ruta del excel
        $reporte = WRITEPATH . 'uploads/reporte.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($reporte);

        return $reporte;
    }
}
