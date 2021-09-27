<?php

namespace App\Controllers;

use App\Libraries\Emisiones;

class Home extends BaseController
{
    public function index()
    {
        $libreria = new Emisiones;

        $lista = array();
        $polizas = 0;
        $vencidas = 0;
        $pendiente = 0;

        //obtener las emisiones
        $emisiones = $libreria->lista();

        foreach ((array)$emisiones as $emision) {
            //filtrar por  mes y año actual
            if (date("Y-m", strtotime($emision->getCreatedTime())) == date("Y-m")) {
                foreach ($emision->getLineItems() as $lineItem) {
                    //contador del nombre de las aseguradoras
                    $lista[] =  $lineItem->getDescription();
                }

                //contador en general
                $polizas++;

                //contador para las emisiones que aun no ha sido revisadas
                if ($emision->getFieldValue('Status') == "Pendiente") {
                    $pendiente++;
                }
            }

            //contador para las emisiones que vencen en el mes y año actual
            if (date("Y-m", strtotime($emision->getFieldValue('Due_Date'))) == date("Y-m")) {
                $vencidas++;
            }
        }

        return view('home/index', [
            "titulo" => "Panel de Control",
            "lista" => array_count_values($lista),
            "polizas" => $polizas,
            "vencidas" => $vencidas,
            "pendiente" => $pendiente
        ]);
    }

    public function mes()
    {
        //libreria para emisiones
        $libreria = new Emisiones;
        //lista de emisiones
        $emisiones = $libreria->lista();

        return view("home/mes", [
            "titulo" => "Emisiones Del Mes",
            "emisiones" => $emisiones
        ]);
    }

    public function vencidas()
    {
        //libreria para emisiones
        $libreria = new Emisiones;
        //lista de emisiones
        $emisiones = $libreria->lista();

        return view("home/vencidas", [
            "titulo" => "Emisiones En Vencimiento Este Mes",
            "emisiones" => $emisiones
        ]);
    }
}
