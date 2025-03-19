<?php

namespace App\Http\Controllers;

use App\Models\empresa;
use App\Models\proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function Illuminate\Events\queueable;

class ProveedoresController extends Controller
{


    /**
     * Funcion para visualizar todos los proveedores.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function index()
        {
            return proveedores::all();
    }

    /**
     * Funcion para buscar un proveedor especifico.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */
    
    public function show($id)
        {
            $proveedor = proveedores::find($id);
            // dd($proveedor);
            if(proveedores::where('id',$proveedor->id)->exists()){
                return response()->json($proveedor, 200);
            }else{
                return response()->json("Producto no encontrado", 404);
            }

    }

    /**
     * Funcion para crear un registro de un proveedor.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){
        
            $rules = [
                'nombre' => 'required',
                'cedula' => 'required|unique:proveedores,cedula',
                'direccion' => 'required',
                'telefono' => 'required',
                'empresa_id' => 'required'
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
                
                $proveedorcreado = proveedores::create([
                    'nombre' => $request->nombre,
                    'cedula' => $request->cedula,
                    'direccion' => $request->direccion,
                    'telefono' => $request->telefono,
                    'empresa_id' => $request->empresa_id
                ]);
        
                return response()->json([
                    'success' => true,
                    'message' => 'Proveedor creado exitosamente',
                    'data' => $proveedorcreado 
                ], 200);
        
            } catch (\Exception $e) {

                Log::error('Error al crear el cliente: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno al crear el Proveedor',
                    'error' => $e->getMessage() 
                ], 400); 
            }
    }


    /**
    * Funcion para modificar un registro de un proveedor.
    * Author: Santiago Hoyos Baquero
    * Date: 2025-03-19
    * @param  \Illuminate\Http\Request  request
    * @return \Illuminate\Http\Response
    */

    public function update(Request $request)
        {
        $rules = [
            'cedula' => 'required',
            'nombre' => '',
            'direccion' => '',
            'telefono' => ''
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

        $proveexist = proveedores::where('cedula', $request->cedula)->first();

        if($proveexist == null){
            Log::info('error el proveedor no se encuentra', ['Cedula' => $request->cedula]);
            return response()->json([
                'succes' => false,
                'message' => 'Error, Cliente no encontrado',
                'errors' => ['cedula' => ['No se encontró un proveedor con la cédula proporcionada.']]
                ], 404);
        }

        try{
            
            $proveexist->update([
                'nombre' => $request->nombre ?? $proveexist->nombre,
                'direccion' => $request->direccion ?? $proveexist->direccion,
                'telefono' => $request->telefono ?? $proveexist->telefono
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Proveedor modificado exitosamente',
                'data' => $proveexist
            ], 200);
        }catch (\Exception $e){
            Log::error('Error al modificar el proveedor: ' .  $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno al modifiacr el proveedor',
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Funcion para eliminar un registro de un proveedor.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){
                
        $proveexist = proveedores::find($id);
        if ($proveexist == null) {
            Log::info('Error: proveedores no encontrado', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Error, proveedores no encontrado',
                'errors' => ['id' => ['No se encontró un proveedores con el ID proporcionado.']]
            ], 404);
        }

        try {
            
            $proveexist->delete();

            return response()->json([
                'success' => true,
                'message' => 'proveedor eliminado exitosamente',
                'data' => $proveexist 
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al eliminar el proveedor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno al eliminar el proveedor',
                'error' => $e->getMessage()
            ], 500); 
        }
    }

public function vista()
 {
    return view('mensaje', ['nombre' => 'Laravel']);
}

}
