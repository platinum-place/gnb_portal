<?php

namespace App\Controllers;

use App\Models\Marca;
use App\Models\Modelo;

class Cotizaciones extends BaseController
{
    protected $marca;
    protected $modelo;

    function __construct()
    {
        $this->marca = new Marca;
        $this->modelo = new Modelo;
    }

    public function index()
    {
        $marcas = $this->marca->lista_marcas();
        asort($marcas);
        return view("cotizaciones/index", ["titulo" => "Cotizar", "marcas" => $marcas]);
    }
}
