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
    public function index()
    {
        $productos = ProductosCat::all();
        return response()->json($productos);
    }

    public function show($id)
{

    $productos = ProductosCat::find($id);

    if ($productos) {

        $product = productos::find($productos->producto_id);
        
        $categoria = categorias::find($productos->categoria_id);

        if ($product && $categoria) {

            $productos = [
                'id' => $productos->id,
                'producto_id' => $productos->producto_id,
                'producto' => [
                    'nombre' => $product->nombre,
                    'codigo' => $product->codigo,
                    'descripcion' => $product->descripcion,
                    'precio' => $product->precio,
                    'stock' => $product->stock,
                ],
                'categoria_id' => $productos->categoria_id,
                'categoria' => [
                    'nombre' => $categoria->nombre,
                    'codigo' => $categoria->codigo,
                    'descripcion' => $categoria->descripcion,
                ]
            ];

            return response()->json($productos, 200);
        } else {

            return response()->json("Producto o Categoría no encontrados", 404);
        }
    } else {

        return response()->json("ProductoCat no encontrado", 404);
    }
}

public function BuscarPorCat(Request $request)
{
    $rules = [
        'codigo' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $categoria = categorias::where('codigo', $request->codigo)->first();

    if (!$categoria) {
        return response()->json("Categoría no encontrada", 404);
    }

    $productos = ProductosCat::where('categoria_id', $categoria->id)->get();

 if ($productos->isEmpty()) {
        return response()->json("No hay productos para esta categoría", 404);
    }


    $productosConInfo = $productos->map(function($productoCat) {
        
        $producto = productos::find($productoCat->producto_id);


        $proveedor = proveedores::find($producto->proveedores_id);

        
        return [
            'id' => $productoCat->id,
            'producto_id' => $productoCat->producto_id,
            'producto' => [
                'nombre' => $producto->nombre,
                'codigo' => $producto->codigo,
                'descripcion' => $producto->descripcion,
                'precio' => $producto->precio,
                'stock' => $producto->stock,
            ],
            'proveedor' => [
                'id' => $proveedor->id,
                'nombre' => $proveedor->nombre,
                'cedula' => $proveedor->cedula,
                'direccion' => $proveedor->direccion,
                'telefono' => $proveedor->telefono,
            ]
        ];
    });

    $respuesta = [
        'categoria' => [
            'id' => $categoria->id,
            'nombre' => $categoria->nombre,
            'codigo' => $categoria->codigo,
            'descripcion' => $categoria->descripcion,
        ],
        'productos' => $productosConInfo 
    ];


    return response()->json($respuesta, 200);
}




    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required',
            'categoria_id' => 'required'
        ]);

        if (!$request->producto_id || !$request->categoria_id) {
            return response()->json([
                'message' => 'Datos incompletos'
            ]);
        }else{
            $prod = productos::find($request->producto_id);
            $cat = categorias::find($request->categoria_id);
            if (!$prod || !$cat) {
                return response()->json([
                    'message' => 'Producto o categoria no encontrada'
                ], 400);
            }
            $producto = ProductosCat::create($request->all());
            return response()->json(['message' => 'relacion registrada exitosamente'], 200);
        }        
    }


    public function update(Request $request)
    {
        try {
            
            $request->validate([
                'producto_id' => 'required|exists:productos,id', 
                'categoria_id' => 'required|exists:categorias,id', 
            ]);
    
            $producat = ProductosCat::where('producto_id', $request->producto_id)
                                   ->where('categoria_id', $request->categoria_id)
                                   ->first();
    
            if (!$producat) {
                return $this->errorResponse('El registro no existe', 404);
            }
    
            $producat->update([
                'producto_id' => $request->producto_id, 
                'categoria_id' => $request->categoria_id, 
            ]);
    
            return Response()->json(['message' => 'Registro actualizado con éxito'], 200);
    
        } catch (\Throwable $th) {

            return $this->errorResponse('Ocurrió un error al actualizar el registro: ' . $th->getMessage(), 500);
        }
    }
    
    private function errorResponse($message, $statusCode)
    {
        return response()->json(['message' => $message], $statusCode);
    }   

    }

