<?php

namespace App\Models;

use App\Libraries\Zoho;

class Contacto extends Cuenta
{
    public $zoho;

    public function ingresar($email, $pass)
    {
        $libreria = new Zoho;
        //buscar el todos los usuarios con el correo y contraseña sean iguales
        //los correos son campos unicos en el crm
        $criteria = "((Email:equals:$email) and (Contrase_a:equals:$pass))";
        $usuarios = $libreria->searchRecordsByCriteria("Contacts", $criteria, 1, 1);
        //buscar el todos los usuarios con el correo y contraseña sean iguales
        //los correos son campos unicos en el crm
        foreach ((array)$usuarios as $usuario) {
            $this->zoho = $usuario;
        }
    }
}
