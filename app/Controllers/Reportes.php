<?php

namespace App\Controllers;

use App\Libraries\Auto;
use App\Libraries\Desempleo;
use App\Libraries\Incendio;
use App\Libraries\Reportes as LibrariesReportes;
use App\Libraries\Vida;
use App\Models\Reporte;

class Reportes extends BaseController
{
    protected $libreria;

    function __construct()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $this->libreria = new LibrariesReportes;
    }

    public function index()
    {
        if ($this->request->getPost()) {
            //modelo de reporte
            $reporte = new Reporte;
            $reporte->desde = $this->request->getPost("desde");
            $reporte->hasta = $this->request->getPost("hasta");
            $reporte->tipo = $this->request->getPost("tipo");

            //verifica si existen emisiones para generar el reporte
            $this->libreria->emisiones_existentes($reporte);

            //si no encontro registros sale de la funcion
            if (empty($reporte->emisiones)) {
                session()->setFlashdata('alerta', 'No existen emisiones dentro del rango de tiempo.');
                return redirect()->to(site_url("reportes"));
            } else {
                //instanciar una libreria para el cuerpo del reporte
                switch ($this->request->getPost("tipo")) {
                    case 'Vida':
                        $libreria = new Vida;
                        break;

                    case 'Auto':
                        $libreria = new Auto;
                        break;

                    case 'Desempleo':
                        $libreria = new Desempleo;
                        break;

                    case 'Incendio':
                        $libreria = new Incendio;
                        break;
                }

                //crear el archivo en excel y da la ruta a el
                $ruta_reporte = $libreria->generar_reporte($reporte);

                //forzar al navegador a descargar el archivo


                //funciona en ambos ambientes
                $nombre = "Reporte " . $this->request->getPost("tipo") . " " . date("d-m-Y");
                return $this->response->download($ruta_reporte, null)->setFileName("$nombre.xlsx");;

                //no funciona en ambiente de produccion, solo en desarrollo local
                //es necesario no tener echo antes de descargar
                /*
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($ruta_reporte) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($ruta_reporte));
                readfile($ruta_reporte);
                //eliminar el archivo descargado
                unlink($ruta_reporte);
                */
            }
        }

        //vista
        return view("reportes/index", ["titulo" => "Reporte de Pólizas Emitidas"]);
    }
}
