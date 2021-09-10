<?php

namespace App\Controllers;

class Cotizaciones extends BaseController
{
    public function index()
    {
        return view("cotizaciones/index",["titulo"=>"Cotizar"]);
    }
}
