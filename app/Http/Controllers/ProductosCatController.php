<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use App\Models\productos;
use App\Models\ProductosCat;
use App\Models\proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class ProductosCatController extends Controller
{

    /**
     * Funcion para visualizar todos los registros entre categorias y productos.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = ProductosCat::all();
        return response()->json($productos);
    }

    /**
     * Funcion para buscar una relacion especifica.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

     public function show($id)
     {
         try {
            
             $productosCat = ProductosCat::find($id);
     
             if (!$productosCat) {
                 return response()->json([
                     'success' => false,
                     'message' => 'Relacion no encontrado'
                 ], 404);
             }
     
             $productosCat->load('producto', 'categoria');
     
             if (!$productosCat->producto || !$productosCat->categoria) {
                 return response()->json([
                     'success' => false,
                     'message' => 'Producto o categoría no encontrados'
                 ], 404);
             }
     
             $response = [
                 'id' => $productosCat->id,
                 'producto_id' => $productosCat->producto_id,
                 'producto' => [
                     'nombre' => $productosCat->producto->nombre,
                     'codigo' => $productosCat->producto->codigo,
                     'descripcion' => $productosCat->producto->descripcion,
                     'precio' => $productosCat->producto->precio,
                     'stock' => $productosCat->producto->stock,
                 ],
                 'categoria_id' => $productosCat->categoria_id,
                 'categoria' => [
                     'nombre' => $productosCat->categoria->nombre,
                     'codigo' => $productosCat->categoria->codigo,
                     'descripcion' => $productosCat->categoria->descripcion,
                 ]
             ];
     
             return response()->json([
                 'success' => true,
                 'message' => 'Relación encontrada exitosamente',
                 'data' => $response
             ], 200);
     
         } catch (\Exception $e) {
            
             return response()->json([
                 'success' => false,
                 'message' => 'Error interno al buscar la relación',
                 'error' => $e->getMessage()
             ], 500);
         }
     }

     /**
      * Funcion para buscar un registro de la relacion por medio de categoria.
      * Author: Santiago Hoyos Baquero
      * Date: 2025-03-19
      * @param  \Illuminate\Http\Request  request
      * @return \Illuminate\Http\Response
      */

      public function BuscarPorCat(Request $request)
      {
          try {
            
              $rules = [
                  'codigo' => 'required'
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
      
              $categoria = categorias::where('codigo', $request->codigo)->first();
      
              if (!$categoria) {
                  Log::info('Error: Categoría no encontrada', ['codigo' => $request->codigo]);
                  return response()->json([
                      'success' => false,
                      'message' => 'Error, categoría no encontrada',
                      'errors' => ['codigo' => ['No se encontró una categoría con el código proporcionado.']]
                  ], 404);
              }
      
              $productos = ProductosCat::with(['producto.proveedor'])
                  ->where('categoria_id', $categoria->id)
                  ->get();
      
              if ($productos->isEmpty()) {
                  return response()->json([
                      'success' => false,
                      'message' => 'No hay productos para esta categoría'
                  ], 404);
              }
      
              $respuesta = [
                  'categoria' => [
                      'id' => $categoria->id,
                      'nombre' => $categoria->nombre,
                      'codigo' => $categoria->codigo,
                      'descripcion' => $categoria->descripcion,
                  ],
                  'productos' => $productos->map(function($productoCat) {
                      return [
                          'id' => $productoCat->id,
                          'producto_id' => $productoCat->producto_id,
                          'producto' => [
                              'nombre' => $productoCat->producto->nombre,
                              'codigo' => $productoCat->producto->codigo,
                              'descripcion' => $productoCat->producto->descripcion,
                              'precio' => $productoCat->producto->precio,
                              'stock' => $productoCat->producto->stock,
                          ],
                          'proveedor' => [
                              'id' => $productoCat->producto->proveedor->id,
                              'nombre' => $productoCat->producto->proveedor->nombre,
                              'cedula' => $productoCat->producto->proveedor->cedula,
                              'direccion' => $productoCat->producto->proveedor->direccion,
                              'telefono' => $productoCat->producto->proveedor->telefono,
                          ]
                      ];
                  })
              ];
      
              return response()->json([
                  'success' => true,
                  'message' => 'Datos encontrados exitosamente',
                  'data' => $respuesta
              ], 200);
      
          } catch (\Exception $e) {
            
              Log::error('Error en BuscarPorCat: ' . $e->getMessage());
              return response()->json([
                  'success' => false,
                  'message' => 'Error interno al buscar la categoría y sus productos',
                  'error' => $e->getMessage()
              ], 500);
          }
      }


    /**
     * Funcion para crear un registro de una relacion.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){

        $rules = [
            'producto_id' => 'required',
            'categoria_id' => 'required'
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

        try{
            $prodcatexist = ProductosCat::create([
                'producto_id' => $request->producto_id,
                'categoria_id' => $request->categoria_id,
            ]);

            return response()->json([
                'success' => True,
                'message' => 'Relacion craeda existosamente',
                'data' => $prodcatexist
            ], 200);

        }catch (\Exception $e){
            Log::error('error al crear la relacion: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno al crear la relacion',
                'data' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * funcion para modificar la relacion aun no confirmada.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

     public function update(Request $request)
     {
        
         $request->validate([
             'producto_id' => 'required|exists:productos,id',
             'categoria_id' => 'required|exists:categorias,id',
         ]);
     
         try {
            
             $producat = ProductosCat::where('producto_id', $request->producto_id)
                 ->where('categoria_id', $request->categoria_id)
                 ->first();
     
             if (!$producat) {
                 return response()->json([
                     'success' => false,
                     'message' => 'El registro no existe',
                     'errors' => [
                         'producto_id' => ['No se encontró un registro con el producto_id y categoria_id proporcionados.']
                     ]
                 ], 400);
             }
     
             $producat->update([
                 'producto_id' => $request->producto_id,
                 'categoria_id' => $request->categoria_id,
             ]);
     
             return response()->json([
                 'success' => true,
                 'message' => 'Registro actualizado con éxito',
                 'data' => $producat
             ], 200);
     
         } catch (\Throwable $e) {
            
             Log::error('Error al actualizar el registro: ' . $e->getMessage());
             return response()->json([
                 'success' => false,
                 'message' => 'Ocurrió un error al actualizar el registro',
                 'error' => $e->getMessage()
             ], 400);
         }
    }


    public function destroy($id){

        $productocatexist = ProductosCat::find($id);

        if($productocatexist == null){
            Log::info('Error, la relacion no se encontro', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Error la relacion no se encontro',
                'data' => ['id' => ['No se encontro la relacion con el id proporcionado']]
            ],400);
        }

        try {
                
            $productocatexist->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'la relacion se ha eliminado exitosamente',
                'data' => $productocatexist 
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Error al eliminar el la relacion: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno al eliminar la relacion',
                'data' => $e->getMessage()
            ], 500); 
        }
    }
}

