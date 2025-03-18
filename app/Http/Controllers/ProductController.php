<?php

namespace App\Http\Controllers;

use App\Models\productos;
use App\Models\proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Illuminate\Events\queueable;

class ProductController extends Controller
{

public function index()
    {
        return productos::all();
}


/**
 * Store a newly created resource in storage.
 * Author: Santiago Hoyos Baquero
 * Date: 2025-02-06
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */

public function store(Request $request)
    {

        
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
            return response()->json($validator->errors(), 400);
        }
        $proveedores = proveedores::where('id',$request->proveedores_id)->first(); 
        if (!$proveedores) {
            return response()->json(['message' => 'Proveedor no encontrado'], 404);
        }

        try{
            $createdProducto = productos::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'stock' => $request->stock,
                'proveedores_id' => $request->proveedores_id
            ]);

            return response()->json("Producto registrado exitosamente", 201);
        }catch(\Exception $e){
            return response()->json("Error al registrar un producto " . $e->getMessage(), 400);
        }
}

public function show($id)
    {
        $productos = productos::find($id);
        if(productos::where('id',$productos->id)->exists()){
            return response()->json($productos, 200);
        }else{
            return response()->json("Producto no encontrado", 404);
        }

}

public function update(Request $request)
    {

        try{
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
                return response()->json($validator->errors(), 400);
            }
    
            $product = productos::where('codigo', $request->codigo)->first();
    
            if (!$product) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }
    
            $product->update([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'stock' => $request->stock,
                'proveedores_id' => $request->proveedores_id
            ]);

            return response()->json("Producto actualizado exitosamente", 200);
            
        }catch(\Exception $e){
            return response()->json("Error al actualizar un producto" . $e->getMessage(), 400);
        }

}

public function destroy($id)
    {
        $product = productos::find($id);
        if ($product) {
            $product->delete();
            return response()->json("Producto eliminado exitosamente", 200);
        } else {
            return response()->json("Producto no encontrado", 404);
        }
}

public function vista()
 {
    return view('mensaje', ['nombre' => 'Laravel']);
}

}
