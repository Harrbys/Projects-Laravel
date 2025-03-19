<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class categoriasController extends Controller
{

    /**
     * Funcion para visualizar todos los registros de categorias.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return categorias::all();
    }
    
    /**
     * Funcion para buscar una categoria especifica.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */
    
    public function show($id)
    {
        return categorias::find($id);
    }
    
    /**
      * Funcion para crear un registro de una categoria.
      * Author: Santiago Hoyos Baquero
      * Date: 2025-03-19
      * @param  \Illuminate\Http\Request  request
      * @return \Illuminate\Http\Response
      */

    public function store(Request $request){

        $rules = [
            'codigo' => 'required|unique:categorias',
            'nombre' => 'required',
            'descripcion' => 'required'
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

        try{
            $categoriacreado = categorias::create([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion
            ]);

            return response()->json([
                'succes' => True,
                'message' => 'categoria creado exitosamente',
                'errors' => $categoriacreado
            ], 200);

        }catch (\Exception $e){
            Log::error('Error al craer una categoria' . $e->getMessage());
            return response()->json([
                'succes' => false,
                'message' => 'Erro interno al crear una categoria',
                'erros' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Funcion para modificar un registro de una categoria.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request){
        $rules = [
            'codigo' => 'required',
            'nombre' => '',
            'descripcion' => ''
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

        $categoriaexist = categorias::where('codigo', $request->codigo)->first();

        if($categoriaexist == null){
            Log::info('Error la categoria no se encontro', ['Codigo' => $request->codigo]);
            return response()->json([
                'succes' => false,
                'message' => 'Error, Categoria no encontrada',
                'errors' => ['Codigo' => ['No se encontro una categoria con el codigo proporcionado.']]
            ], 400);
        }

        try{

            $categoriaexist->update([
                'nombre' => $request->nombre ?? $categoriaexist->nombre,
                'descripcion' => $request->descripcion ?? $categoriaexist->descripcion
            ]);

            return response()->json([
                'succes' => True,
                'message' => 'Categoria modificada exitosamente',
                'errors' => $categoriaexist
            ], 200);
        } catch (\Exception $e){
            Log::error('Error al modificar la categoria: ' . $e->getMessage());
            return response()->json([
                'succes' => false,
                'message' => 'Error interno al modificar la categoria',
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Funcion para eliminar un registro de una categoria.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

     public function destroy($id){
            
        $categoriaexist = categorias::find($id);

        if ($categoriaexist == null) {
            Log::info('Error: categoria no encontrado', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Error, categoria no encontrado',
                'errors' => ['id' => ['No se encontró un categoria con el ID proporcionado.']]
            ], 404);
        }
    
        try {
            
            $categoriaexist->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'categoria eliminado exitosamente',
                'data' => $categoriaexist 
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Error al eliminar el categoria: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno al eliminar el categoria',
                'error' => $e->getMessage()
            ], 500); 
        }
    }

}
