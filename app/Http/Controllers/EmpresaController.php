<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresa::all();

        return $this->sendResponse($empresas, 'Todas Las Empresas');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'name' => 'required',
            'email' => 'required|email|unique:empresas',
            'logo' => 'required',
            'website' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error en la validacioin.', $validator->errors());
        }

        $path = $request->file('logo')->store('public');

        $inputs['logo'] = $path;
        $empresa = Empresa::create($inputs);

        return $this->sendResponse($empresa, 'Empresa creada Exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empresa = Empresa::find($id);

        if (is_null($empresa)) {
            return $this->sendError('Empresa no encontrada.');
        }

        return $this->sendResponse($empresa, 'Empresa encontrada exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresa $empresa)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:empresas,email,' . $empresa->id,
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error en la validacion.', $validator->errors());
        }

        if ($request->has('name')) {
            $empresa->name = $request->name;
        }

        if ($request->has('email')) {
            $empresa->email = $request->email;
        }

        if ($request->has('website')) {
            $empresa->website = $request->website;
        }

        if ($request->has('logo')) {
            $path = $request->file('logo')->store('public');
            $empresa->logo = $path;
        }

        if (!$empresa->isDirty()) {
            return $this->sendError('Debes enviar almenos un campo diferente.', [], 422);
        }

        $empresa->save();

        return $this->sendResponse($empresa, 'Empresa actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return $this->sendResponse($empresa, 'Empresa eliminada exitosamente.');
    }
}
