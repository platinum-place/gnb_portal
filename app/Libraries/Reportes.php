<?php

namespace App\Libraries;

use App\Models\Reporte;

class Reportes extends Zoho
{
    //verifica si existen reportes del tipo definido
    public function emisiones_existentes(Reporte $reporte)
    {
        //en caso de que el usuario sea admin
        if (session('usuario')->getFieldValue("Title") == "Administrador") {
            $criteria = "((Tipo_portal:equals:" . $reporte->tipo . ") and (Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . "))";
        } else {
            $criteria = "((Tipo_portal:equals:" . $reporte->tipo . ") and (Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . ") and (Contact_Name:equals:" . session('usuario')->getEntityId() . "))";
        }

        //verifica si existen reportes
        //en caso de si haber emisiones, el array de emisiones ya tendra la primera pagina de objetos
        //la libreria no importa porque solo es necesario la extension de la api zoho
        $pag = 1;

        //rellenar el array con todos los objetos posibles
        do {
            //contamos la cantidad de objetos
            $cantidad_actual = count($reporte->emisiones);

            //si no existe una segunda pagina de objetos, entonces ya tendran los necesarios
            //la libreria no importa porque solo es necesario la extension de la api zoho
            if ($emisiones = $this->searchRecordsByCriteria("Deals", $criteria, $pag)) {
                //actumula el array debajo del primer array
                $reporte->emisiones = array_merge($reporte->emisiones, $emisiones);
            }

            //volveos a contar los objetos
            $cantidad_aumentada = count($reporte->emisiones);

            //si el array aumento significa que existen mas objetos que buscar
            //si no debemos salir
            if ($cantidad_aumentada > $cantidad_actual) {
                $pag++;
            } else {
                $pag = 0;
            }
        } while ($pag > 0);
    }
}
