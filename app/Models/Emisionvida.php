<?php

namespace App\Models;

class Emisionvida extends Emision
{
    protected $suma;
    protected $fecha_deudor;
    protected $fecha_codeudor;
    protected $construccion;
    protected $riesgo;
    protected $plazo;

    public function establecer_emision($suma, $fecha_deudor, $fecha_codeudor, $plazo)
    {
        $this->suma = $suma;
        $this->fecha_deudor = $fecha_deudor;
        $this->fecha_codeudor = $fecha_codeudor;
        $this->plazo = $plazo;
    }

    public function crear_emision($request)
    {
        //array que representa al registro que se creara
        //necesita los valores de la cotizacion y el formulario
        //algunos valores, como el plan y la fecha, son fijos
        $emision = [
            "Deal_Name" => "Emisión Póliza Plan Vida",
            "Closing_Date" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years")),
            "Amount" => round($this->prima),
            "Type" => "Vida",
            "Account_Name" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
            "Contact_Name" =>  session("usuario")->getEntityId(),
            "P_liza" => "En trámite",
            "Aseguradora" => $this->aseguradoraid,
            "Estado" => "Activo",
            "Plan" => "Vida",
            "Suma_asegurada" => $this->suma,
            "Nombre" => $request->getPost("nombre"),
            "Apellido" => $request->getPost("apellido"),
            "Identificaci_n" => $request->getPost("id"),
            "Fecha_de_nacimiento" => $request->getPost("fecha"),
            "Correo_electr_nico" => $request->getPost("correo"),
            "Tel_Residencia" => $request->getPost("tel1"),
            "Tel_Celular" => $request->getPost("tel2"),
            "Tel_Trabajo" => $request->getPost("tel3"),
            "Direcci_n" => $request->getPost("direccion"),
            "Nombre_codeudor" => $request->getPost("nombre_codeudor"),
            "Apellido_codeudor" => $request->getPost("apellido_codeudor"),
            "Identificaci_n_codeudor" => $request->getPost("id_codeudor"),
            "Fecha_de_nacimiento_codeudor" => $request->getPost("fecha_codeudor"),
            "Correo_electr_nico_codeudor" => $request->getPost("correo_codeudor"),
            "Tel_Residencia_codeudor" => $request->getPost("tel1_codeudor"),
            "Tel_Celular_codeudor" => $request->getPost("tel2_codeudor"),
            "Tel_Trabajo_codeudor" => $request->getPost("tel3_codeudor"),
            "Direcci_n" => $request->getPost("direccion"),
            "Coberturas" => $this->coberturaid,
            "Stage" => "Proceso de validación",
            "Plazo" => $this->plazo
        ];
        //crear registro en crm
        $this->emisionid = $this->createRecords("Deals", $emision);
        return true;
    }
}
