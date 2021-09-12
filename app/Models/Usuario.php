<?php

namespace App\Models;

use App\Libraries\Zoho;

class Usuario extends Zoho
{
    protected $email, $pass;
    public $crm;

    public function colocar_credenciales($email, $pass)
    {
        $this->email = $email;
        $this->pass = $pass;
    }

    public function validar()
    {
        $criterio = "((Email:equals:" . $this->email . ") and (Contrase_a:equals:" . $this->pass . "))";
        $usuarios = $this->searchRecordsByCriteria("Contacts", $criterio, 1, 1);
        //buscar el todos los usuarios con el correo y contraseña sean iguales
        //los correos son campos unicos en el crm
        foreach ($usuarios as $usuario) {
            $this->crm = $usuario;
        }
        //validar si encontro algun usuario
        if (empty($this->crm)) {
            return false;
        } else {
            return true;
        }
    }

    public function cambiar_clave()
    {
        $this->update("Contacts", session('usuario')->getEntityId(), ["Contrase_a" => $this->pass]);
    }
}
