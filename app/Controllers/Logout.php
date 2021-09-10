<?php

namespace App\Controllers;

class Logout extends BaseController
{
    public function index()
    {
        //eliminar todas las sesiones
        session()->destroy();
        //redirigir al login
        return redirect()->to(site_url("login"));
    }
}
