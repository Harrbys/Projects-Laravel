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

public function index()
    {
        return proveedores::all();
}

public function store(Request $request)
    {
        //se valida que los campos requeridos no esten vacios
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

public function update(Request $request)
    {
    $rules = [
        'nombre' => 'required',
        'cedula' => 'required',
        'direccion' => 'required',
        'telefono' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    } else {
        // Buscar el producto usando el code
        $product = proveedores::where('cedula', $request->cedula)->first();

        if (!$product) {
            return response()->json(['message' => 'Proveedor no encontrado'], 404);
        }else{
            $data = $request->all();
            $product->update($data);
            return response()->json("Proveedor actualizado exitosamente", 200);
        }
    }
}

public function destroy($id)
    {
        $product = proveedores::find($id);
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
