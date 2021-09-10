<?php

namespace App\Controllers;

use App\Libraries\Ingresar_usuario;

class Login extends BaseController
{
    protected $ingresar;

    function __construct()
    {
        $this->ingresar = new Ingresar_usuario;
    }

    public function index()
    {
        if ($this->request->getPost()) {
            $usuario = $this->ingresar->validar_en_sistema($this->request->getPost("correo"), $this->request->getPost("pass"));
            session()->set('usuario', $usuario);
            if (!session()->get("usuario")) {
                //alerta que dara en caso de no encontrar ningun resultado
                session()->setFlashdata('alerta', 'Usuario o contraseña incorrectos.');
                return redirect()->to(site_url("login"));
            }
            return redirect()->to(site_url());
        }
        return view('login');
    }
}
