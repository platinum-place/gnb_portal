<?php

namespace App\Libraries;

class Ingresar_usuario extends Zoho
{
    public function validar_en_sistema($email, $pass)
    {
        $criterio = "((Email:equals:$email) and (Contrase_a:equals:$pass))";
        $usuarios = $this->searchRecordsByCriteria("Contacts", $criterio, 1, 1);
        //buscar el todos los usuarios con el correo y contraseña sean iguales
        //los correos son campos unicos en el crm
        foreach ($usuarios as $usuario) {
            return $usuario;
        }
    }
}
