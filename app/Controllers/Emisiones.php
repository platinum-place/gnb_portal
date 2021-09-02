<?php

namespace App\Controllers;

use App\Libraries\Zoho;
use App\Models\Emisiondesempleo;
use App\Models\Emisionincendio;
use App\Models\Emisionvida;

class Emisiones extends BaseController
{
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

                case 'codigo':
                    $criterio = "((TUA:equals:" . $this->request->getPost("busqueda") . ") and (Account_Name:equals:" .  session("usuario")->getFieldValue("Account_Name")->getEntityId() . "))";
                    break;
            }
        } else {
            $criterio = "Account_Name:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId();
        }
        $zoho = new Zoho;
        $emisiones = $zoho->searchRecordsByCriteria("Deals", $criterio);
        return view('emisiones/index', ["emisiones" => $emisiones]);
    }

    public function incendio($cotizacion)
    {
        //tomar el json (cotizacion) y pasarlo a array
        $cotizacion = json_decode($cotizacion);
        if ($this->request->getPost()) {
            //el campo aseguradora tiene mas de un valor, dividido por ,
            //el primer valor es el id de la aseguradora
            //el segundo valor es el total del plan cotizado
            $aseguradora = explode(",", $this->request->getPost("aseguradora"));
            //cotizacion debe ser subida al servidor para luego ser subida al registro
            $file = $this->request->getFile('cotizacion');
            //cambiar el nombre del archivo
            $newName = $file->getRandomName();
            //subir el archivo al servidor
            $file->move(WRITEPATH . 'uploads', $newName);
            //ruta del archivo subido
            $ruta = WRITEPATH . 'uploads/' . $newName;
            $incendio = new Emisionincendio;
            $incendio->establecer_aseguradora($aseguradora[0], $aseguradora[1]);
            $incendio->obtener_coberturas("Incendio");
            $incendio->establecer_emision(
                $cotizacion->propiedad,
                $cotizacion->prestamo,
                $cotizacion->direccion,
                $cotizacion->construccion,
                $cotizacion->riesgo,
                $cotizacion->plazo
            );
            if ($incendio->crear_emision($this->request) == true) {
                $incendio->adjuntar_documento($ruta);
                //llama una vista adicional para alertas que son muy grandes
                $alerta = view("alertas/emision");
                //alerta de confirmacion
                session()->setFlashdata('alerta', $alerta);
                //ir a los registros
                return redirect()->to(site_url("emisiones"));
            } else {
                session()->setFlashdata('alerta', "Ha ocurrido un error.");
            }
        }
        return view('emisiones/incendio', ["cotizacion" => $cotizacion]);
    }

    public function desempleo($cotizacion)
    {
        //tomar el json (cotizacion) y pasarlo a array
        $cotizacion = json_decode($cotizacion);
        if ($this->request->getPost()) {
            //el campo aseguradora tiene mas de un valor, dividido por ,
            //el primer valor es el id de la aseguradora
            //el segundo valor es el total del plan cotizado
            $aseguradora = explode(",", $this->request->getPost("aseguradora"));
            //cotizacion debe ser subida al servidor para luego ser subida al registro
            $file = $this->request->getFile('cotizacion');
            //cambiar el nombre del archivo
            $newName = $file->getRandomName();
            //subir el archivo al servidor
            $file->move(WRITEPATH . 'uploads', $newName);
            //ruta del archivo subido
            $ruta = WRITEPATH . 'uploads/' . $newName;
            $incendio = new Emisiondesempleo;
            $incendio->establecer_aseguradora($aseguradora[0], $aseguradora[1]);
            $incendio->obtener_coberturas("Desempleo");
            $incendio->establecer_emision(
                $cotizacion->suma,
                $cotizacion->cuota,
                $cotizacion->fecha,
                $cotizacion->plazo
            );
            if ($incendio->crear_emision($this->request) == true) {
                $incendio->adjuntar_documento($ruta);
                //llama una vista adicional para alertas que son muy grandes
                $alerta = view("alertas/emision");
                //alerta de confirmacion
                session()->setFlashdata('alerta', $alerta);
                //ir a los registros
                return redirect()->to(site_url("emisiones"));
            } else {
                session()->setFlashdata('alerta', "Ha ocurrido un error.");
            }
        }
        return view('emisiones/desempleo', ["cotizacion" => $cotizacion]);
    }

    public function vida($cotizacion)
    {
        //tomar el json (cotizacion) y pasarlo a array
        $cotizacion = json_decode($cotizacion);
        if ($this->request->getPost()) {
            //el campo aseguradora tiene mas de un valor, dividido por ,
            //el primer valor es el id de la aseguradora
            //el segundo valor es el total del plan cotizado
            $aseguradora = explode(",", $this->request->getPost("aseguradora"));
            //cotizacion debe ser subida al servidor para luego ser subida al registro
            $file = $this->request->getFile('cotizacion');
            //cambiar el nombre del archivo
            $newName = $file->getRandomName();
            //subir el archivo al servidor
            $file->move(WRITEPATH . 'uploads', $newName);
            //ruta del archivo subido
            $ruta = WRITEPATH . 'uploads/' . $newName;
            $incendio = new Emisionvida;
            $incendio->establecer_aseguradora($aseguradora[0], $aseguradora[1]);
            $incendio->obtener_coberturas("Vida");
            $incendio->establecer_emision(
                $cotizacion->suma,
                $cotizacion->fecha_deudor,
                $cotizacion->fecha_codeudor,
                $cotizacion->plazo
            );
            if ($incendio->crear_emision($this->request) == true) {
                $incendio->adjuntar_documento($ruta);
                //llama una vista adicional para alertas que son muy grandes
                $alerta = view("alertas/emision");
                //alerta de confirmacion
                session()->setFlashdata('alerta', $alerta);
                //ir a los registros
                return redirect()->to(site_url("emisiones"));
            } else {
                session()->setFlashdata('alerta', "Ha ocurrido un error.");
            }
        }
        return view('emisiones/vida', ["cotizacion" => $cotizacion]);
    }
}
