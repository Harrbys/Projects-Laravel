<?php

namespace App\Http\Controllers;

use App\Models\productos;
use App\Models\proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function Illuminate\Events\queueable;

class ProductController extends Controller
{
    
    /**
     * Funcion para visualizar todos los registros de productos.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function index(){
        return productos::all();
    }
    
    /**
     * Funcion para buscar un producto especifico.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */
    
    public function show($id){
            $productos = productos::find($id);
            if(productos::where('id',$productos->id)->exists()){
                return response()->json($productos, 200);
            }else{
                return response()->json("Producto no encontrado", 404);
            }
     }



     /**
      * Funcion para crear un registro de un producto.
      * Author: Santiago Hoyos Baquero
      * Date: 2025-03-19
      * @param  \Illuminate\Http\Request  request
      * @return \Illuminate\Http\Response
      */

    public function store(Request $request){

            
            $rules = [
                'nombre' => 'required',
                'codigo' => 'required|unique:productos,codigo',
                'descripcion' => 'required',
                'precio' => 'required',
                'stock' => 'required',
                'proveedores_id' => 'required'
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

                $productoccreado = productos::create([
                    'nombre' => $request->nombre,
                    'codigo' => $request->codigo,
                    'descripcion' => $request->descripcion,
                    'precio' => $request->precio,
                    'stock' => $request->stock,
                    'proveedores_id' => $request->proveedores_id
                ]);

                return response()->json([
                    'succes' => true,
                    'message' => 'Producto creado exitosamente',
                    'data' => $productoccreado
                ]);

            }catch(\Exception $e){
                Log::error('Error al crear el producto: ' . $e->getMessage());
                return response()->json([
                    'succes' => false,
                    'message' => 'Error interno al crear el cliente',
                    'data' => $e->getMessage()
                ], 400);
            }
    }

    /**
     * Funcion para modificar el registro de un producto.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request){

                $rules = [
                    'codigo' => 'required',
                    'nombre' => 'required',
                    'descripcion' => 'required',
                    'precio' => 'required',
                    'stock' => 'required',
                    'proveedores_id' => 'required'
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
        
                $prodcutoexist = productos::where('codigo', $request->codigo)->first();

                if($prodcutoexist == null){
                    Log::info('Error el producto no se encontro', ['Codigo' => $request->codigo]);
                    return response()->json([
                        'succes' => false,
                        'message' => 'Error producto no econtrado',
                        'errors' => ['Codigo' => ['No se encontro el producto con el codigo proporcionado.']]
                    ], 400);
                }

                try{
        
                    $prodcutoexist->update([
                        'nombre' => $request->nombre ?? $prodcutoexist->codigo,
                        'codigo' => $request->codigo ?? $prodcutoexist->codigo,
                        'descripcion' => $request->descripcion ?? $prodcutoexist->descripcion,
                        'precio' => $request->precio ?? $prodcutoexist->precio,
                        'stock' => $request->stock ?? $prodcutoexist->stock,
                        'proveedores_id' => $request->proveedores_id ?? $prodcutoexist->proveedores
                    ]);

                return response()->json([
                    'succes' => true,
                    'message' => 'Producto modificado exitosamente',
                    'errors' => $prodcutoexist
                ]);
                
            } catch (\Exception $e) {
                Log::error('Error al modificar el producto: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno al modificar el producto',
                    'error' => $e->getMessage() 
                ], 400); 
            }
    }


    /**
     * Funcion para eliminar un registro de un producto.
     * Author: Santiago Hoyos Baquero
     * Date: 2025-03-19
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */

    public function destroy($id){

        $prodcutoexist = productos::find($id);

        if ($prodcutoexist == null){
            Log::info('Error: Producto no econtrado', ['Codigo' => $id]);
            return response()->json([
                'succes' => false,
                'message' => 'Error, Producto no econtrado',
                'errors' => ['Id' => ['No se econtro el producto con el ID proporcionado']]
            ], 400);
        }
           try{

                $prodcutoexist->delete();

                return response()->json([
                    'succes' => true,
                    'message' => 'Producto eliminado exitosamente',
                    'data' => $prodcutoexist
                ], 200);
           }catch (\Exception $e){
                Log::error('Error al eliminar el producto: ' . $e->getMessage());
                return response()->json([
                    'succes' => false,
                    'message' => 'Error interno al eliminar el producto',
                    'errors' => $e->getMessage()
                ], 500);
           }
}

public function vista()
 {
    return view('mensaje', ['nombre' => 'Laravel']);
}

}
