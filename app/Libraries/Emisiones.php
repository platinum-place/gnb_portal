<?php

namespace App\Libraries;

class Emisiones extends Zoho
{
    public function lista($pag = 1, $cantidad = 200)
    {
        //en caso de que el usuario sea admin
        if (session('usuario')->getFieldValue("Title") == "Administrador") {
            $criterio = "Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId();
        } else {
            $criterio = "((Account_Name:equals:" . session('usuario')->getFieldValue("Account_Name")->getEntityId() . ") and (Contact_Name:equals:" . session('usuario')->getEntityId() . "))";
        }

        //retornar todas las emisiones
        return $this->searchRecordsByCriteria("Deals", $criterio, $pag, $cantidad);
    }

    public function crear_cliente($cotizacion)
    {
        $cliente = [
            "First_Name" => $cotizacion->getFieldValue("Nombre"),
            "Last_Name" => $cotizacion->getFieldValue("Apellido"),
            "Fecha_de_nacimiento" => $cotizacion->getFieldValue("Fecha_de_nacimiento"),
            "RNC_C_dula" => $cotizacion->getFieldValue("RNC_C_dula"),
            "Email" => $cotizacion->getFieldValue("Correo_electr_nico"),
            "Street" => $cotizacion->getFieldValue("Direcci_n"),
            "Mobile" => $cotizacion->getFieldValue("Tel_Celular"),
            "Phone" => $cotizacion->getFieldValue("Tel_Residencia"),
            "Fax" => $cotizacion->getFieldValue("Tel_Trabajo")
        ];
        return $this->createRecords("Leads", $cliente);
    }

    public function crear_codeudor($cotizacion)
    {
        $codeudor = [
            "First_Name" => $cotizacion->getFieldValue("Nombre_codeudor"),
            "Last_Name" => $cotizacion->getFieldValue("Apellido_codeudor"),
            "Mobile" => $cotizacion->getFieldValue("Tel_Celular_codeudor"),
            "Phone" => $cotizacion->getFieldValue("Tel_Residencia_codeudor"),
            "Fax" => $cotizacion->getFieldValue("Tel_Trabajo_codeudor"),
            "RNC_C_dula" => $cotizacion->getFieldValue("RNC_C_dula_codeudor"),
            "Fecha_de_nacimiento" => $cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"),
            "Street" => $cotizacion->getFieldValue("Direcci_n_codeudor"),
            "Email" => $cotizacion->getFieldValue("Correo_electr_nico_codeudor"),
        ];
        return $this->createRecords("Leads", $codeudor);
    }

    public function crear_emision($cotizacion, $total, $planid, $clienteid, $codeudorid)
    {
        $emision = [
            "Deal_Name" => "Emisión desde portal IT",
            "Fecha_de_inicio" => date("Y-m-d"),
            "Closing_Date" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years")),
            "Amount" => round($total, 2),
            "Cuota" => $cotizacion->getFieldValue("Cuota"),
            "Tipo_portal" =>  $cotizacion->getFieldValue("Tipo"),
            "Type" =>  $cotizacion->getFieldValue("Tipo_crm"),
            "Coberturas" =>  $planid,
            "Account_Name" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
            "Contact_Name" =>  session("usuario")->getEntityId(),
            "Plan" => $cotizacion->getFieldValue("Plan"),
            "Suma_asegurada" =>  $cotizacion->getFieldValue("Suma_asegurada"),
            "Stage" => "Pendiente",
            "Plazo" => $cotizacion->getFieldValue("Plazo"),
            "Cliente" => $clienteid,
            "Codeudor" => $codeudorid,
            "Lead_Source" => "Portal",
        ];
        return $this->createRecords("Deals", $emision);
    }

    public function crear_bien($cotizacion, $emisionid)
    {
        $bien = [
            "A_o" => $cotizacion->getFieldValue("A_o"),
            "Marca" => $cotizacion->getFieldValue("Marca")->getLookupLabel(),
            "Modelo" => $cotizacion->getFieldValue("Modelo")->getLookupLabel(),
            "Tipo" => $cotizacion->getFieldValue("Tipo_veh_culo"),
            "Name" => $cotizacion->getFieldValue("Chasis"),
            "Color" => $cotizacion->getFieldValue("Color"),
            "Placa" => $cotizacion->getFieldValue("Placa"),
            "Aseguradora" => session('usuario')->getFieldValue("Account_Name")->getEntityId(),
            "Trato" =>  $emisionid,
            "Estado" => "2",
        ];
        return $this->createRecords("Bienes", $bien);
    }
}
