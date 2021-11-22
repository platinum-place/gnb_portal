<?php

namespace App\Controllers;

use App\Libraries\Zoho;
use App\Models\Contacto;

class Login extends BaseController
{
    public function index()
    {
        if ($this->request->getPost()) {
            $usuario = new Contacto;
            $usuario->ingresar($this->request->getPost("correo"), $this->request->getPost("pass"));

            if (!empty($usuario->zoho)) {
                //el objeto con las propiedades de la api pasa a ser una sesion
                session()->set('cuenta', $usuario->zoho->getFieldValue('Account_Name')->getLookupLabel());
                session()->set('cuenta_id', $usuario->zoho->getFieldValue('Account_Name')->getEntityId());
                session()->set('usuario', $usuario->zoho->getFieldValue('First_Name') . " " . $usuario->zoho->getFieldValue('Last_Name'));
                session()->set('usuario_id', $usuario->zoho->getEntityId());

                if ($usuario->zoho->getFieldValue("Title") == "Administrador") {
                    session()->set('admin', true);
                    session()->setFlashdata('alerta', 'Has iniciado sesión como administrador. Podrás visualizar las cotizaciones y emisiones de los demás usuarios.');
                }

                return redirect()->to(site_url());
            } else {
                //alerta que dara en caso de no encontrar ningun resultado
                session()->setFlashdata('alerta', 'Usuario o contraseña incorrectos.');
                return redirect()->to(site_url('login'));
            }
        }

        return view('login');
    }

    public function editar()
    {
        $libreria = new Zoho;
        $libreria->update("Contacts", session('usuario_id'), ["Contrase_a" => $this->request->getPost("pass")]);
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
