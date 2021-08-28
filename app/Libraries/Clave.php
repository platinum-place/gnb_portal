<?php

namespace App\Libraries;

class Clave
{
    protected $actual;
    protected $nueva;
    protected $zoho;

    function __construct(Zoho $zoho)
    {
        $this->zoho = $zoho;
    }

    public function establecer($actual, $nueva)
    {
        $this->actual = $actual;
        $this->nueva = $nueva;
    }

    public function validar()
    {
        if (session("usuario")->getFieldValue("Contrase_a") == $this->actual) {
            return true;
        }
    }

    public function cambiar()
    {
        $this->zoho->update("Contacts", session("usuario")->getEntityId(), ["Contrase_a" => $this->nueva]);
    }
}
