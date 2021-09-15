<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Login extends BaseController
{
    protected $zoho;

    function __construct()
    {
        $this->zoho = new Zoho;
    }

    public function index()
    {
        if ($this->request->getPost()) {
            $criterio = "((Email:equals:" . $this->request->getPost("correo") . ") and (Contrase_a:equals:" . $this->request->getPost("pass") . "))";
            $usuarios = $this->zoho->searchRecordsByCriteria("Contacts", $criterio, 1, 1);
            //buscar el todos los usuarios con el correo y contraseña sean iguales
            //los correos son campos unicos en el crm
            foreach ((array)$usuarios as $usuario) {
                $usuario_ingresado = $usuario;
            }
            //validar si encontro algun usuario
            if (empty($usuario_ingresado)) {
                //alerta que dara en caso de no encontrar ningun resultado
                session()->setFlashdata('alerta', 'Usuario o contraseña incorrectos.');
                return redirect()->to(site_url("login"));
            } else {
                //el objeto con las propiedades de la api pasa a ser una sesion
                session()->set('usuario', $usuario_ingresado);
                return redirect()->to(site_url());
            }
        }
        return view('login');
    }

    public function editar()
    {
        $this->zoho->update("Contacts", session('usuario')->getEntityId(), ["Contrase_a" => $this->request->getPost("pass")]);
        //alerta
        session()->setFlashdata('alerta', 'La contraseña ha sido actualizada.');
        //recargar la pagina para limpiar el post
        return redirect()->to(site_url());
    }

    public function salir()
    {
        //eliminar todas las sesiones
        session()->destroy();
        //redirigir al login
        return redirect()->to(site_url("login"));
    }
}
