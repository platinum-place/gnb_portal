<?php

namespace App\Controllers;

use App\Libraries\Login as LibrariesLogin;
use App\Models\Usuario;

class Login extends BaseController
{
    public function index()
    {
        if ($this->request->getPost()) {
            $libreria = new LibrariesLogin;
            $libreria->ingresar($this->request->getPost("correo"), $this->request->getPost("pass"));
            //validar si creo alguna sesion
            if (!session("usuario")) {
                //alerta que dara en caso de no encontrar ningun resultado
                session()->setFlashdata('alerta', 'Usuario o contraseña incorrectos.');
                return redirect()->to(site_url("login"));
            } else {
                return redirect()->to(site_url());
            }
        }
        return view('login');
    }

    public function editar()
    {
        $libreria = new LibrariesLogin;
        $libreria->editar($this->request->getPost("pass"));
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
