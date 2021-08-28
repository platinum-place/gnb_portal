<?php

namespace App\Libraries;

class Login
{
    //propiedades de la clase
    //son de un solo uso
    protected $user;
    protected $pass;
    protected $usuario;
    protected $zoho;

    function __construct(Zoho $zoho)
    {
        $this->zoho = $zoho;
    }

    //toma el usuario y contraseña de formulario
    public function usuario($user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
    }

    //busca los valores en el crm
    public function validar()
    {
        $criterio = "((Email:equals:" . $this->user . ")and(Contrase_a:equals:" . $this->pass . "))";
        $usuarios = $this->zoho->searchRecordsByCriteria("Contacts", $criterio);
        foreach ((array)$usuarios as $usuario) {
            $this->usuario = $usuario;
            return true;
        }
    }

    //toma el objeto de la clase y lo pasa a una variable de sesion
    public function ingresar()
    {
        session()->set('usuario', $this->usuario);
    }
}
