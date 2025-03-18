<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\clientes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClientesController extends Controller
{
    public function index(){
        return Cliente::all();
    }

    public function show($id){
        return Cliente::find($id);
    }


    public function store(Request $request)
    {
        
        $rules = [
            'cedula' => 'required|unique:clientes',
            'full_name' => 'required',
            'direccion' => 'required',
            'telefono' => 'required'
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            Log::info('Error en los parámetros ingresados', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }
    
        try {
            
            $clienteCreado = Cliente::create([
                'cedula' => $request->cedula,
                'Full_name' => $request->full_name,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Cliente creado exitosamente',
                'data' => $clienteCreado 
            ], 200);
    
        } catch (\Exception $e) {

            Log::error('Error al crear el cliente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno al crear el cliente',
                'error' => $e->getMessage() 
            ], 400); 
        }
    }

}
