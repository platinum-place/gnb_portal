<?php

namespace App\Controllers;

use App\Models\Usuario;

class Login extends BaseController
{
    protected $usuario;

    function __construct()
    {
        $this->usuario = new Usuario;
    }

    public function index()
    {
        if ($this->request->getPost()) {
            $this->usuario->colocar_credenciales($this->request->getPost("correo"), $this->request->getPost("pass"));
            if ($this->usuario->validar()) {
                session()->set('usuario', $this->usuario->crm);
                return redirect()->to(site_url());
            } else {
                //alerta que dara en caso de no encontrar ningun resultado
                session()->setFlashdata('alerta', 'Usuario o contraseña incorrectos.');
                return redirect()->to(site_url("login"));
            }
        }
        return view('login');
    }

    public function editar()
    {
        $this->usuario->colocar_credenciales(session("usuario")->getFieldValue('Email'), $this->request->getPost("pass"));
        $this->usuario->cambiar_clave();
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
