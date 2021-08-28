<?php

namespace App\Controllers;

use App\Libraries\Clave;
use App\Libraries\Login as LibrariesLogin;
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
            $libreria = new LibrariesLogin($this->zoho);

            $libreria->usuario($this->request->getPost("user"), $this->request->getPost("pass"));

            if ($libreria->validar()) {
                $libreria->ingresar();
                return redirect()->to(site_url());
            }

            session()->setFlashdata('alerta', 'Ha ocurrido un error');
        }
        return view('login/index');
    }

    public function editar()
    {
        if ($this->request->getPost()) {
            $libreria = new Clave($this->zoho);
            $libreria->establecer($this->request->getPost("actual"), $this->request->getPost("nuevo"));

            if ($libreria->validar()) {
                $libreria->cambiar();

                session()->setFlashdata('alerta', 'La contraseña actualizada.');
                return redirect()->to(site_url("clave"));
            } else {
                session()->setFlashdata('alerta', 'La contraseña actual no coincide.');
                return redirect()->to(site_url("clave"));
            }
        }
        return view('login/editar');
    }

    public function salir()
    {
        session()->destroy();
        return redirect()->to(site_url());
    }
}
