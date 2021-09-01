<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Home extends BaseController
{
	public function index()
	{
		$zoho = new Zoho;
		$lista = array();
		$polizas = 0;
		$vencidas = 0;
		$evaluacion = 0;

		$criterio = "Account_Name:equals:" . session("usuario")->getFieldValue("Account_Name")->getEntityId();
		$emisiones = $zoho->searchRecordsByCriteria("Deals", $criterio);

		foreach ((array)$emisiones as $emision) {
			if (date("Y-m", strtotime($emision->getCreatedTime())) == date("Y-m")) {
				$lista[] = $emision->getFieldValue('Aseguradora')->getLookupLabel();

				$polizas++;

				if ($emision->getFieldValue('Stage') == "Proceso de validación") {
					$evaluacion++;
				}
			}

			if (date("Y-m", strtotime($emision->getFieldValue('Closing_Date'))) == date("Y-m")) {
				$vencidas++;
			}
		}

		return view('index', [
			"lista" => array_count_values($lista),
			"polizas" => $polizas,
			"evaluacion" => $evaluacion,
			"vencidas" => $vencidas
		]);
	}
}
