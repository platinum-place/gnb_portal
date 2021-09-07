<?php

namespace App\Http\Controllers;

use App\Models\Emision;
use Illuminate\Http\Request;

class Emisiones extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $emision = new Emision;
        $registro = $emision->obtener_registro($id);
        switch ($registro->getFieldValue("Type")) {
            case 'Incendio':
                return view('Emisiones.incendio', ["emision" => $registro]);
                break;

            case 'Vida':
                $requisitos = $emision->obtener_requisitos($registro->getFieldValue("Coberturas")->getEntityId());
                return view('Emisiones.vida', ["emision" => $registro, "requisitos" => $requisitos[0], "corequisitos" => $requisitos[1]]);
                break;

            case 'Desempleo':
                $requisitos = $emision->obtener_requisitos($registro->getFieldValue("Coberturas")->getEntityId());
                return view('Emisiones.desempleo', ["emision" => $registro, "requisitos" => $requisitos[0]]);
                break;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
