<?php

namespace App\Libraries;

class Cotizaciones extends Zoho
{
    public function lista_marcas()
    {
        return $this->getRecords("Marcas");
    }

    public function lista_modelos($marcaid, $pagina = 1)
    {
        $criterio = "Marca:equals:$marcaid";
        return $this->searchRecordsByCriteria("Modelos", $criterio, $pagina);
    }

    public function lista_recargos($marcaid, $aseguradoraid)
    {
        $criterio = "((Marca:equals:$marcaid) and (Aseguradora:equals:$aseguradoraid))";
        return $this->searchRecordsByCriteria("Recargos", $criterio, 1, 200);
    }

    public function calcular_prima_incendio($tasa, $suma)
    {
        return ($suma / 100) * ($tasa->getFieldValue('Name') / 100);
    }

    public function calcular_prima_desempleo($tasa, $suma, $cuota)
    {
        $vida = ($suma / 1000) * ($tasa->getFieldValue('Name') / 100);
        $desempleo = ($cuota / 1000) * $tasa->getFieldValue('Desempleo');
        return $vida + $desempleo;
    }

    public function calcular_prima_vida($tasa, $suma, $deudor, $codeudor)
    {
        $tasa_deudor = $tasa->getFieldValue('Name') / 100;
        $deudor = ($suma / 1000) * $tasa_deudor;
        if (!empty($edad_codeudor)) {
            $tasa_codeudor = $tasa->getFieldValue('Codeudor') / 100;
            $codeudor = ($suma / 1000) * ($tasa_codeudor - $tasa_deudor);
        }
        return $deudor + $codeudor;
    }

    public function calcular_edad($fecha)
    {
        list($ano, $mes, $dia) = explode("-", $fecha);
        $ano_diferencia  = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia   = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
            $ano_diferencia--;
        return $ano_diferencia;
    }

    public function lista_tasas($tipo)
    {
        $criterio = "((Intermediario:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Tipo:equals:$tipo))";
        return $this->searchRecordsByCriteria("Tasas", $criterio);
    }

    public function verificar_limites_deudor_codeudor($tasa, $plazo, $suma, $edad_deudor, $edad_codeudor = 0)
    {
        //verificar limite de plazo
        if ($plazo > $tasa->getFieldValue('Plazo')) {
            return "El plazo es mayor al limite establecido.";
        }
        //verificar limite suma
        if ($suma > $tasa->getFieldValue('Suma_asegurada')) {
            return "La suma asegurada es mayor al limite establecido.";
        }
        //verificar limite edad minima
        if ($edad_deudor < $tasa->getFieldValue('Edad_min')) {
            return "La edad del deudor es menor al limite permitido.";
        }
        //verificar limite edad maximo
        if ($edad_deudor > $tasa->getFieldValue('Edad_max')) {
            return "La edad del deudor es mayor al limite permitido.";
        }
        //verificar limite edad minima del codeudor
        if ($edad_codeudor > 0 and $edad_codeudor < $tasa->getFieldValue('Edad_min')) {
            return "La edad del codeudor es menor al limite permitido.";
        }
        //verificar limite edad maximo del codeudor
        if ($edad_codeudor > 0 and $edad_codeudor > $tasa->getFieldValue('Edad_max')) {
            return "La edad del codeudor es mayor al limite permitido.";
        }
    }

    public function lista_planes($tipo)
    {
        $criterio = "((Corredor:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId() . ") and (Product_Category:equals:$tipo))";
        return $this->searchRecordsByCriteria("Products", $criterio);
    }
}
