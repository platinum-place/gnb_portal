<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Login extends BaseController
{
    public function index()
    {
        if ($this->request->getPost()) {
            $libreria = new Zoho;
            //buscar el todos los usuarios con el correo y contraseña sean iguales
            //los correos son campos unicos en el crm
            $criteria = "((Email:equals:" . $this->request->getPost("correo") . ") and (Contrase_a:equals:" . $this->request->getPost("pass") . "))";
            $usuarios = $libreria->searchRecordsByCriteria("Contacts", $criteria, 1, 1);
            //buscar el todos los usuarios con el correo y contraseña sean iguales
            //los correos son campos unicos en el crm
            foreach ((array)$usuarios as $usuario) {
                //el objeto con las propiedades de la api pasa a ser una sesion
                session()->set('cuenta', $usuario->getFieldValue('Account_Name')->getLookupLabel());
                session()->set('cuenta_id', $usuario->getFieldValue('Account_Name')->getEntityId());
                session()->set('usuario', $usuario->getFieldValue('First_Name') . " " . $usuario->getFieldValue('Last_Name'));
                session()->set('usuario_id', $usuario->getEntityId());
                session()->set('puesto', $usuario->getFieldValue("Title"));
                //en caso de que el usuario sea admin
                if (session("puesto") == "Administrador") {
                    session()->setFlashdata('alerta', 'Has iniciado sesión como administrador. Podrás visualizar las cotizaciones y emisiones de los demás usuarios.');
                }
                return redirect()->to(site_url());
            }
            //alerta que dara en caso de no encontrar ningun resultado
            session()->setFlashdata('alerta', 'Usuario o contraseña incorrectos.');
            return redirect()->to(site_url('login'));
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
