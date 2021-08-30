<?php

namespace App\Libraries;

class Emisiones
{
    protected $zoho;

    function __construct(Zoho $zoho)
    {
        $this->zoho = $zoho;
    }

    public function emitirincendio($detalles, $cliente, $aseguradoraid, $total, $ruta)
    {
        $coberturaid = null;

        $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Vendor_Name:equals:$aseguradoraid) and (Product_Category:equals:Incendio))";
        $coberturas = $this->zoho->searchRecordsByCriteria("Products", $criterio);
        foreach ($coberturas as $cobertura) {
            $coberturaid = $cobertura->getEntityId();
        }

        $emision = [
            "Deal_Name" => "Emisión Póliza Incendio Hipotecario",
            "Closing_Date" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years")),
            "Amount" => $total,
            "Type" => "Incendio",
            "Account_Name" => session("usuario")->getFieldValue("Account_Name")->getEntityId(),
            "Contact_Name" =>  session("usuario")->getEntityId(),
            "P_liza" => "En trámite",
            "Aseguradora" => $aseguradoraid,
            "Estado" => "Activo",
            "Plan" => "Incendio Hipotecario",
            "Suma_asegurada" => $detalles["propiedad"],
            "Prestamo" => $detalles["prestamo"],
            "Nombre" => $cliente["nombre"],
            "Apellido" => $cliente["apellido"],
            "Identificaci_n" => $cliente["id"],
            "Fecha_de_nacimiento" => $cliente["fecha"],
            "Correo_electr_nico" => $cliente["correo"],
            "Tel_Residencia" => $cliente["tel1"],
            "Tel_Celular" => $cliente["tel2"],
            "Tel_Trabajo" => $cliente["tel3"],
            "Direcci_n" => $detalles["direccion"],
            "Coberturas" => $coberturaid,
            "Stage" => "Proceso de validación"
        ];

        //crear registro en crm
        $id = $this->zoho->createRecords("Deals", $emision);

        //adjuntar documento al registro creado
        $this->zoho->uploadAttachment("Deals", $id, $ruta);

        unlink($ruta);

        return $id;
    }
}
