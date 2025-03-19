<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleados;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmpleadosController extends Controller
{

    /**
     * Funcion para visualizar todos los registros de empleados.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function index(){
        return Empleados::all();
    }

    /**
     * Funcion para buscar un empleado especifico.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

     public function show($id){
        return Empleados::find($id);
     }

    /**
     * Funcion para crear un registro de un empleado con jefe.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

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

    /**
     * Funcion para modificar un registro de un empleado.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

     public function update(Request $request){

        $rules = [
            'codigo' => 'requred',
            'full_name' => '',
            'telefono' => '',
            'cargo' => '',
            'area' => '',
            'jefe' => ''
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

        $empleadoexist = Empleados::where('codigo', $request->codigo)->first();

        if($empleadoexist == null){
            Log::info('Error el empleado no se encontro', ['Codigo' => $request->codigo]);
            return response()->json([
                'succes' => false,
                'message' => 'Error, Empleado no encontrado',
                'errors' => ['codigo' => ['No se encontro el empleado con el codigo proporcionado']]
            ], 400);
        }

        try{

            $empleadoexist->update([
                'full_name' => $request->full_name ?? $empleadoexist->full_name,
                'telefono' => $request->telefono ?? $empleadoexist->telefono,
                'cargo' => $request->cargo ?? $empleadoexist->cargo,
                'area' => $request->area ?? $empleadoexist->area,
                'jefe' => $request->jefe ?? $empleadoexist->jefe
            ]);

            return response()->json([
                'succes' => true,
                'message' => 'Empleado modificado exitosamente',
                'errors' => $empleadoexist
            ], 200);

        }catch (\Exception $e){
            Log::error('error al modificar el empleado: ' . $e->getMessage());
            return response()->json([
                'succes' => false,
                'message' => 'Error interno al modifiacr el empleado',
                'errors' => $e->getMessage()
            ], 400);
        }
     }

     /**
      * Funcion para eliminar un empleado.
      * Author: Santiago Hoyos Baquero
      * Date: 2025-03-19
      * @param  \Illuminate\Http\Request  request
      * @return \Illuminate\Http\Response
      */

      public function destroy($id){
            
        $empleadoexist = Empleados::find($id);

        if ($empleadoexist == null) {
            Log::info('Error: empleado no encontrado', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Error, empleado no encontrado',
                'errors' => ['id' => ['No se encontró un empleado con el ID proporcionado.']]
            ], 404);
        }
    
        try {
            
            $empleadoexist->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'empleado eliminado exitosamente',
                'data' => $empleadoexist 
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Error al eliminar el empleado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno al eliminar el empleado',
                'error' => $e->getMessage()
            ], 500); 
        }
    }
}
