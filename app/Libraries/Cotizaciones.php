<?php

namespace App\Libraries;

use zcrmsdk\crm\exception\ZCRMException;

class Cotizaciones extends Zoho
{
    public function lista_cotizaciones(): ?array
    {
        if (session('admin') == true) {
            $criterio = "((Account_Name:equals:" . session('cuenta_id') . ") and (Quote_Stage:starts_with:C))";
        } else {
            $criterio = "((Account_Name:equals:" . session('cuenta_id') . ") and (Contact_Name:equals:" . session('usuario_id') . ") and (Quote_Stage:starts_with:C))";
        }

        return $this->searchRecordsByCriteria("Quotes", $criterio);
    }

    public function lista_emisiones(): ?array
    {
        if (session('admin') == true) {
            $criterio = "((Account_Name:equals:" . session('cuenta_id') . ") and (Quote_Stage:starts_with:E))";
        } else {
            $criterio = "((Account_Name:equals:" . session('cuenta_id') . ") and (Contact_Name:equals:" . session('usuario_id') . ") and (Quote_Stage:starts_with:E))";
        }

        return $this->searchRecordsByCriteria("Quotes", $criterio);
    }

    /**
     * @throws ZCRMException
     */
    public function actualizar_cotizacion($cotizacion, $planid)
    {
        // obtener los datos del plan elegido
        foreach ($cotizacion->getLineItems() as $lineItem) {
            if ($planid == $lineItem->getProduct()->getEntityId()) {
                $total = $lineItem->getNetTotal();
                $planid = $lineItem->getProduct()->getEntityId();
                $neta = $lineItem->getNetTotal() / 1.16;
                $isc = $total - $neta;

                $cambios = [
                    "Prima" => round($total, 2),
                    "Prima_neta" => round($neta, 2),
                    "ISC" => round($isc, 2),
                    "Coberturas" => $planid,
                    "Quote_Stage" => "Emitida",
                    "Vigencia_desde" => date("Y-m-d"),
                    "Valid_Till" => date("Y-m-d", strtotime(date("Y-m-d") . "+ 1 years"))
                ];

                $this->update("Quotes", $cotizacion->getEntityId(), $cambios);
            }
        }
    }

    public function adjuntar_archivo($documentos, $id)
    {
        foreach ($documentos as $documento) {
            if ($documento->isValid() && !$documento->hasMoved()) {
                // subir el archivo al servidor
                $documento->move(WRITEPATH . 'uploads');
                // ruta del archivo subido
                $ruta = WRITEPATH . 'uploads/' . $documento->getClientName();
                // funcion para adjuntar el archivo
                $this->uploadAttachment("Quotes", $id, $ruta);
                // borrar el archivo del servidor local
                unlink($ruta);
            }
        }
    }
}