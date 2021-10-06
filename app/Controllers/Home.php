<?php

namespace App\Controllers;

use App\Libraries\Emisiones;

class Home extends BaseController
{
    protected $libreria;

    function __construct()
    {
        //cargar la libreria para hacer uso de una funcion de la api
        $this->libreria = new Emisiones;
    }

    public function index()
    {
        $lista = array();
        $polizas = 0;
        $vencidas = 0;

        //obtener las emisiones
        $emisiones = $this->libreria->lista();

        foreach ((array)$emisiones as $emision) {
            //filtrar por  mes y año actual
            if (date("Y-m", strtotime($emision->getFieldValue('Fecha_de_inicio'))) == date("Y-m")) {
                //detalles de las coberturas
                $coberturas = $this->libreria->getRecord("Products", $emision->getFieldValue('Coberturas')->getEntityId());
                //contador del nombre de las aseguradoras
                $lista[] =  $coberturas->getFieldValue('Vendor_Name')->getLookupLabel();

                //contador en general
                $polizas++;
            }

            //contador para las emisiones que vencen en el mes y año actual
            if (date("Y-m", strtotime($emision->getFieldValue('Closing_Date'))) == date("Y-m")) {
                $vencidas++;
            }
        }

        return view('home/index', [
            "titulo" => "Panel de Control",
            "lista" => array_count_values($lista),
            "polizas" => $polizas,
            "vencidas" => $vencidas,
        ]);
    }

    public function mes()
    {
        //lista de emisiones
        $emisiones = $this->libreria->lista();
        return view("home/mes", ["titulo" => "Emisiones Del Mes", "emisiones" => $emisiones]);
    }

    public function vencidas()
    {
        //lista de emisiones
        $emisiones = $this->libreria->lista();
        return view("home/vencidas", ["titulo" => "Emisiones En Vencimiento Este Mes", "emisiones" => $emisiones]);
    }
}
