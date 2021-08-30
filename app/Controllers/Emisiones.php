<?php

namespace App\Controllers;

use App\Libraries\Emisiones as LibrariesEmisiones;
use App\Libraries\Excel;
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
        $criterio = "Account_Name:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId();
        $emisiones = $this->zoho->searchRecordsByCriteria("Deals", $criterio);
        return view('emisiones/index', ["emisiones" => $emisiones]);
    }

    public function reporte()
    {
        if ($this->request->getPost()) {
            $libreria = new Excel($this->zoho);

            switch ($this->request->getPost("tipo")) {
                case 'auto':
                    $excel = $libreria->reporteauto($this->request->getPost("desde"), $this->request->getPost("hasta"));
                    break;
            }

            if (empty($excel)) {
                session()->setFlashdata('alerta', 'Ha ocurrido un error');
                return redirect()->to(site_url("excel"));
            }

            return $this->response->download($excel, null)->setFileName("REPORTE EMISIONES.xlsx");
        }

        return view('emisiones/reporte');
    }

    public function incendio($detalles)
    {
        $detalles = json_decode($detalles, true);

        if ($this->request->getPost()) {
            $libreria = new LibrariesEmisiones($this->zoho);

            //aseguradora elegida en un conjunto de datos
            $aseguradora = explode(",", $this->request->getPost("aseguradora"));

            //datos del cliente
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
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);
            $ruta = WRITEPATH . 'uploads/' . $newName;

            //crear registro
            $id = $libreria->emitirincendio($detalles, $cliente, $aseguradora[0], $aseguradora[1], $ruta);

            //ir a los detalles del registro
            return redirect()->to(site_url("emisiones"));
        }

        return view('emisiones/incendio', ["detalles" => $detalles]);
    }
}
