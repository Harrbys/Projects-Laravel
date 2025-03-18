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
    public function index()
    {
        return empresa::all();
    }

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

    public function show($id)
    {
        return empresa::find($id);
    }

    public function update(Request $request)
    {
        try{

            $rules = [
                'codigo' => 'required',
                'nombre' => 'required',
                'direccion' => 'required',
                'telefono' => 'required'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }else{
                $data = $request->all();
                $empresa = empresa::where('codigo', $request->codigo)->first();
                $empresa->update($data);
                return response()->json([
                    'succes' => True,
                    'message' => 'Empresa registrada exitosamente',
                    'data' => $data
                ]);
            }
        }catch(Exception $e){
            Log::error('Error en la capa 8' . $e->getMessage());
            return response()->json([
                'succes' => false,
                'message' => 'Error en la capa 8 sino en el codigo'
            ], 500);
        }
    }


    public function destroy($id)
    {
        try{
            $empresa = empresa::find($id);
            if(!$empresa){
                return response()->json("No se encontro la empresa para eliminarla", 400);    
            }
            $empresa->delete();
            return response()->json("Empresa eliminada exitosamente", 200);
        }catch(Exception $e){
            Log::error('Error en la capa 8' . $e->getMessage());
            return response()->json([
                'succes' => false,
                'message' => 'Error en la capa 8 sino en el codigo'
            ], 500);
        }
    }
}
