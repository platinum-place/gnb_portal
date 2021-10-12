<?php

namespace App\Libraries;

class Login extends Zoho
{
    public function ingresar($user, $pass)
    {
        //buscar el todos los usuarios con el correo y contraseña sean iguales
        //los correos son campos unicos en el crm
        $criteria = "((Email:equals:$user) and (Contrase_a:equals:$pass))";
        $usuarios = $this->searchRecordsByCriteria("Contacts", $criteria, 1, 1);
        //buscar el todos los usuarios con el correo y contraseña sean iguales
        //los correos son campos unicos en el crm
        foreach ((array)$usuarios as $usuario) {
            //el objeto con las propiedades de la api pasa a ser una sesion
            session()->set('cuenta', $usuario->getFieldValue('Account_Name')->getLookupLabel());
            session()->set('cuenta_id', $usuario->getFieldValue('Account_Name')->getEntityId());
            session()->set('usuario', $usuario->getFieldValue('First_Name') . " " . $usuario->getFieldValue('Last_Name'));
            session()->set('usuario_id', $usuario->getEntityId());
            session()->set('puesto', $usuario->getFieldValue("Title"));
        }
    }
}
