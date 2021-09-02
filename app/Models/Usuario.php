<?php

namespace App\Models;

use App\Libraries\Zoho;

class Usuario extends Zoho
{
    protected $email;
    protected $pass;

    public function ingresar($email, $pass)
    {
        $this->email = $email;
        $this->pass = $pass;
    }

    public function validar()
    {
        //buscar el todos los usuarios con el correo y contraseña sean iguales
        //los correos son campos unicos en el crm
        $criterio = "((Email:equals:" . $this->email . ") and (Contrase_a:equals:" . $this->pass . "))";
        $usuarios = $this->searchRecordsByCriteria("Contacts", $criterio);
        //el resultado siempre son dos
        //o encuentra el usuario, que solo sera uno, en este caso se crear la sesion
        //o no encuentra ningun valor
        foreach ((array)$usuarios as $usuario) {
            session()->set('usuario', $usuario);
            return true;
        }
    }

    public function cambiarclave()
    {
        //cambiar solo la contrase del usuario en el crm
        $this->update("Contacts", session("usuario")->getEntityId(), ["Contrase_a" => $this->pass]);
    }
}
