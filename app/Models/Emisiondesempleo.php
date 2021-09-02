<?php

namespace App\Models;

class Emisiondesempleo extends Emision
{
    protected $suma;
    protected $cuota;
    protected $fecha;
    protected $plazo;

    public function establecer_emision($suma, $cuota, $fecha, $plazo)
    {
        $this->suma = $suma;
        $this->cuota = $cuota;
        $this->fecha = $fecha;
        $this->plazo = $plazo;
    }

    public function obtener_coberturas()
    {
        //planes o coberturas de plan elegido, solo es un registro,
        //debe haber un registro, si no, debe ser posible avanzar
        //estan ubicados en el modulo de productos
        $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Vendor_Name:equals:" . $this->aseguradoraid . ") and (Product_Category:equals:Desempleo))";
        $coberturas = $this->zoho->searchRecordsByCriteria("Products", $criterio);
        foreach ($coberturas as $cobertura) {
            $this->coberturaid = $cobertura->getEntityId();
        }
    }

    public function crear_emision($request)
    {
        //array que representa al registro que se creara
        //necesita los valores de la cotizacion y el formulario
        //algunos valores, como el plan y la fecha, son fijos
        $emision = [
            "Deal_Name" => "Emisión de Póliza Plan Vida/Desempleo",
            "Closing_Date" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years")),
            "Amount" => round($this->prima),
            "Type" => "Desempleo",
            "Account_Name" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
            "Contact_Name" =>  session("usuario")->getEntityId(),
            "P_liza" => "En trámite",
            "Aseguradora" => $this->aseguradoraid,
            "Estado" => "Activo",
            "Plan" => "Vida/Desempleo",
            "Suma_asegurada" => $this->suma,
            "Cuota" => $this->cuota,
            "Nombre" => $request->getPost("nombre"),
            "Apellido" => $request->getPost("apellido"),
            "Identificaci_n" => $request->getPost("id"),
            "Fecha_de_nacimiento" => $this->fecha,
            "Correo_electr_nico" => $request->getPost("correo"),
            "Tel_Residencia" => $request->getPost("tel1"),
            "Tel_Celular" => $request->getPost("tel2"),
            "Tel_Trabajo" => $request->getPost("tel3"),
            "Direcci_n" => $request->getPost("direccion"),
            "Coberturas" => $this->coberturaid,
            "Stage" => "Proceso de validación",
            "Plazo" => $this->plazo
        ];
        //crear registro en crm
        $this->emisionid = $this->zoho->createRecords("Deals", $emision);
        return true;
    }
}
