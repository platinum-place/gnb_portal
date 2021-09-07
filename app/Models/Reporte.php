<?php

namespace App\Models;

use App\Zoho;
use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\exception\ZCRMException;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reporte extends Zoho
{
    protected $desde;
    protected $hasta;

    public function establecer($desde, $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    public function reporte_incendio()
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Deals"); // To get module instance
        $criteria = "((Type:equals:Incendio) and (Account_Name:equals:" .  session()->get("usuario")->getFieldValue("Account_Name")->getEntityId() . "))"; //criteria to search for
        $param_map = array("page" => 1, "per_page" => 200); // key-value pair containing all the parameters
        try {
            $response = $moduleIns->searchRecordsByCriteria($criteria, $param_map); // To get module records// $criteria to search for  to search for// $param_map-parameters key-value pair - optional
            $records = $response->getData(); // To get response data
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Add a drawing to the worksheet
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(public_path("img/nobe.png"));
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
            $sheet->setCellValue('E2', 'REPORTE SEGURO INCENDIO HIPOTECARIO');
            $sheet->setCellValue('D4', session()->get("usuario")->getFieldValue("Account_Name")->getLookupLabel());
            $sheet->setCellValue('D5', 'Generado por:');
            $sheet->setCellValue('E5', session()->get("usuario")->getFieldValue("First_Name") . " " . session()->get("usuario")->getFieldValue("Last_Name"));
            $sheet->setCellValue('D6', 'Desde:');
            $sheet->setCellValue('E6', $this->desde);
            $sheet->setCellValue('D7', 'Hasta:');
            $sheet->setCellValue('E7', $this->hasta);
            //titulo tabla
            $sheet->setCellValue('A12', 'Num');
            $sheet->setCellValue('B12', 'Cliente');
            $sheet->setCellValue('C12', 'Cédula/RNC');
            $sheet->setCellValue('D12', 'Fecha de Nacimiento');
            $sheet->setCellValue('E12', 'Teléfono');
            $sheet->setCellValue('F12', 'Dirección');
            $sheet->setCellValue('G12', 'Aseguradora');
            $sheet->setCellValue('H12', 'Póliza');
            $sheet->setCellValue('I12', 'Plan');
            $sheet->setCellValue('J12', 'Valor de la propiedad');
            $sheet->setCellValue('K12', 'Prima');
            $sheet->setCellValue('L12', 'Desde');
            $sheet->setCellValue('M12', 'Hasta');
            $sheet->setCellValue('N12', 'Valor del Préstamo');
            $sheet->setCellValue('O12', 'Plazo');
            $sheet->setCellValue('P12', 'Tipo de Construcción');
            $sheet->setCellValue('Q12', 'Tipo de Riesgo');
            $cont = 1;
            $pos = 13;
            foreach ($records as $record) {
                if (
                    date("Y-m-d", strtotime($record->getCreatedTime())) >= $this->desde
                    and
                    date("Y-m-d", strtotime($record->getCreatedTime())) <= $this->hasta
                ) {
                    $sheet->setCellValue('A' . $pos, $cont);
                    $sheet->setCellValue('B' . $pos, $record->getFieldValue('Nombre') . " " . $record->getFieldValue('Apellido'));
                    $sheet->setCellValue('C' . $pos, $record->getFieldValue('Identificaci_n'));
                    $sheet->setCellValue('D' . $pos, $record->getFieldValue('Fecha_de_nacimiento'));
                    $sheet->setCellValue('E' . $pos, $record->getFieldValue('Tel_Celular'));
                    $sheet->setCellValue('F' . $pos, $record->getFieldValue('Direcci_n'));
                    $sheet->setCellValue('G' . $pos, $record->getFieldValue('Aseguradora')->getLookupLabel());
                    $sheet->setCellValue('H' . $pos, $record->getFieldValue('P_liza'));
                    $sheet->setCellValue('I' . $pos, $record->getFieldValue('Plan'));
                    $sheet->setCellValue('J' . $pos, $record->getFieldValue('Suma_asegurada'));
                    $sheet->setCellValue('K' . $pos, $record->getFieldValue('Amount'));
                    $sheet->setCellValue('L' . $pos, date("Y-m-d", strtotime($record->getCreatedTime())));
                    $sheet->setCellValue('M' . $pos, date("Y-m-d", strtotime($record->getFieldValue('Closing_Date'))));
                    $sheet->setCellValue('N' . $pos, $record->getFieldValue('Prestamo'));
                    $sheet->setCellValue('O' . $pos, $record->getFieldValue("Plazo"));
                    $sheet->setCellValue('P' . $pos, $record->getFieldValue("Tipo_de_Construcci_n"));
                    $sheet->setCellValue('Q' . $pos, $record->getFieldValue("Tipo_de_Riesgo"));
                    $cont++;
                    $pos++;
                }
            }
            //cambiar el color de fondo de un rango de celdas
            $spreadsheet
                ->getActiveSheet()
                ->getStyle('A12:Q12')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('004F97');
            //cambiar el color de fuente de un rango de celdas
            $spreadsheet->getActiveSheet()
                ->getStyle('A12:Q12')
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
            $sheet->getColumnDimension('Q')->setAutoSize(true);
            //ruta del excel
            $doc = storage_path("app/public/reporte.xlsx");
            $writer = new Xlsx($spreadsheet);
            $writer->save($doc);
            return $doc;
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo $ex->getFile(); // To get the file name that throws the Exception
            return null;
        }
    }

    protected function vehiculo_asosiado($id)
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Bienes"); // To get module instance
        $criteria = "Trato:equals:$id"; //criteria to search for
        $param_map = array("page" => 1, "per_page" => 1); // key-value pair containing all the parameters
        $response = $moduleIns->searchRecordsByCriteria($criteria, $param_map); // To get module records// $criteria to search for  to search for// $param_map-parameters key-value pair - optional
        $records = $response->getData(); // To get response data
        try {
            return $records;
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo $ex->getFile(); // To get the file name that throws the Exception
        }
    }

    public function reporte_auto()
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Deals"); // To get module instance
        $criteria = "((Type:equals:Auto) and (Account_Name:equals:" .  session()->get("usuario")->getFieldValue("Account_Name")->getEntityId() . "))"; //criteria to search for
        $param_map = array("page" => 1, "per_page" => 200); // key-value pair containing all the parameters
        try {
            $response = $moduleIns->searchRecordsByCriteria($criteria, $param_map); // To get module records// $criteria to search for  to search for// $param_map-parameters key-value pair - optional
            $records = $response->getData(); // To get response data
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Add a drawing to the worksheet
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(public_path("img/nobe.png"));
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
            $sheet->setCellValue('D4', session()->get("usuario")->getFieldValue("Account_Name")->getLookupLabel());
            $sheet->setCellValue('D5', 'Generado por:');
            $sheet->setCellValue('E5', session()->get("usuario")->getFieldValue("First_Name") . " " . session()->get("usuario")->getFieldValue("Last_Name"));
            $sheet->setCellValue('D6', 'Desde:');
            $sheet->setCellValue('E6', $this->desde);
            $sheet->setCellValue('D7', 'Hasta:');
            $sheet->setCellValue('E7', $this->hasta);
            //titulo tabla
            $sheet->setCellValue('A12', 'Num');
            $sheet->setCellValue('B12', 'Cliente');
            $sheet->setCellValue('C12', 'Cédula/RNC');
            $sheet->setCellValue('D12', 'Fecha de Nacimiento');
            $sheet->setCellValue('E12', 'Teléfono');
            $sheet->setCellValue('F12', 'Dirección');
            $sheet->setCellValue('G12', 'Aseguradora');
            $sheet->setCellValue('H12', 'Póliza');
            $sheet->setCellValue('I12', 'Plan');
            $sheet->setCellValue('J12', 'Suma Asegurada');
            $sheet->setCellValue('K12', 'Prima');
            $sheet->setCellValue('L12', 'Desde');
            $sheet->setCellValue('M12', 'Hasta');
            $sheet->setCellValue('N12', 'Marca');
            $sheet->setCellValue('O12', 'Modelo');
            $sheet->setCellValue('P12', 'Año');
            $sheet->setCellValue('Q12', 'Color');
            $sheet->setCellValue('R12', 'Placa');
            $sheet->setCellValue('S12', 'Chasis');
            $cont = 1;
            $pos = 13;
            foreach ($records as $record) {
                if (
                    date("Y-m-d", strtotime($record->getCreatedTime())) >= $this->desde
                    and
                    date("Y-m-d", strtotime($record->getCreatedTime())) <= $this->hasta
                ) {
                    $sheet->setCellValue('A' . $pos, $cont);
                    $sheet->setCellValue('B' . $pos, $record->getFieldValue('Nombre') . " " . $record->getFieldValue('Apellido'));
                    $sheet->setCellValue('C' . $pos, $record->getFieldValue('Identificaci_n'));
                    $sheet->setCellValue('D' . $pos, $record->getFieldValue('Fecha_de_nacimiento'));
                    $sheet->setCellValue('E' . $pos, $record->getFieldValue('Tel_Celular'));
                    $sheet->setCellValue('F' . $pos, $record->getFieldValue('Direcci_n'));
                    $sheet->setCellValue('G' . $pos, $record->getFieldValue('Aseguradora')->getLookupLabel());
                    $sheet->setCellValue('H' . $pos, $record->getFieldValue('P_liza'));
                    $sheet->setCellValue('I' . $pos, $record->getFieldValue('Plan'));
                    $sheet->setCellValue('J' . $pos, $record->getFieldValue('Suma_asegurada'));
                    $sheet->setCellValue('K' . $pos, $record->getFieldValue('Amount'));
                    $sheet->setCellValue('L' . $pos, date("Y-m-d", strtotime($record->getCreatedTime())));
                    $sheet->setCellValue('M' . $pos, date("Y-m-d", strtotime($record->getFieldValue('Closing_Date'))));
                    $vehiculos = $this->vehiculo_asosiado($record->getEntityId());
                    foreach ($vehiculos as $vehiculo) {
                        $sheet->setCellValue('N' . $pos, $vehiculo->getFieldValue("Marca"));
                        $sheet->setCellValue('O' . $pos, $vehiculo->getFieldValue("Modelo"));
                        $sheet->setCellValue('P' . $pos, $vehiculo->getFieldValue("A_o"));
                        $sheet->setCellValue('Q' . $pos, $vehiculo->getFieldValue("Color"));
                        $sheet->setCellValue('R' . $pos, $vehiculo->getFieldValue("Placa"));
                        $sheet->setCellValue('S' . $pos, $vehiculo->getFieldValue("Name"));
                    }
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
            $sheet->getColumnDimension('Q')->setAutoSize(true);
            $sheet->getColumnDimension('R')->setAutoSize(true);
            $sheet->getColumnDimension('S')->setAutoSize(true);
            //ruta del excel
            $doc = storage_path("app/public/reporte.xlsx");
            $writer = new Xlsx($spreadsheet);
            $writer->save($doc);
            return $doc;
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo $ex->getFile(); // To get the file name that throws the Exception
            return null;
        }
    }

    public function reporte_vida()
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Deals"); // To get module instance
        $criteria = "((Type:equals:Vida) and (Account_Name:equals:" .  session()->get("usuario")->getFieldValue("Account_Name")->getEntityId() . "))"; //criteria to search for
        $param_map = array("page" => 1, "per_page" => 200); // key-value pair containing all the parameters
        try {
            $response = $moduleIns->searchRecordsByCriteria($criteria, $param_map); // To get module records// $criteria to search for  to search for// $param_map-parameters key-value pair - optional
            $records = $response->getData(); // To get response data
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Add a drawing to the worksheet
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(public_path("img/nobe.png"));
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
            $sheet->setCellValue('E2', 'REPORTE PLAN VIDA');
            $sheet->setCellValue('D4', session()->get("usuario")->getFieldValue("Account_Name")->getLookupLabel());
            $sheet->setCellValue('D5', 'Generado por:');
            $sheet->setCellValue('E5', session()->get("usuario")->getFieldValue("First_Name") . " " . session()->get("usuario")->getFieldValue("Last_Name"));
            $sheet->setCellValue('D6', 'Desde:');
            $sheet->setCellValue('E6', $this->desde);
            $sheet->setCellValue('D7', 'Hasta:');
            $sheet->setCellValue('E7', $this->hasta);
            //titulo tabla
            $sheet->setCellValue('A12', 'Num');
            $sheet->setCellValue('B12', 'Deudor');
            $sheet->setCellValue('C12', 'Cédula/RNC');
            $sheet->setCellValue('D12', 'Fecha de Nacimiento');
            $sheet->setCellValue('E12', 'Teléfono');
            $sheet->setCellValue('F12', 'Dirección');
            $sheet->setCellValue('G12', 'Aseguradora');
            $sheet->setCellValue('H12', 'Póliza');
            $sheet->setCellValue('I12', 'Plan');
            $sheet->setCellValue('J12', 'Suma Asegurada');
            $sheet->setCellValue('K12', 'Prima');
            $sheet->setCellValue('L12', 'Desde');
            $sheet->setCellValue('M12', 'Hasta');
            $sheet->setCellValue('N12', 'Plazo');
            $cont = 1;
            $pos = 13;
            foreach ($records as $record) {
                if (
                    date("Y-m-d", strtotime($record->getCreatedTime())) >= $this->desde
                    and
                    date("Y-m-d", strtotime($record->getCreatedTime())) <= $this->hasta
                ) {
                    $sheet->setCellValue('A' . $pos, $cont);
                    $sheet->setCellValue('B' . $pos, $record->getFieldValue('Nombre') . " " . $record->getFieldValue('Apellido'));
                    $sheet->setCellValue('C' . $pos, $record->getFieldValue('Identificaci_n'));
                    $sheet->setCellValue('D' . $pos, $record->getFieldValue('Fecha_de_nacimiento'));
                    $sheet->setCellValue('E' . $pos, $record->getFieldValue('Tel_Celular'));
                    $sheet->setCellValue('F' . $pos, $record->getFieldValue('Direcci_n'));
                    $sheet->setCellValue('G' . $pos, $record->getFieldValue('Aseguradora')->getLookupLabel());
                    $sheet->setCellValue('H' . $pos, $record->getFieldValue('P_liza'));
                    $sheet->setCellValue('I' . $pos, $record->getFieldValue('Plan'));
                    $sheet->setCellValue('J' . $pos, $record->getFieldValue('Suma_asegurada'));
                    $sheet->setCellValue('K' . $pos, $record->getFieldValue('Amount'));
                    $sheet->setCellValue('L' . $pos, date("Y-m-d", strtotime($record->getCreatedTime())));
                    $sheet->setCellValue('M' . $pos, date("Y-m-d", strtotime($record->getFieldValue('Closing_Date'))));
                    $sheet->setCellValue('N' . $pos, $record->getFieldValue("Plazo"));
                    $cont++;
                    $pos++;
                }
            }
            //cambiar el color de fondo de un rango de celdas
            $spreadsheet
                ->getActiveSheet()
                ->getStyle('A12:N12')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('004F97');
            //cambiar el color de fuente de un rango de celdas
            $spreadsheet->getActiveSheet()
                ->getStyle('A12:N12')
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
            //ruta del excel
            $doc = storage_path("app/public/reporte.xlsx");
            $writer = new Xlsx($spreadsheet);
            $writer->save($doc);
            return $doc;
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo $ex->getFile(); // To get the file name that throws the Exception
            return null;
        }
    }

    public function reporte_desempleo()
    {
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Deals"); // To get module instance
        $criteria = "((Type:equals:Desempleo) and (Account_Name:equals:" .  session()->get("usuario")->getFieldValue("Account_Name")->getEntityId() . "))"; //criteria to search for
        $param_map = array("page" => 1, "per_page" => 200); // key-value pair containing all the parameters
        try {
            $response = $moduleIns->searchRecordsByCriteria($criteria, $param_map); // To get module records// $criteria to search for  to search for// $param_map-parameters key-value pair - optional
            $records = $response->getData(); // To get response data
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Add a drawing to the worksheet
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(public_path("img/nobe.png"));
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
            $sheet->setCellValue('E2', 'REPORTE SEGURO INCENDIO HIPOTECARIO');
            $sheet->setCellValue('D4', session()->get("usuario")->getFieldValue("Account_Name")->getLookupLabel());
            $sheet->setCellValue('D5', 'Generado por:');
            $sheet->setCellValue('E5', session()->get("usuario")->getFieldValue("First_Name") . " " . session()->get("usuario")->getFieldValue("Last_Name"));
            $sheet->setCellValue('D6', 'Desde:');
            $sheet->setCellValue('E6', $this->desde);
            $sheet->setCellValue('D7', 'Hasta:');
            $sheet->setCellValue('E7', $this->hasta);
            //titulo tabla
            $sheet->setCellValue('A12', 'Num');
            $sheet->setCellValue('B12', 'Cliente');
            $sheet->setCellValue('C12', 'Cédula/RNC');
            $sheet->setCellValue('D12', 'Fecha de Nacimiento');
            $sheet->setCellValue('E12', 'Teléfono');
            $sheet->setCellValue('F12', 'Dirección');
            $sheet->setCellValue('G12', 'Aseguradora');
            $sheet->setCellValue('H12', 'Póliza');
            $sheet->setCellValue('I12', 'Plan');
            $sheet->setCellValue('J12', 'Suma Asegurada');
            $sheet->setCellValue('K12', 'Prima');
            $sheet->setCellValue('L12', 'Desde');
            $sheet->setCellValue('M12', 'Hasta');
            $sheet->setCellValue('N12', 'Cuota Mensual de Prestamo');
            $sheet->setCellValue('O12', 'Plazo');
            $cont = 1;
            $pos = 13;
            foreach ($records as $record) {
                if (
                    date("Y-m-d", strtotime($record->getCreatedTime())) >= $this->desde
                    and
                    date("Y-m-d", strtotime($record->getCreatedTime())) <= $this->hasta
                ) {
                    $sheet->setCellValue('A' . $pos, $cont);
                    $sheet->setCellValue('B' . $pos, $record->getFieldValue('Nombre') . " " . $record->getFieldValue('Apellido'));
                    $sheet->setCellValue('C' . $pos, $record->getFieldValue('Identificaci_n'));
                    $sheet->setCellValue('D' . $pos, $record->getFieldValue('Fecha_de_nacimiento'));
                    $sheet->setCellValue('E' . $pos, $record->getFieldValue('Tel_Celular'));
                    $sheet->setCellValue('F' . $pos, $record->getFieldValue('Direcci_n'));
                    $sheet->setCellValue('G' . $pos, $record->getFieldValue('Aseguradora')->getLookupLabel());
                    $sheet->setCellValue('H' . $pos, $record->getFieldValue('P_liza'));
                    $sheet->setCellValue('I' . $pos, $record->getFieldValue('Plan'));
                    $sheet->setCellValue('J' . $pos, $record->getFieldValue('Suma_asegurada'));
                    $sheet->setCellValue('K' . $pos, $record->getFieldValue('Amount'));
                    $sheet->setCellValue('L' . $pos, date("Y-m-d", strtotime($record->getCreatedTime())));
                    $sheet->setCellValue('M' . $pos, date("Y-m-d", strtotime($record->getFieldValue('Closing_Date'))));
                    $sheet->setCellValue('N' . $pos, $record->getFieldValue('Prestamo'));
                    $sheet->setCellValue('O' . $pos, $record->getFieldValue("Plazo"));
                    $cont++;
                    $pos++;
                }
            }
            //cambiar el color de fondo de un rango de celdas
            $spreadsheet
                ->getActiveSheet()
                ->getStyle('A12:O12')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('004F97');
            //cambiar el color de fuente de un rango de celdas
            $spreadsheet->getActiveSheet()
                ->getStyle('A12:O12')
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
            //ruta del excel
            $doc = storage_path("app/public/reporte.xlsx");
            $writer = new Xlsx($spreadsheet);
            $writer->save($doc);
            return $doc;
        } catch (ZCRMException $ex) {
            echo $ex->getMessage(); // To get ZCRMException error message
            echo $ex->getExceptionCode(); // To get ZCRMException error code
            echo $ex->getFile(); // To get the file name that throws the Exception
            return null;
        }
    }
}
