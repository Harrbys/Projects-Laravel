<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleados;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmpleadosController extends Controller
{
    public function index(){
        return Empleados::all();
    }

    public function store(Request $request){

        $rules = [
            'codigo' => 'required|unique:empleados',
            'full_name' => 'required',
            'telefono' => 'required',
            'cargo' => 'reqired',
            'area' => 'reqired',
            'jefe' => 'integer'

        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            Log::info('Error en los paraemtros ingresados');
            return response()->json($validator->errors(), 400);
        }else{
            // $data = $request->all();
            // $create = Empleados::create($data)
            Empleados::create([
                'codigo' => $request->codigo,
                'full_name' => $request->full_name,
                'telefono' => $request->telefono,
                'cargo' => $request->cargo,
                'area' => $request->area,
                'jefe' => $request->jefe,
            ]);

            return response()->json('Empleado registrado exitosamente');
        }
    }
}
