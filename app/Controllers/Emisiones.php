<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Libraries\Zoho;

class Emisiones extends BaseController
{
    protected $zoho;

    function __construct()
    {
        $this->zoho = new Zoho;
    }

    public function index()
    {
        if ($this->request->getPost()) {
            switch ($this->request->getPost("opcion")) {
                case 'nombre':
                    $criterio = "((Nombre:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;

                case 'apellido':
                    $criterio = "((Apellido:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;

                case 'id':
                    $criterio = "((Identificaci_n:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;
            }
        } else {
            $criterio = "Account_Name:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId();
        }

        $emisiones = $this->zoho->searchRecordsByCriteria("Deals", $criterio);
        return view('emisiones/index', ["emisiones" => $emisiones]);
    }

    public function reporte()
    {
        if ($this->request->getPost()) {
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
            $sheet->setCellValue('E6', $this->request->getPost("desde"));
            $sheet->setCellValue('D7', 'Hasta: ');
            $sheet->setCellValue('E7', $this->request->getPost("hasta"));

            switch ($this->request->getPost("tipo")) {
                case 'auto':
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
                            date("Y-m-d", strtotime($emision->getCreatedTime())) >= $this->request->getPost("desde")
                            and
                            date("Y-m-d", strtotime($emision->getFieldValue('Closing_Date'))) <= $this->request->getPost("hasta")
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
                    break;
            }

            //si no encontro registros sale de la funcion
            if ($cont <= 1) {
                session()->setFlashdata('alerta', 'Ha ocurrido un error');
                return redirect()->to(site_url("excel"));
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

            return $this->response->download($reporte, null)->setFileName("REPORTE EMISIONES.xlsx");
        }

        return view('emisiones/reporte');
    }

    public function incendio($detalles)
    {
        //tomar el json (cotizacion) y pasarlo a array
        $detalles = json_decode($detalles, true);

        if ($this->request->getPost()) {
            //el campo aseguradora tiene mas de un valor, dividido por ,
            //el primer valor es el id de la aseguradora
            //el segundo valor es el total del plan cotizado
            $aseguradora = explode(",", $this->request->getPost("aseguradora"));

            //datos del cliente
            //crear un array con estos valores
            $cliente = [
                "nombre" => $this->request->getPost("nombre"),
                "apellido" => $this->request->getPost("apellido"),
                "id" => $this->request->getPost("id"),
                "fecha" => $this->request->getPost("fecha"),
                "correo" => $this->request->getPost("correo"),
                "tel1" => $this->request->getPost("tel1"),
                "tel2" => $this->request->getPost("tel2"),
                "tel3" => $this->request->getPost("tel3"),
                "direccion" => $this->request->getPost("direccion")
            ];

            //cotizacion debe ser subida al servidor para luego ser subida al registro
            $file = $this->request->getFile('cotizacion');
            //cambiar el nombre del archivo
            $newName = $file->getRandomName();
            //subir el archivo al servidor
            $file->move(WRITEPATH . 'uploads', $newName);
            //ruta del archivo subido
            $ruta = WRITEPATH . 'uploads/' . $newName;

            //planes o coberturas de plan elegido, solo es un registro,
            //debe haber un registro, si no, debe ser posible avanzar
            //estan ubicados en el modulo de productos
            $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Vendor_Name:equals:" . $aseguradora[0] . ") and (Product_Category:equals:Incendio))";
            $coberturas = $this->zoho->searchRecordsByCriteria("Products", $criterio);
            foreach ($coberturas as $cobertura) {
                $coberturaid = $cobertura->getEntityId();
            }

            //array que representa al registro que se creara
            //necesita los valores de la cotizacion y el formulario
            //algunos valores, como el plan y la fecha, son fijos
            $emision = [
                "Deal_Name" => "Emisión Póliza Incendio Hipotecario",
                "Closing_Date" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years")),
                "Amount" => round($aseguradora[1]),
                "Type" => "Incendio",
                "Account_Name" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
                "Contact_Name" =>  session("usuario")->getEntityId(),
                "P_liza" => "En trámite",
                "Aseguradora" => $aseguradora[0],
                "Estado" => "Activo",
                "Plan" => "Incendio Hipotecario",
                "Suma_asegurada" => $detalles["propiedad"],
                "Prestamo" => $detalles["prestamo"],
                "Nombre" => $cliente["nombre"],
                "Apellido" => $cliente["apellido"],
                "Identificaci_n" => $cliente["id"],
                "Fecha_de_nacimiento" => $cliente["fecha"],
                "Correo_electr_nico" => $cliente["correo"],
                "Tel_Residencia" => $cliente["tel1"],
                "Tel_Celular" => $cliente["tel2"],
                "Tel_Trabajo" => $cliente["tel3"],
                "Direcci_n" => $detalles["direccion"],
                "Coberturas" => $coberturaid,
                "Stage" => "Proceso de validación",
                "Tipo_de_Construcci_n" => $detalles["construccion"],
                "Tipo_de_Riesgo" => $detalles["riesgo"],
                "Plazo" => $detalles["plazo"]
            ];

            //crear registro en crm
            $id = $this->zoho->createRecords("Deals", $emision);

            //adjuntar documento al registro creado
            $this->zoho->uploadAttachment("Deals", $id, $ruta);

            //eliminar documento subido al servidor
            unlink($ruta);

            //alerta de confirmacion
            session()->setFlashdata('alerta', 'Emisión realizada correctamente.');

            //ir a los registros
            return redirect()->to(site_url("emisiones"));
        }

        return view('emisiones/incendio', ["detalles" => $detalles]);
    }

    public function plantillaincendio($id)
    {
        $detalles = $this->zoho->getRecord("Deals", $id);
        return view('plantillas/emisionincendio', ["detalles" => $detalles]);
    }

    public function adjuntos($id)
    {
        $attachments = $this->zoho->getAttachments("Products", $id);

        foreach ($attachments as $attchmentIns) {
            $file = $this->zoho->downloadAttachment("Products", $id, $attchmentIns->getId(), WRITEPATH . "uploads");
            return $this->response->download($file, null)->setFileName('Documentos.pdf');
        }
    }

    public function documentos($id)
    {
        if ($files = $this->request->getFiles()) {
            foreach ($files['documentos'] as $file) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads', $newName);
                $this->zoho->uploadAttachment("Deals", $id, WRITEPATH . 'uploads/' . $newName);
            }

            session()->setFlashdata('alerta', 'Documentos adjuntados correctamente');

            //ir a los detalles del registro
            return redirect()->to(site_url("emisiones"));
        }

        $documentos = $this->zoho->getAttachments("Deals", $id);
        return view('emisiones/documentos', ["documentos" => $documentos, "id" => $id]);
    }
}
