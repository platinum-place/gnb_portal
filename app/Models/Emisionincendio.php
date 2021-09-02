<?php

namespace App\Models;

class Emisionincendio extends Emision
{
    protected $propiedad;
    protected $prestamo;
    protected $direccion;
    protected $construccion;
    protected $riesgo;
    protected $plazo;

    public function establecer_emision($propiedad, $prestamo, $direccion, $construccion, $riesgo, $plazo)
    {
        $this->propiedad = $propiedad;
        $this->prestamo = $prestamo;
        $this->direccion = $direccion;
        $this->construccion = $construccion;
        $this->riesgo = $riesgo;
        $this->plazo = $plazo;
    }

    public function crear_emision($request)
    {
        //array que representa al registro que se creara
        //necesita los valores de la cotizacion y el formulario
        //algunos valores, como el plan y la fecha, son fijos
        $emision = [
            "Deal_Name" => "Emisión Póliza Incendio Hipotecario",
            "Closing_Date" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years")),
            "Amount" => round($this->prima),
            "Type" => "Incendio",
            "Account_Name" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
            "Contact_Name" =>  session("usuario")->getEntityId(),
            "P_liza" => "En trámite",
            "Aseguradora" => $this->aseguradoraid,
            "Estado" => "Activo",
            "Plan" => "Incendio Hipotecario",
            "Suma_asegurada" => $this->propiedad,
            "Prestamo" => $this->prestamo,
            "Nombre" => $request->getPost("nombre"),
            "Apellido" => $request->getPost("apellido"),
            "Identificaci_n" => $request->getPost("id"),
            "Fecha_de_nacimiento" => $request->getPost("fecha"),
            "Correo_electr_nico" => $request->getPost("correo"),
            "Tel_Residencia" => $request->getPost("tel1"),
            "Tel_Celular" => $request->getPost("tel2"),
            "Tel_Trabajo" => $request->getPost("tel3"),
            "Direcci_n" => $request->getPost("direccion"),
            "Coberturas" => $this->coberturaid,
            "Stage" => "Proceso de validación",
            "Tipo_de_Construcci_n" => $this->construccion,
            "Tipo_de_Riesgo" => $this->riesgo,
            "Plazo" => $this->plazo
        ];
        //crear registro en crm
        $this->emisionid = $this->createRecords("Deals", $emision);
        return true;
    }
}
