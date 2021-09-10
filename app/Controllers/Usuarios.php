<?php

namespace App\Controllers;

use App\Libraries\Editar_usuario;

class Usuarios extends BaseController
{
    protected $editar;

    function __construct()
    {
        $this->editar = new Editar_usuario;
    }

    public function editar()
    {
        $this->editar->cambiar_clave($this->request->getPost("pass"));
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
