<?php

namespace App\Controllers;

use App\Libraries\Zoho;
use PhpOffice\PhpWord\Style\Language;

class Plantillas extends BaseController
{
    public function cotizacion($id)
    {
        $libreria = new Zoho;
        //obtener datos de la cotizacion
        $cotizacion = $libreria->getRecord("Quotes", $id);

        switch ($cotizacion->getFieldValue("Plan")) {
            case 'Vida':
                return view('cotizacion_vida', ["cotizacion" => $cotizacion, "libreria" => $libreria]);
                break;

            case 'Vida/Desempleo':
                return view('cotizacion_desempleo', ["cotizacion" => $cotizacion, "libreria" => $libreria]);
                break;

            case 'Seguro Incendio Hipotecario':
                return view('cotizacion_incendio', ["cotizacion" => $cotizacion, "libreria" => $libreria]);
                break;

            default:
                return view('cotizacion_auto', ["cotizacion" => $cotizacion, "libreria" => $libreria]);
                break;
        }
    }

    public function emision($id)
    {
        $libreria = new Zoho;
        //obtener datoss de la emision
        $cotizacion = $libreria->getRecord("Quotes", $id);
        //informacion sobre las coberturas, la aseguradora,las coberturas
        $plan = $libreria->getRecord("Products", $cotizacion->getFieldValue("Coberturas")->getEntityId());

        switch ($cotizacion->getFieldValue("Plan")) {
            case 'Vida':
                return view('emision_vida',  ["cotizacion" => $cotizacion, "plan" => $plan]);
                break;

            case 'Vida/Desempleo':
                return view('emision_desempleo',  ["cotizacion" => $cotizacion, "plan" => $plan]);
                break;

            case 'Seguro Incendio Hipotecario':
                return view('emision_incendio',  ["cotizacion" => $cotizacion, "plan" => $plan]);
                break;

            default:
                return view('emision_auto', ["cotizacion" => $cotizacion, "plan" => $plan]);
                break;
        }
    }

    public function tua($id)
    {
        $libreria = new Zoho;
        //datos de la tua
        $tua = $libreria->getRecord("Deals", $id);
        //datos del cliente
        $cliente = $libreria->getRecord("Leads", $tua->getFieldValue("Cliente")->getEntityId());
        //datos de los vehiculos
        $criterio = "Trato:equals:$id";
        $vehiculos = $libreria->searchRecordsByCriteria("Bienes", $criterio);
        //usuario que creo la tua
        $creado_por = $tua->getCreatedBy();
        //usuario que modifico la tua
        $modificado_por = $tua->getModifiedBy();
        return view('tua', [
            "tua" => $tua,
            "cliente" => $cliente,
            "vehiculos" => $vehiculos,
            "creado_por" => $creado_por,
            "modificado_por" => $modificado_por,
        ]);
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
