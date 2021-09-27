<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Login extends BaseController
{
    public function index()
    {
        if ($this->request->getPost()) {
            $libreria = new Zoho;
            $criterio = "((Email:equals:" . $this->request->getPost("correo") . ") and (Contrase_a:equals:" . $this->request->getPost("pass") . "))";
            $usuarios = $libreria->searchRecordsByCriteria("Contacts", $criterio, 1, 1);
            
            //buscar el todos los usuarios con el correo y contraseña sean iguales
            //los correos son campos unicos en el crm
            foreach ((array)$usuarios as $usuario) {
                //el objeto con las propiedades de la api pasa a ser una sesion
                session()->set('usuario', $usuario);

                //en caso de que el usuario sea admin
                if ($usuario->getFieldValue("Title") == "Administrador") {
                    session()->setFlashdata('alerta', 'Has iniciado sesión como administrador. Podrás visualizar las cotizaciones y emisiones de los demás usuarios.');
                }

                return redirect()->to(site_url());
            }

            //validar si creo alguna sesion
            if (!session("usuario")) {
                //alerta que dara en caso de no encontrar ningun resultado
                session()->setFlashdata('alerta', 'Usuario o contraseña incorrectos.');
                return redirect()->to(site_url("login"));
            }
        }

        return view('login/index');
    }

    public function editar()
    {
        $libreria = new Zoho;
        $libreria->update("Contacts", session('usuario')->getEntityId(), ["Contrase_a" => $this->request->getPost("pass")]);
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
