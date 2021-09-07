<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use Illuminate\Http\Request;

class Sesiones extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("sesiones.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sesion = new Sesion;
        $sesion->establecer($request->input("correo"), $request->input("pass"));
        $nuevo_usuario = $sesion->validar_en_crm();
        if (!empty($nuevo_usuario)) {
            session()->put('usuario', $nuevo_usuario);
            return redirect('/');
        } else {
            return back()->with('alerta', "Usuario o contraseña incorrectos");
        }
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
    public function edit()
    {
        return view("sesiones.edit");
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
        $sesion = new Sesion;
        $sesion->establecer(session()->get('usuario')->getFieldValue('Email'), $request->input("pass"));
        $sesion->cambiar_contrasena();
        return back()->with('alerta', "Contraseña cambiada exitosamente, los cambios se reflejaran en el proximo inicio de sesión");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        session()->flush();
        return redirect("/login");
    }
}
