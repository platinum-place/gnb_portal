<?php

namespace App\Controllers;

use App\Models\Reporteauto;
use App\Models\Reporteincendio;

class Reportes extends BaseController
{
    public function index()
    {
        if ($this->request->getPost()) {
            switch ($this->request->getPost("tipo")) {
                case 'Incendio':
                    $reporte = new Reporteincendio;
                    $emisiones = $reporte->comprobar($this->request->getPost("tipo"));
                    //si no encontro registros sale de la funcion¿
                    if (empty($emisiones)) {
                        session()->setFlashdata('alerta', 'No existen emisiones dentro de ese rango de tiempo.');
                        return redirect()->to(site_url("reportes"));
                    }
                    $reporte->establecer($this->request->getPost("desde"), $this->request->getPost("hasta"));
                    $excel = $reporte->generarreporte();
                    break;

                    case 'Auto':
                        $reporte = new Reporteauto;
                        $emisiones = $reporte->comprobar($this->request->getPost("tipo"));
                        //si no encontro registros sale de la funcion¿
                        if (empty($emisiones)) {
                            session()->setFlashdata('alerta', 'No existen emisiones dentro de ese rango de tiempo.');
                            return redirect()->to(site_url("reportes"));
                        }
                        $reporte->establecer($this->request->getPost("desde"), $this->request->getPost("hasta"));
                        $excel = $reporte->generarreporte();
                        break;
            }
            return $this->response->download($excel, null)->setFileName("REPORTE EMISIONES.xlsx");
        }
        return view('reportes/index');
    }
}
