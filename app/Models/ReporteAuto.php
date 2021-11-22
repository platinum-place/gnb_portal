<?php
namespace App\Models;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReporteAuto extends Reporte
{

    public function generar_reporte($desde, $hasta)
    {
        // iniciar las librerias de la api para generar excel
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

        // celdas en negrita
        $sheet->getStyle('D1')
            ->getFont()
            ->setBold(true)
            ->setName('Arial')
            ->setSize(14);
        $sheet->getStyle('D2')
            ->getFont()
            ->setBold(true)
            ->setName('Arial')
            ->setSize(12);
        $sheet->getStyle('D4')
            ->getFont()
            ->setBold(true);
        $sheet->getStyle('D5')
            ->getFont()
            ->setBold(true);
        $sheet->getStyle('D6')
            ->getFont()
            ->setBold(true);
        $sheet->getStyle('D7')
            ->getFont()
            ->setBold(true);

        // titulos del reporte
        $sheet->setCellValue('D1', session("cuenta"));
        $sheet->setCellValue('D2', 'EMISIONES PLAN AUTO');
        $sheet->setCellValue('D4', 'Generado por:');
        $sheet->setCellValue('E4', session("usuario"));
        $sheet->setCellValue('D5', 'Desde:');
        $sheet->setCellValue('E5', $desde);
        $sheet->setCellValue('D6', 'Hasta:');
        $sheet->setCellValue('E6', $hasta);

        // elegir el contenido del encabezado de la tabla
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

        // cambiar el color de fondo de un rango de celdas
        $spreadsheet->getActiveSheet()
            ->getStyle('A12:R12')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('004F97');

        // cambiar el color de fuente de un rango de celdas
        $spreadsheet->getActiveSheet()
            ->getStyle('A12:R12')
            ->getFont()
            ->getColor()
            ->setARGB("FFFFFF");

        // inicializar contadores
        $cont = 1;
        $pos = 13;

        // inicializar contadores
        $cont = 1;
        $pos = 13;

        foreach ($this->emisiones as $emision) {
            if (date("Y-m-d", strtotime($emision->getCreatedTime())) >= $desde and date("Y-m-d", strtotime($emision->getCreatedTime())) <= $hasta and $emision->getFieldValue('Quote_Stage') == "Emitida" and ($emision->getFieldValue('Plan') == "Mensual Full" or $emision->getFieldValue('Plan') == "Anual Full")) {
                // valores de la tabla
                $sheet->setCellValue('A' . $pos, $cont);
                $sheet->setCellValue('B' . $pos, $emision->getFieldValue('Contact_Name')
                    ->getLookupLabel());
                $sheet->setCellValue('C' . $pos, $emision->getFieldValue('Plan'));
                $sheet->setCellValue('D' . $pos, $emision->getFieldValue('Coberturas')
                    ->getLookupLabel());
                $sheet->setCellValue('E' . $pos, $emision->getFieldValue('Suma_asegurada'));
                $sheet->setCellValue('F' . $pos, $emision->getFieldValue('Prima'));

                // valores relacionados al cliente
                $sheet->setCellValue('G' . $pos, $emision->getFieldValue("Nombre") . " " . $emision->getFieldValue("Apellido"));
                $sheet->setCellValue('H' . $pos, $emision->getFieldValue('RNC_C_dula'));
                $sheet->setCellValue('I' . $pos, $emision->getFieldValue('Tel_Residencia'));
                $sheet->setCellValue('J' . $pos, $emision->getFieldValue('Fecha_de_nacimiento'));
                $sheet->setCellValue('K' . $pos, $emision->getFieldValue('Direcci_n'));

                // relacionados al vehiculo
                $sheet->setCellValue('L' . $pos, $emision->getFieldValue('Marca')
                    ->getLookupLabel());
                $sheet->setCellValue('M' . $pos, $emision->getFieldValue('Modelo')
                    ->getLookupLabel());
                $sheet->setCellValue('N' . $pos, $emision->getFieldValue('A_o'));
                $sheet->setCellValue('O' . $pos, $emision->getFieldValue('Color'));
                $sheet->setCellValue('P' . $pos, $emision->getFieldValue('Placa'));
                $sheet->setCellValue('Q' . $pos, $emision->getFieldValue('Chasis'));
                $sheet->setCellValue('R' . $pos, $emision->getFieldValue('Tipo_veh_culo'));

                // contadores
                $cont ++;
                $pos ++;
            }
        }

        // ajustar tamaño de las columnas
        foreach (range('A', 'R') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // ruta del excel
        $doc = WRITEPATH . 'uploads/reporte.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($doc);

        return $doc;
    }
}
