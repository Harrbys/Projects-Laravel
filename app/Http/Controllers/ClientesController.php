<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClientesController extends Controller
{

    /**
     * funcion para visualizar todos los registros de clientes.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function index(){
        return Cliente::all();
    }

    /**
     * Funcion para buscar un cliente especifico.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function show($id){
        return Cliente::find($id);
    }

    /**
     * Funcion para crear un registro de un cliente.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){
        
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
                'data' => $validator->errors()
            ], 400);
        }
    
        try {
            
            $clienteCreado = Cliente::create([
                'cedula' => $request->cedula,
                'full_name' => $request->full_name,
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
                'data' => $e->getMessage() 
            ], 400); 
        }
    }


    /**
     * funcion para actualizar el registro del cliente .
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request){

        $rules = [
            'cedula' => 'required',
            'full_name' => '',
            'direccion' => '',
            'telefono' => ''
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::info('Error en los parámetros ingresados', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $validator->errors()
            ], 400);
        }

        $clientexist = Cliente::where('cedula',$request->cedula)->first();

        if($clientexist == null){
            Log::info('Error el cliente no se encontro', ['Cedula' => $request->cedula]);
            return response()->json([
                'success' => false,
                'message' => 'Error, Cliente no encontrado',
                'data' => ['cedula' => ['No se encontró un cliente con la cédula proporcionada.']]
            ], 400);
        }
    
        try {
            
            $clientexist->update([
                'full_name' => $request->full_name ?? $clientexist->full_name,
                'direccion' => $request->direccion ?? $clientexist->direccion,
                'telefono' => $request->telefono ?? $clientexist->telefono
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Cliente modificado exitosamente',
                'data' => $clientexist 
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Error al modificar el cliente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno al modificar el cliente',
                'data' => $e->getMessage() 
            ], 400); 
        }
    }
    
    /**
     * Funcion para eliminar registro.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

        public function destroy($id){
            
            $clientexist = Cliente::find($id);

            if ($clientexist == null) {
                Log::info('Error: Cliente no encontrado', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Error, Cliente no encontrado',
                    'data' => ['id' => ['No se encontró un cliente con el ID proporcionado.']]
                ], 400);
            }
        
            try {
                
                $clientexist->delete();
        
                return response()->json([
                    'success' => true,
                    'message' => 'Cliente eliminado exitosamente',
                    'data' => $clientexist 
                ], 200);
        
            } catch (\Exception $e) {
                Log::error('Error al eliminar el cliente: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno al eliminar el cliente',
                    'data' => $e->getMessage()
                ], 500); 
            }
        }

}
