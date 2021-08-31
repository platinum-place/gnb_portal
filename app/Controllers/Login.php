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
            //buscar el todos los usuarios con el correo y contraseña sean iguales
            //los correos son campos unicos en el crm
            $criterio = "((Email:equals:" . $this->request->getPost("user") . ")and(Contrase_a:equals:" . $this->request->getPost("pass") . "))";
            $usuarios = $this->zoho->searchRecordsByCriteria("Contacts", $criterio);

            //el resultado siempre son dos
            //o encuentra el usuario, que solo sera uno, en este caso se crear la sesion
            //o no encuentra ningun valor
            foreach ((array)$usuarios as $usuario) {
                session()->set('usuario', $usuario);
                return redirect()->to(site_url());
            }

            //alerta que dara en caso de no encontrar ningun resultado
            session()->setFlashdata('alerta', 'Usuario o contraseña incorrectos.');
        }

        return view('login/index');
    }

    public function editar()
    {
        if ($this->request->getPost()) {
            //cambiar solo la contrase del usuario en el crm
            $this->zoho->update("Contacts", session("usuario")->getEntityId(), ["Contrase_a" => $this->request->getPost("nuevo")]);

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
