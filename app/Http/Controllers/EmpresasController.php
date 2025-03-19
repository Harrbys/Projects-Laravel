<?php

namespace App\Http\Controllers;

use App\Models\empresa;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmpresasController extends Controller
{

    /**
     * Funcion para visualizar todos los registros de empresas.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return empresa::all();
    }

    /**
     * Funcion para buscar una empresa especifica.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */
    
    public function show($id)
    {
        return empresa::find($id);
    }

    /**
     * Funcion para crear un registro de una empresa.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        $rules = [
            'codigo' => 'required|unique:empresas',
            'nombre' => 'required',
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
            
                $empresacreada = empresa::create([
                    'codigo' => $request->codigo,
                    'nombre' => $request->nombre,
                    'direccion' => $request->direccion,
                    'telefono' => $request->telefono
                ]);
        
                return response()->json([
                    'success' => true,
                    'message' => 'empresa creada exitosamente',
                    'data' => $empresacreada 
                ], 200);
        
            } catch (\Exception $e) {
    
                Log::error('Error al crear la empresa: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno al crear la empresa',
                    'error' => $e->getMessage() 
                ], 400); 
            }

    }

    /**
     * Funcion para midificar un registro de una empresa.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {

            $rules = [
                'codigo' => 'required',
                'nombre' => 'required',
                'direccion' => 'required',
                'telefono' => 'required'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'succes' => false,
                    'message' => 'Error de validacion',
                    'errors' => $validator->errors()
                ], 400);
            }
            $empresaexist = empresa::where('codigo',$request->codigo)->first();

            if($empresaexist == null){
                Log::info('Error la empresa no se encontro', ['Codigo' => $request->codigo]);
                return response()->json([
                    'succes' => false,
                    'message' => 'Error, Empresa no encontrada',
                    'erorrs' => ['codigo'=> ['No se encontro el codigo de la empresa']]
                ], 400);
            }

            try{

                $empresaexist->update([
                    'nombre' => $request->nombre ?? $empresaexist->nombre,
                    'direccion' => $request->direccion ?? $empresaexist->direccion,
                    'telefono' => $request->telefono ?? $empresaexist->telefono
                ]);

                return response()->json([ 
                    'succes' => true,
                    'message' => 'Empresa modificada exitosamente',
                    'data' => $empresaexist
                ]);

            }catch (\Exception $e){
                Log::error('Error al modificar la empresa: ' .
                $e->getmessage());
                return response()->json([
                    'succes' => false,
                    'message' => 'Error interno al modificar la empresa',
                    'error' => $e->getMessage()
                ], 400);
            }

        }

    /**
     * Funcion para elinminar un registro de una empresa.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

        public function destroy($id){
            
            $empresaxist = empresa::find($id);
            if ($empresaxist == null) {
                Log::info('Error: empresa no encontrado', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Error, empresa no encontrado',
                    'errors' => ['id' => ['No se encontró un empresa con el ID proporcionado.']]
                ], 404);
            }
        
            try {
                
                $empresaxist->delete();
        
                return response()->json([
                    'success' => true,
                    'message' => 'empresa eliminado exitosamente',
                    'data' => $empresaxist 
                ], 200);
        
            } catch (\Exception $e) {
                Log::error('Error al eliminar el empresa: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno al eliminar el empresa',
                    'error' => $e->getMessage()
                ], 500); 
            }
        }
}
