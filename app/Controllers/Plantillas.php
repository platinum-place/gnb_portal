<?php

namespace App\Controllers;

use App\Libraries\Zoho;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\Style\Language;

class Plantillas extends BaseController
{
    public function tua($id)
    {
        $libreria = new Zoho;
        //datos de la tua
        $tua = $libreria->getRecord("Deals", $id);
        //datos del cliente
        $cliente = $libreria->getRecord("Leads", $tua->getFieldValue("Cliente")->getEntityId());
        //datos del corredor
        $corredor = $libreria->getRecord("Accounts", $tua->getFieldValue("Account_Name")->getEntityId());
        //datos de los vehiculos
        $criterio = "Trato:equals:$id";
        $vehiculos = $libreria->searchRecordsByCriteria("Bienes", $criterio);
        return view('plantillas/tua', [
            "tua" => $tua,
            "cliente" => $cliente,
            "corredor" => $corredor,
            "vehiculos" => $vehiculos,
        ]);
    }

    public function excel($id)
    {
        $libreria = new Zoho;
        //datos de la tua
        $tua = $libreria->getRecord("Deals", $id);
        //datos del cliente
        $cliente = $libreria->getRecord("Leads", $tua->getFieldValue("Cliente")->getEntityId());
        //datos del corredor
        $corredor = $libreria->getRecord("Accounts", $tua->getFieldValue("Account_Name")->getEntityId());


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
        $sheet->setCellValue('D1', $tua->getFieldValue("Account_Name")->getLookupLabel());
        $sheet->setCellValue('D2', 'REGISTRO TU ASISTENCIA ' . $tua->getFieldValue('Deal_Name'));
        $sheet->setCellValue('D4', 'Estado:');
        $sheet->setCellValue('E4', $tua->getFieldValue("Stage"));
        $sheet->setCellValue('D5', 'Vigencia Desde:');
        $sheet->setCellValue('E5', date('d/m/Y', strtotime($tua->getFieldValue("Fecha_de_inicio"))));
        $sheet->setCellValue('D6', 'Vigencia Hasta:');
        $sheet->setCellValue('E6', date('d/m/Y', strtotime($tua->getFieldValue("Closing_Date"))));
        $sheet->setCellValue('D7', 'Beneficiario:');
        $sheet->setCellValue('E7', $cliente->getFieldValue("First_Name") . " " . $cliente->getFieldValue("Last_Name"));

        // elegir el contenido del encabezado de la tabla
        $sheet->setCellValue('A12', '#');
        $sheet->setCellValue('B12', 'Marca');
        $sheet->setCellValue('C12', 'Modelo');
        $sheet->setCellValue('D12', 'Tipo vehículo');
        $sheet->setCellValue('E12', 'Año');
        $sheet->setCellValue('F12', 'Color');
        $sheet->setCellValue('G12', 'Placa');
        $sheet->setCellValue('H12', 'Chasis');

        // cambiar el color de fondo de un rango de celdas
        $spreadsheet->getActiveSheet()
            ->getStyle('A12:H12')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('004F97');

        // cambiar el color de fuente de un rango de celdas
        $spreadsheet->getActiveSheet()
            ->getStyle('A12:H12')
            ->getFont()
            ->getColor()
            ->setARGB("FFFFFF");

        // inicializar contadores
        $cont = 1;
        $pos = 13;

        //datos de los vehiculos
        $criterio = "Trato:equals:$id";
        $vehiculos = $libreria->searchRecordsByCriteria("Bienes", $criterio);

        foreach ($vehiculos as $vehiculo) {
                // valores de la tabla
                $sheet->setCellValue('A' . $pos, $cont);
                $sheet->setCellValue('B' . $pos, $vehiculo->getFieldValue('Marca'));
                $sheet->setCellValue('C' . $pos, $vehiculo->getFieldValue('Modelo'));
                $sheet->setCellValue('D' . $pos, $vehiculo->getFieldValue('Tipo'));
                $sheet->setCellValue('E' . $pos, $vehiculo->getFieldValue('A_o') );
                $sheet->setCellValue('F' . $pos,$vehiculo->getFieldValue('Color'));
                $sheet->setCellValue('G' . $pos, $vehiculo->getFieldValue('Placa'));
                $sheet->setCellValue('H' . $pos, $vehiculo->getFieldValue('Name'));

                // contadores
                $cont ++;
                $pos ++;
            }

        // ajustar tamaño de las columnas
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(30);
        }

        // ruta del excel
        $doc = WRITEPATH . 'uploads/reporte.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($doc);

        // forzar al navegador a descargar el archivo

        // funciona en ambos ambientes
        $nombre = "Registro " . $tua->getFieldValue('Deal_Name');
        return $this->response->download($doc, null)->setFileName("$nombre.xlsx");
    }

    public function caso($id)
    {
        //ruta donde se guardaran los documentos
        $ruta_servidor =  WRITEPATH . 'uploads/';

        //vaciar la carpeta donde se guardan los documentos
        $files = array_diff(scandir($ruta_servidor), array('.', '..'));
        foreach ($files as $file) {
            if (!is_dir($ruta_servidor . $file)) {
                unlink($ruta_servidor . $file);
            }
        }

        //obtener datos del caso
        $libreria = new Zoho;
        $caso = $libreria->getRecord("Cases", $id);

        // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection();

        //encabezado
        $section->addImage(FCPATH . "img/tua.png", ["width" => 90]);

        //titulo
        $phpWord->addTitleStyle(1, ['size' => 14]);
        $section->addTitle('Reporte de accidente', 1);

        // Agregar tabla
        $table = $section->addTable('myTable');
        $styleFont = array('bold' => true); //Negrita

        $table->addRow();
        $table->addCell(8000)->addText('Núm. de caso', $styleFont);
        $table->addCell(8000)->addText('Fecha', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("TUA"));
        $table->addCell(8000)->addText($caso->getFieldValue("Fecha"));

        $table->addRow();
        $table->addCell(8000)->addText('Asegurado', $styleFont);
        $table->addCell(8000)->addText('Aseguradora', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("Asegurado"));
        $table->addCell(8000)->addText($caso->getFieldValue("Aseguradora"));

        $table->addRow();
        $table->addCell(8000)->addText('Inicio Vigencia', $styleFont);
        $table->addCell(8000)->addText('Fin Vigencia', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("Desde"));
        $table->addCell(8000)->addText($caso->getFieldValue("Hasta"));

        $table->addRow();
        $table->addCell(8000)->addText('Plan', $styleFont);
        $table->addCell(8000)->addText('Póliza', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("Plan"));
        $table->addCell(8000)->addText($caso->getFieldValue("P_liza"));

        $table->addRow();
        $table->addCell(8000)->addText('Marca', $styleFont);
        $table->addCell(8000)->addText('Modelo', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("Marca"));
        $table->addCell(8000)->addText($caso->getFieldValue("Modelo"));

        $table->addRow();
        $table->addCell(8000)->addText('Año', $styleFont);
        $table->addCell(8000)->addText('Placa', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("A_o"));
        $table->addCell(8000)->addText($caso->getFieldValue("Placa"));

        $table->addRow();
        $table->addCell(8000)->addText('Chasis', $styleFont);
        $table->addCell(8000)->addText('Color', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("Chasis"));
        $table->addCell(8000)->addText($caso->getFieldValue("Color"));

        $table->addRow();
        $table->addCell(8000)->addText('Solicitante', $styleFont);
        $table->addCell(8000)->addText('Teléfono', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("Solicitante"));
        $table->addCell(8000)->addText($caso->getFieldValue("Phone"));

        $table->addRow();
        $table->addCell(8000)->addText('Zona', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("Zona"));

        $table->addRow();
        $table->addCell(8000)->addText('Punto A', $styleFont);
        $table->addCell(8000)->addText('Punto B', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("Punto_A"));
        $table->addCell(8000)->addText($caso->getFieldValue("Punto_B"));

        $table->addRow();
        $table->addCell(8000)->addText('Hora de despacho', $styleFont);
        $table->addCell(8000)->addText('Hora de contacto', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("Hora_de_despacho"));
        $table->addCell(8000)->addText($caso->getFieldValue("Hora_de_contacto"));

        $table->addRow();
        $table->addCell(8000)->addText('Hora de cierre', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("Hora_de_cierre"));

        $section->addTextBreak(1, ['size' => 1]);

        // Agregar tabla
        $table = $section->addTable('myTable1');

        $table->addRow();
        $table->addCell(8000)->addText('Observaciones', $styleFont);

        $table->addRow();
        $table->addCell(8000)->addText($caso->getFieldValue("Description"));

        //fotos de accidentes
        $fotos = $libreria->getAttachments("Cases", $caso->getEntityId());

        foreach ((array)$fotos as $foto) {
            $imagen = $libreria->downloadAttachment("Cases", $caso->getEntityId(), $foto->getId(),  $ruta_servidor);
            $nombre = uniqid() . '.png';
            $ruta_final = $ruta_servidor . $nombre;
            rename($imagen,   $ruta_final);
            $section->addImage($ruta_final, ["width" => 500, 'align' => 'left']);
        }

        # Para que no diga que se abre en modo de compatibilidad
        $phpWord->getCompatibility()->setOoxmlVersion(15);
        # Idioma español de México
        $phpWord->getSettings()->setThemeFontLang(new Language("ES-MX"));

        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        //ruta del documento
        $doc = WRITEPATH . 'uploads/documento.docx';

        $objWriter->save($doc);

        //descargar el reporte
        return $this->response->download($doc, null)->setFileName($caso->getFieldValue("TUA") . ".docx");
    }
}
