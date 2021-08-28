<?php

namespace App\Controllers;

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
        # code...
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
}
