<?php

namespace App\Controllers;

use App\Libraries\Zoho;
use App\Models\Usuario;

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
            $usuario = new Usuario;
            $usuario->ingresar($this->request->getPost("user"), $this->request->getPost("pass"));
            if ($usuario->validar()) {
                return redirect()->to(site_url());
            }
            //alerta que dara en caso de no encontrar ningun resultado
            session()->setFlashdata('alerta', 'Usuario o contraseña incorrectos.');
            return redirect()->to(site_url("login"));
        }
        return view('login/index');
    }

    public function editar()
    {
        if ($this->request->getPost()) {
            $usuario = new Usuario;
            $usuario->ingresar(session("usuario")->getFieldValue("Email	"), $this->request->getPost("nuevo"));
            $usuario->cambiarclave();
            //alerta
            session()->setFlashdata('alerta', 'La contraseña ha sido actualizada.');
            //recargar la pagina para limpiar el post
            return redirect()->to(site_url("login/editar"));
        }
        return view('login/editar');
    }

    public function salir()
    {
        //eliminar todas las sesiones
        session()->destroy();
        //redirigir al login
        return redirect()->to(site_url("login"));
    }
}
