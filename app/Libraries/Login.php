<?php

namespace App\Libraries;

class Login extends Zoho
{
    public function ingresar($correo, $pass)
    {
        $criterio = "((Email:equals:$correo) and (Contrase_a:equals:$pass))";
        $usuarios = $this->searchRecordsByCriteria("Contacts", $criterio, 1, 1);
        //buscar el todos los usuarios con el correo y contraseña sean iguales
        //los correos son campos unicos en el crm
        foreach ((array)$usuarios as $usuario) {
            //el objeto con las propiedades de la api pasa a ser una sesion
            session()->set('usuario', $usuario);
        }
    }

    public function editar($pass)
    {
        $this->update("Contacts", session('usuario')->getEntityId(), ["Contrase_a" => $pass]);
    }
}
