<?php

namespace App\Libraries\Reporte;

use App\Libraries\Cotizaciones;

class Reporte
{
    public function generar_reporte(Cotizaciones $cotizaciones, $plan, $desde, $hasta): ?string
    {
        $emisiones = $cotizaciones->lista_emisiones();

        if (empty($emisiones)) {
            return null;
        }

        switch ($plan) {
            case 'Auto':
                $reporte = new ReporteAuto();
                break;

            case 'Vida':
                $reporte = new ReporteVida();
                break;

            case 'Vida/Desempleo':
                $reporte = new ReporteDesempleo();
                break;

            case 'Seguro Incendio Hipotecario':
                $reporte = new ReporteIncendio();
                break;
        }

        if (!empty($reporte)) {
            return $reporte->generar_reporte($emisiones, $desde, $hasta);
        }

        return null;
    }
}